<?php
/*
 * VULNERABILITY: STORED XSS + SQL INJECTION
 * - No output escaping with htmlspecialchars()
 * - Direct echo of user-generated content
 * - SQL Injection in query parameter
 * - Executes any JavaScript from database
 */

session_start();
require_once 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// VULNERABLE: SQL Injection via GET parameter
$post_id = $_GET['id'];
$query = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = $post_id";

$result = mysqli_query($conn, $query);

if (!$result) {
    // VULNERABLE: Information leakage
    die("Error: " . mysqli_error($conn));
}

$post = mysqli_fetch_assoc($result);

if (!$post) {
    die("Post not found!");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $post['title']; ?> - Insecure Blog</title>
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
        <div class="post-full">
            <!-- VULNERABLE: Stored XSS - Direct output without escaping -->
            <h1><?php echo $post['title']; ?></h1>
            
            <p class="meta">
                Posted by: <?php echo $post['username']; ?> | 
                Post ID: <?php echo $post['id']; ?>
            </p>
            
            <div class="post-content">
                <!-- VULNERABLE: This will execute any JavaScript stored in database -->
                <?php echo $post['content']; ?>
            </div>
            
            <p><a href="index.php">← Back to Home</a></p>
        </div>
    </div>
    
    <footer>
        <p>⚠️ This page is vulnerable to Stored XSS! Any JavaScript in the post content will execute.</p>
    </footer>
</body>
</html>
