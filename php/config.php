<?php
// config.php
// session_start(); // Ensure this is only called here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BMI_PHP_APP";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
