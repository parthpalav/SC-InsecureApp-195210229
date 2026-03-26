<?php



error_reporting(E_ALL);
ini_set('display_errors', 1);


$host = "localhost";
$dbuser = "root";
$dbpass = ""; 
$dbname = "insecure_blog";


$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error() . "<br>");
    die("Error details: " . mysqli_connect_errno() . "<br>");
}



?>
