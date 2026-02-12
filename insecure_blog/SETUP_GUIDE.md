# 🚀 Quick Setup Guide

## Copy to XAMPP

1. Copy the entire `insecure_blog` folder to:
   - **Windows**: `C:\xampp\htdocs\insecure_blog\`
   - **macOS**: `/Applications/XAMPP/htdocs/insecure_blog/`
   - **Linux**: `/opt/lampp/htdocs/insecure_blog/`

## Setup Database

### Method 1: Using phpMyAdmin (Easiest)
1. Start Apache and MySQL in XAMPP
2. Open: `http://localhost/phpmyadmin`
3. Click "SQL" tab
4. Open `database_setup.sql` in a text editor
5. Copy all content and paste into SQL tab
6. Click "Go"

### Method 2: Using MySQL Command Line
```bash
mysql -u root -p < database_setup.sql
```
(Press Enter when asked for password - default is empty)

## Verify Setup

1. Open browser
2. Go to: `http://localhost/insecure_blog/`
3. You should see the home page

## Test Login

Use any of these accounts:
- **Email**: `admin@test.com` | **Password**: `admin123`
- **Email**: `john@test.com` | **Password**: `password123`

## Try SQL Injection

On login page:
- **Email**: `' OR '1'='1`
- **Password**: `' OR '1'='1`
- Click Login → You're in without valid credentials!

## 🎯 Quick Vulnerability Tests

### ✅ SQL Injection
- Login page with: `' OR '1'='1`

### ✅ Stored XSS
- Create post with: `<script>alert('XSS')</script>`

### ✅ Reflected XSS
- Visit: `profile.php?id=<script>alert('XSS')</script>`

### ✅ IDOR
- Change URL: `profile.php?id=1` to `profile.php?id=2`

### ✅ Broken Access Control
- Login as regular user, visit: `admin.php`

## ❗ Troubleshooting

**Can't connect to database?**
- Make sure MySQL is running in XAMPP
- Check credentials in `db.php` (default: root with no password)

**404 Not Found?**
- Verify folder is in `htdocs`
- Check URL: `http://localhost/insecure_blog/` (not `http://localhost/`)

**CSS not loading?**
- Check that `css/style.css` exists
- Verify file permissions

**No data showing?**
- Make sure you ran `database_setup.sql`
- Check database exists: `insecure_blog`
- Verify tables: `users` and `posts`

## 📝 For Your Lab Report

Document:
1. Each vulnerability found
2. Steps to reproduce
3. Impact of the vulnerability
4. How to fix it properly
5. Screenshots of exploits

---

⚠️ **Remember**: This is for EDUCATION ONLY! Never deploy vulnerable code!
