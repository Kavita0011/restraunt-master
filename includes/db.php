
<?php
// Database Connection Function
function connectDatabase() {
    $host = 'localhost';
    $dbname = 'restaurant-master';
    $username = 'root';
    $password = '';

    $connection = mysqli_connect($host, $username, $password, $dbname);

    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    return $connection;
}

// Main Execution
$conn = connectDatabase();
?>
