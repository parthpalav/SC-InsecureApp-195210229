-- ========================================
-- INSECURE BLOG - DATABASE SETUP
-- Educational Demo - Deliberately Vulnerable
-- ========================================

-- Create Database
CREATE DATABASE IF NOT EXISTS insecure_blog;
USE insecure_blog;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS users;

-- ========================================
-- USERS TABLE
-- VULNERABILITIES:
-- - No constraints on password length
-- - No unique constraints on email
-- - No data validation
-- - Allows duplicate usernames/emails
-- ========================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(100),
    role VARCHAR(20)
);

-- ========================================
-- POSTS TABLE
-- VULNERABILITIES:
-- - No foreign key constraints
-- - No validation on content
-- - Allows XSS storage
-- - No length limits
-- ========================================

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    content TEXT
);

-- ========================================
-- INSERT SAMPLE DATA
-- Note: Passwords are stored in PLAIN TEXT (INSECURE!)
-- ========================================

-- Sample Users
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@test.com', 'admin123', 'admin'),
('john_doe', 'john@test.com', 'password123', 'user'),
('jane_smith', 'jane@test.com', 'jane456', 'user'),
('test_user', 'test@test.com', 'test', 'user');

-- Sample Posts
INSERT INTO posts (user_id, title, content) VALUES
(1, 'Welcome to Insecure Blog!', 'This is a deliberately vulnerable blog application for educational purposes. It demonstrates various security vulnerabilities including SQL Injection, XSS, IDOR, and more.'),
(2, 'My First Post', 'Hello everyone! This is my first post on this platform. Excited to share my thoughts here.'),
(1, 'Security Best Practices', 'Always sanitize user input, use prepared statements, implement proper access control, and hash passwords. Unfortunately, this application does none of that!'),
(3, 'Test Post with HTML', '<h2>This post contains HTML</h2><p>You can try injecting <strong>scripts</strong> here!</p>');

-- Display confirmation
SELECT 'Database setup complete!' AS Status;
SELECT 'Tables created: users, posts' AS Info;
SELECT CONCAT('Total users: ', COUNT(*)) AS Users FROM users;
SELECT CONCAT('Total posts: ', COUNT(*)) AS Posts FROM posts;

-- ========================================
-- VULNERABILITY NOTES:
-- ========================================
-- 1. No password hashing (bcrypt, argon2, etc.)
-- 2. No email validation or unique constraints
-- 3. No foreign key relationships
-- 4. No character set/collation security
-- 5. No prepared statement usage in PHP code
-- 6. Plain text passwords visible in database
-- 7. No input length validation
-- 8. No XSS prevention in stored content
-- ========================================
