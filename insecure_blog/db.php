<?php
/*
 * VULNERABILITY: INFORMATION LEAKAGE
 * - Full error reporting enabled
 * - Displays all PHP errors and warnings
 * - Shows raw database connection errors
 * - Exposes sensitive system information
 */

// Enable full error reporting - INSECURE!
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials - hardcoded (bad practice)
$host = "localhost";
$dbuser = "root";
$dbpass = ""; // Default XAMPP password
$dbname = "insecure_blog";

// Create connection - no error handling
$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

// Display raw connection errors - INSECURE!
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "<br>");
    die("Error details: " . mysqli_connect_errno() . "<br>");
}

// No mysqli_real_escape_string usage anywhere
// No prepared statements
?>
