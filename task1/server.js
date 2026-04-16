const express = require("express");
const mysql = require("mysql2");
const cors = require("cors");

const app = express();

app.use(cors());
app.use(express.json());
app.use(express.static(__dirname));


const db = mysql.createConnection({
    host: "localhost",
    user: "root",
    password: "Dasarivasu@827",  
    database: "taask1"
});

db.connect(err => {
    if (err) {
        console.log("Database connection failed");
    } else {
        console.log("Connected to MySQL");
    }
});



app.post("/addStudent", (req, res) => {

    const { name, email, dob, department, phone } = req.body;

    const sql = "INSERT INTO student (name, email, dob, department, phone) VALUES (?, ?, ?, ?, ?)";

    db.query(sql, [name, email, dob, department, phone], (err, result) => {
        if (err) {
            res.status(500).json({ message: "Error inserting data" });
        } else {
            res.json({ message: "Student stored successfully" });
        }
    });
});



app.get("/students", (req, res) => {

    const sql = "SELECT * FROM student";

    db.query(sql, (err, result) => {
        if (err) {
    console.log("Insert Error:", err);   
    res.status(500).json({ message: "Error inserting data" });
}
else {
            res.json(result);
        }
    });
});


app.listen(5000, () => {
    console.log("Server running at http://localhost:5000");
});
