<?php

// Fetch user's orders securely
$user_id = $_SESSION['user_id'];

try {
    // Prepare the SQL statement
    $sql = "SELECT order_id, items, total_price, delivery_address, created_at 
            FROM Orders WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Failed to prepare the statement: " . $conn->error);
    }

    // Bind the parameter and execute the query
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if there are any orders
    if ($result->num_rows > 0) {
        echo "<div class='container'>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Items</th>
                            <th>Total Price</th>
                            <th>Delivery Address</th>
                            <th>Order Date</th>
                            <th>Track Order </th>
                        </tr>
                    </thead>
                    <tbody>";

        while ($row = $result->fetch_assoc()) {
            $items = json_decode($row['items'], true); // Decode JSON items
            echo "<tr>
                    <td>" . htmlspecialchars($row['order_id']) . "</td>
                    <td>";
            // Display each item in the order
            foreach ($items as $item) {
                echo "Menu ID: " . htmlspecialchars($item['menu_id']) . ", Quantity: " . htmlspecialchars($item['quantity']) . "<br>";
            }
            echo "</td>
                    <td>$" . number_format($row['total_price'], 2) . "</td>
                    <td>" . htmlspecialchars($row['delivery_address']) . "</td>
                    <td>" . htmlspecialchars(date('Y-m-d H:i', strtotime($row['created_at']))) . "</td>
                    <td><a href=../order-tracking.php?order_id=" . number_format($row['order_id']) . ">Track your order</a></td>
                </tr>";
        }

        echo "</tbody>
              </table>
              </div>";
    } else {
        echo "<div class='container'><p>You have no orders yet. <a href='../menu.php'>Browse the menu</a> to place your first order.</p></div>";
    }

} catch (Exception $e) {
    echo "<div class='container'><p>Error fetching orders: " . htmlspecialchars($e->getMessage()) . "</p></div>";
} finally {
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
</body>
</html>
