<?php
/*
 * VULNERABILITY: STORED XSS
 * - No input sanitization
 * - No output escaping
 * - Allows JavaScript injection in title and content
 * - SQL Injection possible
 * 
 * EXPLOIT: Enter <script>alert('XSS')</script> in title or content
 */

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // VULNERABLE: No input sanitization or validation
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    
    // VULNERABLE: SQL Injection + Stored XSS
    $query = "INSERT INTO posts (user_id, title, content) VALUES ($user_id, '$title', '$content')";
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $success = "Post created successfully!";
    } else {
        // VULNERABLE: Information leakage
        $error = "Error creating post: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Post - Insecure Blog</title>
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
        <h1>Create New Post</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?> <a href="dashboard.php">Go to Dashboard</a></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" required>
                <small>Try: &lt;script&gt;alert('XSS in title')&lt;/script&gt;</small>
            </div>
            
            <div class="form-group">
                <label>Content:</label>
                <textarea name="content" rows="10" required></textarea>
                <small>Try: &lt;script&gt;alert('Stored XSS!')&lt;/script&gt;</small>
            </div>
            
            <button type="submit">Create Post</button>
        </form>
    </div>
</body>
</html>
