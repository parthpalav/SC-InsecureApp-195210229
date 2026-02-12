<?php
/*
 * INSECURE BLOG - Home Page
 * This application intentionally contains multiple security vulnerabilities
 * for educational demonstration purposes.
 */

session_start();
require_once 'db.php';

// VULNERABLE: SQL Injection possible in query
$query = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    // VULNERABLE: Information leakage
    echo "Error: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home - Insecure Blog</title>
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
        <h1>🔓 Insecure Blog - Educational Demo</h1>
        
        <?php if (isset($_SESSION['username'])): ?>
            <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
        <?php else: ?>
            <p>Please <a href="login.php">login</a> or <a href="register.php">register</a> to post.</p>
        <?php endif; ?>
        
        <h2>Recent Posts</h2>
        
        <div class="posts">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($post = mysqli_fetch_assoc($result)): ?>
                    <div class="post-card">
                        <!-- VULNERABLE: No output escaping -->
                        <h3><?php echo $post['title']; ?></h3>
                        <p class="author">By: <?php echo $post['username']; ?></p>
                        <a href="view_post.php?id=<?php echo $post['id']; ?>">Read More</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No posts yet. Be the first to post!</p>
            <?php endif; ?>
        </div>
    </div>
    
    <footer>
        <p>⚠️ WARNING: This application is intentionally insecure for educational purposes only!</p>
        <p>Vulnerabilities: SQL Injection, XSS, IDOR, Broken Access Control, Information Leakage</p>
    </footer>
</body>
</html>
