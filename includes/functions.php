<?php
// food_delivery_system.php

/**
 * A simple PHP-based food delivery system using MySQL and reusable functions in core PHP.
 * The system includes Admin, Users, Restaurants, Delivery Partners, and a tracking system.
 */

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

// Function to fetch records
function fetchRecords($connection, $table, $conditions = []) {
    $sql = "SELECT * FROM $table";

    if (!empty($conditions)) {
        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = '" . mysqli_real_escape_string($connection, $value) . "'";
        }
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    $result = mysqli_query($connection, $sql);
    $data = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }
    return $data;
}

// Function to insert a record
function insertRecord($connection, $table, $data) {
    $columns = implode(", ", array_keys($data));
    $values = implode(", ", array_map(function($value) use ($connection) {
        return "'" . mysqli_real_escape_string($connection, $value) . "'";
    }, array_values($data)));

    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    return mysqli_query($connection, $sql);
}

// Function to update a record
function updateRecord($connection, $table, $data, $conditions) {
    $setClauses = [];
    foreach ($data as $column => $value) {
        $setClauses[] = "$column = '" . mysqli_real_escape_string($connection, $value) . "'";
    }

    $whereClauses = [];
    foreach ($conditions as $column => $value) {
        $whereClauses[] = "$column = '" . mysqli_real_escape_string($connection, $value) . "'";
    }

    $sql = "UPDATE $table SET " . implode(", ", $setClauses) . " WHERE " . implode(" AND ", $whereClauses);
    return mysqli_query($connection, $sql);
}

// Example: Fetching orders for a user
function getUserOrders($connection, $userId) {
    return fetchRecords($connection, 'orders', ['user_id' => $userId]);
}

// Example: Placing a new order
function placeOrder($connection, $userId, $restaurantId, $items) {
    $orderData = [
        'user_id' => $userId,
        'restaurant_id' => $restaurantId,
        'items' => json_encode($items),
        'status' => 'Pending',
        'created_at' => date('Y-m-d H:i:s')
    ];
    return insertRecord($connection, 'orders', $orderData);
}

// Example: Updating order status
function updateOrderStatus($connection, $orderId, $status) {
    return updateRecord($connection, 'orders', ['status' => $status], ['id' => $orderId]);
}

// Example: Tracking an order
function trackOrder($connection, $orderId) {
    $orders = fetchRecords($connection, 'orders', ['id' => $orderId]);
    return !empty($orders) ? $orders[0] : null;
}

// Main Execution
$connection = connectDatabase();

// Example usage:
// Place a new order
// $orderId = placeOrder($connection, 1, 1, ['Burger', 'Fries']);
// echo $orderId ? "Order placed successfully!" : "Failed to place order.";

// Fetch user orders
// $orders = getUserOrders($connection, 1);
// print_r($orders);

// Track an order
// $order = trackOrder($connection, 1);
// print_r($order);

?>
