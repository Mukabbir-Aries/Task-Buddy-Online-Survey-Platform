<?php
$host = 'localhost'; // Change if your database server is different
$username = 'root'; // Replace with your MySQL username
$password = ''; // Default XAMPP password is empty
$dbname = 'task_buddy_db'; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to ensure proper encoding
$conn->set_charset("utf8mb4");

// Return the connection
return $conn;
?>
