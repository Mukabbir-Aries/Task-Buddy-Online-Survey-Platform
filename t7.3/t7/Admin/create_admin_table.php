<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'task_buddy_db';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Drop table if exists
$conn->query("DROP TABLE IF EXISTS admins");

// Create admins table with hashed password field
$sql = "CREATE TABLE admins (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    admin_name VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table 'admins' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert admin user with hashed password
$admin_name = 'A';
$admin_password_plain = '5';
$admin_password_hashed = password_hash($admin_password_plain, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admins (admin_name, password) VALUES (?, ?)");
$stmt->bind_param("ss", $admin_name, $admin_password_hashed);

if ($stmt->execute()) {
    echo "Admin user inserted successfully.<br>";
} else {
    echo "Error inserting admin user: " . $stmt->error . "<br>";
}

$stmt->close();
$conn->close();
?>
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create admins table if it doesn't exist
$sql_admins = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_name VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_admins) === TRUE) {
    echo "Table admins created successfully<br>";
} else {
    echo "Error creating admins table: " . $conn->error . "<br>";
}

$conn->close();
?>
