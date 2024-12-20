<?php
include 'db.php';
$deliveryId = $_GET['delivery_id'];
$deliveryDetails = fetchRecords($conn, 'deliveries', ['id' => $deliveryId]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Tracking</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>
</head>
<body>
    <h1>Delivery Tracking</h1>
    <div id="map" style="height: 500px;"></div>

    <script>
        async function fetchLocations() {
            const response = await fetch(`api.php?action=getLocations&delivery_id=<?php echo $deliveryId; ?>`);
            const data = await response.json();
            return data.data;
        }

        async function initMap() {
            const locations = await fetchLocations();
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: { lat: parseFloat(locations[0].latitude), lng: parseFloat(locations[0].longitude) }
            });

            locations.forEach(location => {
                new google.maps.Marker({
                    position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) },
                    map: map
                });
            });
        }

        initMap();
    </script>
</body>
</html>
