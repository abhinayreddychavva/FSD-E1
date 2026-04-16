use task6;
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    salary DECIMAL(10,2),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE employee_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT,
    action_type VARCHAR(50),
    action_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
DELIMITER //

CREATE TRIGGER after_employee_insert
AFTER INSERT ON employees
FOR EACH ROW
BEGIN
    INSERT INTO employee_logs (employee_id, action_type)
    VALUES (NEW.id, 'INSERT');
END //

DELIMITER ;
DELIMITER //

CREATE TRIGGER after_employee_update
AFTER UPDATE ON employees
FOR EACH ROW
BEGIN
    INSERT INTO employee_logs (employee_id, action_type)
    VALUES (NEW.id, 'UPDATE');
END //

DELIMITER ;
INSERT INTO employees (name, salary)
VALUES ('Arjun', 50000);

UPDATE employees
SET salary = 55000
WHERE id = 1;
SELECT * FROM employee_logs;
CREATE VIEW daily_activity AS
SELECT 
    DATE(action_time) AS activity_date,
    action_type,
    COUNT(*) AS total_actions
FROM employee_logs
GROUP BY DATE(action_time), action_type;
SELECT * FROM daily_activity;
