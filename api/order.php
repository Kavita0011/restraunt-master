<?php
include('../includes/db.php');
session_start();

// Check if the user is logged in
if (empty($_SESSION["user_email"])) {
    die("<div class='container'>You are not logged in. Please <a href='../api/user/user-login.php'>log in</a>.</div>");
}

// Check if the form is submitted using POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'], $_POST['quantity'])) {
    $user_id = $_SESSION['user_id'];
    $menu_id = intval($_POST['menu_id']);
    $quantity = intval($_POST['quantity']);

    // Validate quantity
    if ($quantity < 1) {
        die("<div class='container'>Invalid quantity. Please <a href='../menu.php'>try again</a>.</div>");
    }

    // Fetch price from menu using prepared statements
    $stmt = $conn->prepare("SELECT price FROM Menu WHERE menu_id = ?");
    $stmt->bind_param("i", $menu_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("<div class='container'>Invalid menu item. Please <a href='../menu.php'>try again</a>.</div>");
    }

    $row = $result->fetch_assoc();
    $price = $row['price'];
    $total_price = $price * $quantity;

    // Prepare items as a JSON string
    $items = json_encode([["menu_id" => $menu_id, "quantity" => $quantity]]);

    // Insert order into the database
    $delivery_address = '123 Elm Street'; // Placeholder; replace with dynamic data if available
    $stmt = $conn->prepare("INSERT INTO Orders (user_id, restaurant_id, items, total_price, delivery_address) 
                            VALUES (?, ?, ?, ?, ?)");
    $restaurant_id = 1; // Assuming a single restaurant ID
    $stmt->bind_param("iisds", $user_id, $restaurant_id, $items, $total_price, $delivery_address);

    if ($stmt->execute()) {
        echo "<div class='container'>Order placed successfully! <a href='../view/menu.php'>Back to menu</a></div>";
    } else {
        echo "<div class='container'>Error placing order: " . htmlspecialchars($stmt->error) . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='container'>Invalid request. Please <a href='../menu.php'>try again</a>.</div>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
</head>
<body>
    <h1>Order Details</h1>
</body>
</html>
