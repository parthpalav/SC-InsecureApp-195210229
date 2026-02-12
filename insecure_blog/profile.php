<?php
/*
 * VULNERABILITY: IDOR + REFLECTED XSS
 * - Insecure Direct Object Reference
 * - No ownership check
 * - Any user can view any profile
 * - Reflected XSS via GET parameter
 * - SQL Injection in query
 * 
 * EXPLOIT IDOR: Change ?id=1 to ?id=2 to view other users' profiles
 * EXPLOIT XSS: Try profile.php?id=<script>alert('XSS')</script>
 */

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

// VULNERABLE: Reflected XSS - displaying GET parameter directly
$requested_id = $_GET['id'];

// VULNERABLE: SQL Injection - no validation
$query = "SELECT * FROM users WHERE id = $requested_id";

$result = mysqli_query($conn, $query);

if (!$result) {
    // VULNERABLE: Information leakage
    die("Error: " . mysqli_error($conn));
}

$profile_user = mysqli_fetch_assoc($result);

if (!$profile_user) {
    die("User not found!");
}

// VULNERABLE: No ownership check!
// MISSING: if ($profile_user['id'] != $_SESSION['user_id']) { die("Access denied!"); }

// Get user's posts
$posts_query = "SELECT * FROM posts WHERE user_id = $requested_id ORDER BY id DESC";
$posts_result = mysqli_query($conn, $posts_query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile - Insecure Blog</title>
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
        <h1>User Profile</h1>
        
        <div class="warning">
            <strong>⚠️ IDOR VULNERABILITY:</strong> No authorization check! 
            You can view anyone's profile by changing the ID parameter.
        </div>
        
        <div class="profile-info">
            <!-- VULNERABLE: Reflected XSS - Direct output of GET parameter -->
            <p><strong>Viewing profile of user ID:</strong> <?php echo $_GET['id']; ?></p>
            
            <p><strong>Username:</strong> <?php echo $profile_user['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $profile_user['email']; ?></p>
            <!-- VULNERABLE: Displaying plain text password -->
            <p><strong>Password (Plain):</strong> <?php echo $profile_user['password']; ?></p>
            <p><strong>Role:</strong> <?php echo $profile_user['role']; ?></p>
        </div>
        
        <h2>Posts by <?php echo $profile_user['username']; ?></h2>
        
        <div class="posts">
            <?php if ($posts_result && mysqli_num_rows($posts_result) > 0): ?>
                <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>
                    <div class="post-card">
                        <h3><?php echo $post['title']; ?></h3>
                        <a href="view_post.php?id=<?php echo $post['id']; ?>">Read More</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No posts yet.</p>
            <?php endif; ?>
        </div>
        
        <div class="test-links">
            <h3>Test IDOR:</h3>
            <p>Try viewing other profiles:</p>
            <a href="profile.php?id=1">Profile ID 1</a> | 
            <a href="profile.php?id=2">Profile ID 2</a> | 
            <a href="profile.php?id=3">Profile ID 3</a>
            
            <h3>Test Reflected XSS:</h3>
            <p>Try: <code>profile.php?id=&lt;script&gt;alert('XSS')&lt;/script&gt;</code></p>
        </div>
    </div>
    
    <footer>
        <p>⚠️ VULNERABILITIES: IDOR (can view any profile), Reflected XSS, SQL Injection, Plain Text Password Exposure</p>
    </footer>
</body>
</html>
