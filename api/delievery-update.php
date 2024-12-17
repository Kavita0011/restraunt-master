<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delivery_partner_id = $_POST['delivery_partner_id'];
    $current_lat = $_POST['current_lat'];
    $current_lng = $_POST['current_lng'];

    // Update delivery partner's location
    $sql = "UPDATE Delivery_Partners SET current_lat = ?, current_lng = ? WHERE delivery_partner_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddi", $current_lat, $current_lng, $delivery_partner_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
    $stmt->close();
}
?>
