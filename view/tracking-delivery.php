<?php

// Ensure database connection is open
if (!$conn->ping()) {
    // Reconnect if the connection is closed
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

// Fetch delivery partner details
$deliveryId = 1; // You can modify this dynamically as needed
$deliveryDetails = fetchRecords($conn, 'delivery_partners', "*", ['delivery_partner_id' => $deliveryId]);

// Fetch NASA APOD (Astronomy Picture of the Day) data
$apiKey = 'YOUR_NASA_API_KEY'; // Replace with your actual NASA API key
$nasaApiUrl = "https://api.nasa.gov/planetary/apod?api_key=$apiKey";
$nasaResponse = file_get_contents($nasaApiUrl);
$nasaData = json_decode($nasaResponse, true);

$apodImageUrl = $nasaData['url']; // Image URL from NASA API
$apodTitle = $nasaData['title']; // Title of the image
$apodDescription = $nasaData['explanation']; // Description of the image


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Tracking</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script> <!-- Replace with your Google Maps API key -->
</head>
<body>
    <h1>Delivery Tracking</h1>

    <!-- NASA APOD Section -->
    <section>
        <h2>Astronomy Picture of the Day</h2>
        <h3><?php echo htmlspecialchars($apodTitle); ?></h3>
        <img src="<?php echo htmlspecialchars($apodImageUrl); ?>" alt="Astronomy Picture of the Day" style="width: 100%; height: auto;">
        <p><?php echo htmlspecialchars($apodDescription); ?></p>
    </section>

    <!-- Google Map Section -->
    <section>
        <h2>Delivery Location on Map</h2>
        <div id="map" style="height: 500px;"></div>
    </section>

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
