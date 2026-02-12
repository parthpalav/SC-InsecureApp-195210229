<?php
/*
 * VULNERABILITY: SQL INJECTION
 * - Direct string concatenation in SQL query
 * - No prepared statements
 * - No input validation
 * - Raw error messages displayed
 * 
 * EXPLOIT: Use ' OR '1'='1 in email or password field to bypass login
 * Example: email: admin@test.com' OR '1'='1' --
 *          password: anything
 */

session_start();
require_once 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // VULNERABLE: No sanitization whatsoever
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // VULNERABLE: SQL Injection - direct string concatenation
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        // VULNERABLE: Information leakage - showing raw SQL error
        $error = "Query failed: " . mysqli_error($conn);
    } else {
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // VULNERABLE: No session timeout implemented
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Insecure Blog</title>
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
        <h1>Login</h1>
        
        <?php if ($error): ?>
            <!-- VULNERABLE: Direct echo without escaping -->
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Email:</label>
                <input type="text" name="email" required>
                <small>Try: ' OR '1'='1</small>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
                <small>Try: ' OR '1'='1</small>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
