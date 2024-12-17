<form action="api/delivery_update.php" method="POST">
    <h1>Update Location</h1>
    <input type="hidden" name="delivery_partner_id" value="1"> <!-- Replace with dynamic ID -->
    <label for="current_lat">Latitude:</label>
    <input type="text" id="current_lat" name="current_lat" required>
    <label for="current_lng">Longitude:</label>
    <input type="text" id="current_lng" name="current_lng" required>
    <button type="submit">Update</button>
</form>
