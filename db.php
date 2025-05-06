<?php
// $servername = "localhost";  // Replace with your server name
// $username = "root";         // Replace with your database username
// $password = "";             // Replace with your database password
// $dbname = "jyotidham";  // Replace with your database name

$servername = "localhost";  // Replace with your server name
$username = "gozoomte_jyotidham";         // Replace with your database username
$password = "Gozoom@123";             // Replace with your database password
$dbname = "gozoomte_jyotidham";  // Replace with your database name
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
