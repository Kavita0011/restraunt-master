<?php
include 'db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getLocations':
        $deliveryId = $_GET['delivery_id'];
        $locations = fetchRecords($conn, 'delivery_locations', ['delivery_id' => $deliveryId]);
        echo json_encode(['success' => true, 'data' => $locations]);
        break;

    case 'updateLocation':
        $deliveryId = $_POST['delivery_id'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $success = insertRecord($conn, 'delivery_locations', [
            'delivery_id' => $deliveryId,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);
        echo json_encode(['success' => $success]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
