<?php include('includes/header.php'); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY"></script>

<main>
    <h1>Track Your Order</h1>
    <p>Order Status: <span id="order-status">Loading...</span></p>
    <div id="map" style="width: 100%; height: 400px;"></div>
</main>

<script>
const orderId = 1; // Replace with dynamic order ID
const mapElement = document.getElementById('map');
let map, marker;

// Initialize the map
function initMap(lat, lng) {
    const location = { lat: parseFloat(lat), lng: parseFloat(lng) };
    map = new google.maps.Map(mapElement, {
        zoom: 15,
        center: location,
    });
    marker = new google.maps.Marker({
        position: location,
        map: map,
    });
}

// Fetch order tracking details
async function fetchTrackingInfo() {
    const response = await fetch(`api/order_tracking.php?order_id=${orderId}`);
    const data = await response.json();

    if (data.current_status) {
        document.getElementById('order-status').innerText = data.current_status;

        if (data.current_lat && data.current_lng) {
            const lat = parseFloat(data.current_lat);
            const lng = parseFloat(data.current_lng);

            // Update map marker
            if (!map) {
                initMap(lat, lng);
            } else {
                const location = { lat, lng };
                marker.setPosition(location);
                map.setCenter(location);
            }
        }
    }
}

// Poll tracking info every 10 seconds
setInterval(fetchTrackingInfo, 10000);
fetchTrackingInfo();
</script>
<?php include('includes/footer.php'); ?>
