CREATE DATABASE IF NOT EXISTS insecure_blog;
USE insecure_blog;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(100),
    role VARCHAR(20)
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    content TEXT
);
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@test.com', 'admin123', 'admin'),
('john_doe', 'john@test.com', 'password123', 'user'),
('jane_smith', 'jane@test.com', 'jane456', 'user'),
('test_user', 'test@test.com', 'test', 'user');
INSERT INTO posts (user_id, title, content) VALUES
(1, 'Welcome to Insecure Blog!', 'This is a deliberately vulnerable blog application for educational purposes. It demonstrates various security vulnerabilities including SQL Injection, XSS, IDOR, and more.'),
(3, 'Test Post with HTML', '<h2>This post contains HTML</h2><p>You can try injecting <strong>scripts</strong> here!</p>');
SELECT 'Database setup complete!' AS Status;
SELECT 'Tables created: users, posts' AS Info;
SELECT CONCAT('Total users: ', COUNT(*)) AS Users FROM users;
SELECT CONCAT('Total posts: ', COUNT(*)) AS Posts FROM posts;
