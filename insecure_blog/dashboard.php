<?php
/*
 * VULNERABILITY: WEAK ACCESS CONTROL
 * - Only checks if session exists
 * - No timeout validation
 * - No session regeneration
 */

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

// VULNERABLE: SQL Injection possible
$user_id = $_SESSION['user_id'];
$query = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.user_id = $user_id ORDER BY posts.id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    // VULNERABLE: Information leakage
    echo "Error: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Insecure Blog</title>
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
        <h1>Dashboard</h1>
        <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
        <p>Role: <?php echo $_SESSION['role']; ?></p>
        
        <div class="actions">
            <a href="create_post.php" class="btn">Create New Post</a>
            <a href="profile.php?id=<?php echo $_SESSION['user_id']; ?>" class="btn">View Profile</a>
        </div>
        
        <h2>Your Posts</h2>
        
        <div class="posts">
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($post = mysqli_fetch_assoc($result)): ?>
                    <div class="post-card">
                        <!-- VULNERABLE: No output escaping -->
                        <h3><?php echo $post['title']; ?></h3>
                        <p><?php echo substr($post['content'], 0, 100); ?>...</p>
                        <a href="view_post.php?id=<?php echo $post['id']; ?>">View</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>You haven't created any posts yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
