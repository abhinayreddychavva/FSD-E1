const express = require('express');
const mysql = require('mysql2');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: 'Dasarivasu@827', // change if needed
    database: 'task2'
});

db.connect((err) => {
    if (err) {
        console.error('Database connection failed:', err);
        process.exit(1);
    }
    console.log('Connected to MySQL Database');
});

app.get('/students', (req, res) => {
    const { sortBy, dept } = req.query;

    let query = 'SELECT name, email, dept, DOB FROM student';
    let values = [];

    if (dept) {
        query += ' WHERE dept = ?';
        values.push(dept);
    }

    if (sortBy === 'name') {
        query += ' ORDER BY name ASC';
    } else if (sortBy === 'dob') {
        query += ' ORDER BY DOB ASC';
    }

    db.query(query, values, (err, result) => {
        if (err) {
            return res.status(500).json({
                success: false,
                message: err.message
            });
        }

        res.json({
            success: true,
            data: result
        });
    });
});

app.get('/students/count', (req, res) => {
    const query = `
        SELECT dept, COUNT(*) AS total
        FROM student
        GROUP BY dept
    `;

    db.query(query, (err, result) => {
        if (err) {
            return res.status(500).json({
                success: false,
                message: err.message
            });
        }

        res.json({
            success: true,
            data: result
        });
    });
});

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server running on http://localhost:${PORT}`);
});
