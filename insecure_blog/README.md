# 🔓 Insecure Blog - Educational Security Demo

⚠️ **WARNING**: This application is **INTENTIONALLY VULNERABLE** and should **NEVER** be deployed in a production environment or exposed to the internet!

## 📚 Purpose

This PHP web application is designed for educational purposes to demonstrate common web security vulnerabilities including:

- **SQL Injection**
- **Cross-Site Scripting (XSS)** - Both Stored and Reflected
- **Insecure Direct Object Reference (IDOR)**
- **Broken Access Control**
- **Information Leakage**
- **Plain Text Password Storage**
- **No Session Timeout**
- **CSRF Vulnerabilities**

## 📁 Project Structure

```
insecure_blog/
├── index.php           # Home page with blog posts
├── register.php        # User registration (no password hashing)
├── login.php           # Login with SQL injection vulnerability
├── logout.php          # Simple logout
├── dashboard.php       # User dashboard
├── admin.php           # Admin panel (broken access control)
├── profile.php         # User profile (IDOR + Reflected XSS)
├── create_post.php     # Create post (Stored XSS)
├── view_post.php       # View post (Stored XSS execution)
├── db.php              # Database connection (info leakage)
├── database_setup.sql  # Database schema and sample data
└── css/
    └── style.css       # Styling
```

## 🔧 Setup Instructions

### Prerequisites

- XAMPP (or LAMP/WAMP) installed
- Apache and MySQL running

### Step 1: Copy Files

Copy the entire `insecure_blog` folder to your XAMPP htdocs directory:

```
C:\xampp\htdocs\insecure_blog\     (Windows)
/Applications/XAMPP/htdocs/insecure_blog/     (macOS)
```

### Step 2: Create Database

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click on "SQL" tab
3. Copy and paste the contents of `database_setup.sql`
4. Click "Go" to execute

Alternative using MySQL command line:
```bash
mysql -u root -p < database_setup.sql
```

### Step 3: Verify Database Configuration

Open `db.php` and verify credentials:
```php
$host = "localhost";
$dbuser = "root";
$dbpass = "";  // Default XAMPP password is empty
$dbname = "insecure_blog";
```

### Step 4: Access Application

Open browser and navigate to:
```
http://localhost/insecure_blog/
```

## 👤 Test Accounts

| Username | Email | Password | Role |
|----------|-------|----------|------|
| admin | admin@test.com | admin123 | admin |
| john_doe | john@test.com | password123 | user |
| jane_smith | jane@test.com | jane456 | user |
| test_user | test@test.com | test | user |

## 🎯 Vulnerability Demonstrations

### 1️⃣ SQL Injection (Login Page)

**Location**: `login.php`

**How to exploit**:
- Email field: `' OR '1'='1`
- Password field: `' OR '1'='1`
- Click Login → Bypasses authentication!

**Why it works**: Direct string concatenation in SQL query without prepared statements.

### 2️⃣ Stored XSS (Blog Posts)

**Location**: `create_post.php` and `view_post.php`

**How to exploit**:
1. Log in with any account
2. Go to Dashboard → Create New Post
3. In Title or Content, enter: `<script>alert('XSS Attack!')</script>`
4. Submit the post
5. View the post → JavaScript executes!

**Why it works**: No input sanitization and no output escaping with `htmlspecialchars()`.

### 3️⃣ Reflected XSS

**Location**: `profile.php`

**How to exploit**:
- Visit: `profile.php?id=<script>alert('Reflected XSS')</script>`
- The script executes immediately!

**Why it works**: GET parameter displayed directly without sanitization.

### 4️⃣ IDOR (Insecure Direct Object Reference)

**Location**: `profile.php`

**How to exploit**:
1. Log in as any user
2. Visit your profile: `profile.php?id=2`
3. Change ID parameter: `profile.php?id=1`
4. You can now view OTHER users' profiles including their passwords!

**Why it works**: No authorization check to verify profile ownership.

### 5️⃣ Broken Access Control

**Location**: `admin.php`

**How to exploit**:
1. Register as a regular user (role: 'user')
2. After login, navigate to: `admin.php`
3. You have full admin access despite not being an admin!
4. Can view all users and their plain text passwords
5. Can delete any user: `admin.php?delete=3`

**Why it works**: Only checks if user is logged in, doesn't verify role.

### 6️⃣ Information Leakage

**Location**: All files

**What's exposed**:
- Full PHP error messages displayed on screen
- Raw MySQL error messages with query details
- Database structure revealed in errors
- Stack traces visible

**Why it happens**: `error_reporting(E_ALL)` and `display_errors = 1` in `db.php`.

### 7️⃣ No Password Hashing

**Location**: `register.php`, database

**How to see**:
1. Log in as any user
2. Visit: `admin.php`
3. View the "Password (Plain)" column
4. All passwords are stored and displayed in plain text!

**Why it's bad**: No use of `password_hash()` or `password_verify()`.

### 8️⃣ No Session Timeout

**Location**: All authenticated pages

**How to observe**:
1. Log in
2. Close browser
3. Reopen and navigate to `dashboard.php`
4. Still logged in! Session never expires.

**Why it happens**: No timeout logic or session expiration implemented.

## 🛡️ How to Fix These Vulnerabilities

### SQL Injection → Use Prepared Statements
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
```

### XSS → Sanitize Input & Escape Output
```php
// Input
$title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');

// Output
echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8');
```

### IDOR → Implement Authorization
```php
if ($profile_user['id'] != $_SESSION['user_id']) {
    die("Access denied!");
}
```

### Broken Access Control → Check Roles
```php
if ($_SESSION['role'] != 'admin') {
    die("Unauthorized access!");
}
```

### Password Storage → Use Hashing
```php
// Register
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Login
if (password_verify($password, $user['password'])) {
    // Valid
}
```

### Information Leakage → Disable Errors
```php
error_reporting(0);
ini_set('display_errors', 0);
// Log errors instead
```

### Session Timeout → Implement Timeout
```php
$timeout = 1800; // 30 minutes
if (time() - $_SESSION['last_activity'] > $timeout) {
    session_destroy();
}
$_SESSION['last_activity'] = time();
```

## 📖 Learning Objectives

After exploring this application, you should understand:

1. Why prepared statements are essential
2. The difference between stored and reflected XSS
3. Importance of authorization checks (not just authentication)
4. Why passwords must be hashed
5. How information leakage helps attackers
6. The dangers of trusting user input
7. Why proper access control is critical

## ⚠️ Security Disclaimer

This application:
- Must ONLY be used in isolated, local development environments
- Should NEVER be accessible from the internet
- Is for EDUCATIONAL PURPOSES ONLY
- Demonstrates what NOT to do in production code
- Should be deleted after learning exercise is complete

## 📝 Assignment Notes

For Secure Coding Lab Experiment 4:
- Document each vulnerability you find
- Explain how each exploit works
- Propose secure alternatives
- Test all vulnerabilities systematically
- Take screenshots for your report

## 🔗 Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [SQL Injection Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html)
- [XSS Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html)
- [PHP Security Guide](https://www.php.net/manual/en/security.php)

---

**Created for**: Secure Coding Course - Semester 6  
**Experiment**: 4 - Web Application Security Vulnerabilities  
**Date**: February 2026

© Educational purposes only - Do not use in production!
