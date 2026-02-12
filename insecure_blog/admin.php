<?php
/*
 * VULNERABILITY: BROKEN ACCESS CONTROL
 * - Only checks if user is logged in
 * - Does NOT check if user has admin role
 * - Any logged-in user can access admin panel
 * - SQL Injection in delete functionality
 * - CSRF vulnerability (delete via GET)
 * 
 * EXPLOIT: Any user can access this page and delete users
 * Try: admin.php?delete=2 to delete user with ID 2
 */

session_start();

// VULNERABLE: Only checks if logged in, NOT if user is admin!
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// MISSING: Role check should be here!
// Should have: if ($_SESSION['role'] != 'admin') { die("Access denied!"); }

require_once 'db.php';

$message = "";

// VULNERABLE: SQL Injection + CSRF (delete via GET)
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
    // VULNERABLE: SQL Injection - no validation or escaping
    $delete_query = "DELETE FROM users WHERE id = $delete_id";
    
    if (mysqli_query($conn, $delete_query)) {
        $message = "User deleted successfully!";
    } else {
        // VULNERABLE: Information leakage
        $message = "Error deleting user: " . mysqli_error($conn);
    }
}

// VULNERABLE: SQL Injection possible
$query = "SELECT * FROM users ORDER BY id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Insecure Blog</title>
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
        <h1>🔓 Admin Panel (Insecure!)</h1>
        <p>Current User: <?php echo $_SESSION['username']; ?> (Role: <?php echo $_SESSION['role']; ?>)</p>
        
        <div class="warning">
            <strong>⚠️ VULNERABILITY:</strong> This page does NOT check user role! 
            Any logged-in user can access this admin panel.
        </div>
        
        <?php if ($message): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <h2>All Users</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password (Plain)</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <!-- VULNERABLE: Displaying plain text passwords -->
                    <td><?php echo $user['password']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <!-- VULNERABLE: Delete via GET (CSRF) + SQL Injection -->
                        <a href="admin.php?delete=<?php echo $user['id']; ?>" 
                           onclick="return confirm('Delete this user?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <footer>
        <p>⚠️ VULNERABILITIES: Broken Access Control, CSRF, SQL Injection, Plain Text Passwords Visible</p>
    </footer>
</body>
</html>
