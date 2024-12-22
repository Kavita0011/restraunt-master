<?php
// menu.php
include '../includes/db.php';
include '../includes/functions.php';
// Fetch records as an associative array
$result = fetchRecords($conn, 'menu', '*');
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="../assets/css/menu.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script>
        // JavaScript to handle the increment and decrement of quantity
        function updateQuantity(increment) {
            let quantity = parseInt(document.getElementById("quantity").innerText);
            if (increment) {
                quantity++;
            } else {
                if (quantity > 1) {
                    quantity--;
                }
            }
            document.getElementById("quantity").innerText = quantity;
        }
    </script>
</head>
<body>

<?php
include "../includes/header.php";
// Check if there are any menu items and display them
if (count($result) > 0) {
    foreach ($result as $item) {
        echo '<div class="container"><div class="menu-card">';
        echo '<img src="' . $item['image_url'] . '" alt="' . $item['item_name'] . '">';
        echo '<h3>' . $item['item_name'] . '</h3>';
        echo '<p>' . $item['description'] . '</p>';
        echo '<p class="price">$' . number_format($item['price'], 2) . '</p>';
        ?>
        <form action="../api/order.php" method="POST">
            <div class="quantity-container">
                <button type="button" class="btn" onclick="updateQuantity(false)"> - </button>
                <span id="quantity" class="quantity"><?php echo 1; ?></span>
                <button type="button" class="btn" onclick="updateQuantity(true)"> + </button>
            </div>
            <input type="hidden" name="menu_id" value="<?php echo $item['menu_id']; ?>" />
            <input type="hidden" name="quantity" id="hiddenQuantity" value="1" />
            <button class="btn" type="submit" onclick="document.getElementById('hiddenQuantity').value = document.getElementById('quantity').innerText;">Buy Now</button>
        </form>
        </div></div>
        <?php
    }
} else {
    echo "<p>No menu items found.</p>";
}
include "../includes/footer.php";
?>

</body>
</html>
