use task5;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    balance DECIMAL(10,2)
);
CREATE TABLE merchants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    balance DECIMAL(10,2)
);
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    merchant_id INT,
    amount DECIMAL(10,2),
    status VARCHAR(50),
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (merchant_id) REFERENCES merchants(id)
);
INSERT INTO users (name, balance)
VALUES
('Arjun', 5000),
('Bhavya', 8000);
INSERT INTO merchants (name, balance)
VALUES
('Amazon', 10000),
('Flipkart', 15000);
SELECT * FROM users;
SELECT * FROM merchants;
use task5;
CREATE TABLE accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    balance DECIMAL(10,2) NOT NULL
);
INSERT INTO accounts (name, balance) VALUES
('User_Account', 5000.00),
('Merchant_Account', 2000.00);
SELECT * FROM accounts;
START TRANSACTION;

UPDATE accounts
SET balance = balance - 1000
WHERE id = 1 AND balance >= 1000;

UPDATE accounts
SET balance = balance + 1000
WHERE id = 2;

COMMIT;
SELECT * FROM accounts;
START TRANSACTION;

UPDATE accounts
SET balance = balance - 10000
WHERE id = 1 AND balance >= 10000;

UPDATE accounts
SET balance = balance + 10000
WHERE id = 2;

ROLLBACK;
SELECT * FROM accounts;
