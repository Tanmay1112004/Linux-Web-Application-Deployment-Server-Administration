USE student_portal;

INSERT INTO students (name, email, course, city) VALUES
('Aarav Sharma', 'aarav@example.com', 'Cloud Computing', 'Pune'),
('Priya Patil', 'priya@example.com', 'DevOps', 'Mumbai'),
('Rohan Deshmukh', 'rohan@example.com', 'Python', 'Nashik'),
('Sneha Kulkarni', 'sneha@example.com', 'AWS', 'Pune'),
('Vivek Joshi', 'vivek@example.com', 'Linux Administration', 'Nagpur'),
('Backup Test', 'backup-test@example.com', 'DevOps', 'Pune')
ON DUPLICATE KEY UPDATE name=VALUES(name);
