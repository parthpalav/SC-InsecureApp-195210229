<?php
/*
 * VULNERABILITY: NO PASSWORD HASHING
 * - Passwords stored in plain text
 * - No input validation
 * - No SQL injection protection
 * - No XSS protection on username/email
 */

session_start();
require_once 'db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // VULNERABLE: No input sanitization
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'user'; // Default role
    
    // VULNERABLE: SQL Injection possible, plain text password
    $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $success = "Registration successful! You can now login.";
    } else {
        // VULNERABLE: Information leakage - showing raw SQL error
        $error = "Registration failed: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Insecure Blog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="admin.php">Admin</a>
        <a href="logout.php">Logout</a>
    </nav>
    
    <div class="container">
        <h1>Register</h1>
        
        <?php if ($error): ?>
            <!-- VULNERABLE: Displaying raw error messages -->
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
