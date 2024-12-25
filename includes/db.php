<?php
// Database Connection Function
function connectDatabase() {
    $host = 'localhost';
    $dbname = 'restaurant-master';
    $username = 'root';
    $password = '';

    // Create a connection using MySQLi
    $connection = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($connection->connect_error) {
        die("Database connection failed: " . $connection->connect_error);
    }

    return $connection;
}

// Main Execution
$conn = connectDatabase();
?>
