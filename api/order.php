<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];

    // Fetch price from menu
    $sql = "SELECT price FROM Menu WHERE menu_id = $menu_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $total_price = $row['price'] * $quantity;

    // Insert order
    $sql = "INSERT INTO Orders (user_id, restaurant_id, items, total_price, delivery_address) VALUES 
            ($user_id, 1, '[{\"menu_id\": $menu_id, \"quantity\": $quantity}]', $total_price, '123 Elm Street')";
    if ($conn->query($sql) === TRUE) {
        echo "Order placed successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
