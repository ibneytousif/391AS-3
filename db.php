<?php
$servername = "localhost";
$username = "root";  // Your MySQL username (default is usually "root" for XAMPP)
$password = "";      // The password for your MySQL username (default is empty for root)
$dbname = "carworkshop"; // Make sure this is the name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
