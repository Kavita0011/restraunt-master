<?php
// food_delivery_system.php

/**
 * A simple PHP-based food delivery system using MySQL and reusable functions in core PHP.
 * The system includes Admin, Users, Restaurants, Delivery Partners, and a tracking system.
 */

// Function to fetch records using prepared statements
function fetchRecords($connection, $table, $columns = "*", $conditions = []) {
    $sql = "SELECT $columns FROM $table";
    $params = [];

    if (!empty($conditions)) {
        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = ?";
            $params[] = $value;
        }
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    $stmt = $connection->prepare($sql);
    if ($params) {
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $data;
}

// Function to insert a record using prepared statements
function insertRecord($connection, $table, $data) {
    $columns = implode(", ", array_keys($data));
    $placeholders = implode(", ", array_fill(0, count($data), "?"));

    $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    $stmt = $connection->prepare($sql);

    $types = str_repeat("s", count($data));
    $values = array_values($data);
    $stmt->bind_param($types, ...$values);

    $success = $stmt->execute();
    $stmt->close();

    return $success;
}

// Function to update a record using prepared statements
function updateRecord($connection, $table, $data, $conditions) {
    $setClauses = [];
    $params = [];

    foreach ($data as $column => $value) {
        $setClauses[] = "$column = ?";
        $params[] = $value;
    }

    $whereClauses = [];
    foreach ($conditions as $column => $value) {
        $whereClauses[] = "$column = ?";
        $params[] = $value;
    }

    $sql = "UPDATE $table SET " . implode(", ", $setClauses) . " WHERE " . implode(" AND ", $whereClauses);
    $stmt = $connection->prepare($sql);

    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);

    $success = $stmt->execute();
    $stmt->close();

    return $success;
}

// Example: Fetching orders for a user
function getUserOrders($connection, $userId) {
    return fetchRecords($connection, 'orders', '*', ['user_id' => $userId]);
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
    $orders = fetchRecords($connection, 'orders', '*', ['id' => $orderId]);
    return !empty($orders) ? $orders[0] : null;
}

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
