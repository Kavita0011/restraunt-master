<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $order_id = $_GET['order_id'];

    $sql = "SELECT o.current_status, dp.current_lat, dp.current_lng 
            FROM Orders o
            LEFT JOIN Delivery_Partners dp ON o.delivery_partner_id = dp.delivery_partner_id
            WHERE o.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        echo json_encode($data);
    } else {
        echo json_encode(["status" => "error", "message" => "Order not found"]);
    }
    $stmt->close();
}
?>
