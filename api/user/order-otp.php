<?php
session_start();
include("/path/to/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $otp = $_POST['otp'];

    // Verify the OTP and update order status
    $stmt = $conn->prepare("SELECT otp FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result && $result['otp'] == $otp) {
        // OTP is valid, mark order as delivered
        $stmt = $conn->prepare("UPDATE orders SET status = 'Delivered', otp = NULL WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        if ($stmt->execute()) {
            echo "Order confirmed as delivered!";
        } else {
            echo "Error updating order status.";
        }
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>
