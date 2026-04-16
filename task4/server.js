const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'Dasarivasu@827',  // change if needed
    database: 'task4'
});

db.connect(err => {
    if (err) {
        console.error("Database connection failed:", err);
        process.exit(1);
    }
    console.log("Connected to task4 database");
});



app.get('/orders', (req, res) => {

    const query = `
        SELECT 
            c.name AS customer,
            p.product_name AS product,
            o.quantity,
            p.price,
            (p.price * o.quantity) AS total,
            o.order_date
        FROM orders o
        JOIN customers c ON o.customer_id = c.customer_id
        JOIN products p ON o.product_id = p.product_id
        ORDER BY o.order_date DESC
    `;

    db.query(query, (err, result) => {
        if (err) return res.status(500).json(err);
        res.json(result);
    });
});



app.get('/orders/highest', (req, res) => {

    const query = `
        SELECT *
        FROM (
            SELECT 
                c.name,
                (p.price * o.quantity) AS total
            FROM orders o
            JOIN customers c ON o.customer_id = c.customer_id
            JOIN products p ON o.product_id = p.product_id
        ) AS totals
        ORDER BY total DESC
        LIMIT 1
    `;

    db.query(query, (err, result) => {
        if (err) return res.status(500).json(err);
        res.json(result[0]);
    });
});



app.get('/customers/active', (req, res) => {

    const query = `
        SELECT c.name, COUNT(o.order_id) AS total_orders
        FROM customers c
        JOIN orders o ON c.customer_id = o.customer_id
        GROUP BY c.customer_id
        ORDER BY total_orders DESC
        LIMIT 1
    `;

    db.query(query, (err, result) => {
        if (err) return res.status(500).json(err);
        res.json(result[0]);
    });
});


app.listen(5000, () => {
    console.log("Server running on http://localhost:5000");
});
