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
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/menu.css">
    <script>
        // JavaScript to handle quantity changes
        function updateQuantity(id, change) {
            const quantityInput = document.getElementById('quantity_' + id);
            let currentValue = parseInt(quantityInput.value);
            currentValue += change;

            if (currentValue < 1) {
                currentValue = 1; // Prevent negative or zero quantity
            }

            quantityInput.value = currentValue;
        }
    </script>
</head>
<body>
<div class="menu-container">
    <?php
    include "../includes/header.php";

    // Check if there are any menu items and display them
    if (count($result) > 0) {
        foreach ($result as $item) { ?>
            <div class="container">
                <div class="menu-card">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                         alt="<?= htmlspecialchars($item['item_name']) ?>" />
                    <h3><?= htmlspecialchars($item['item_name']) ?></h3>
                    <p><?= htmlspecialchars($item['description']) ?></p>
                    <p class="price">$<?= number_format($item['price'], 2) ?></p>

                    <!-- Quantity Controls -->
                    <div class="quantity-controls">
                        <button type="button" onclick="updateQuantity(<?= $item['menu_id'] ?>, -1)">-</button>
                        <input type="number" id="quantity_<?= $item['menu_id'] ?>" name="count" value="1" readonly>
                        <button type="button" onclick="updateQuantity(<?= $item['menu_id'] ?>, 1)">+</button>
                    </div>

                    <!-- Order Form -->
                    <form action="../api/order.php" method="POST">
                        <input type="hidden" name="menu_id" value="<?= htmlspecialchars($item['menu_id']) ?>" />
                        <input type="hidden" name="quantity" id="hiddenQuantity_<?= $item['menu_id'] ?>" value="1" />
                        <input type="hidden" name="user_id" value="<?=  $_SESSION['user_id']; ?>" />
                        <button class="btn" type="submit" 
                                onclick="document.getElementById('hiddenQuantity_<?= $item['menu_id'] ?>').value = document.getElementById('quantity_<?= $item['menu_id'] ?>').value;">
                            Order Now
                        </button>
                    </form>
                </div>
            </div>
        <?php }
    } else {
        echo "<p>No menu items found.</p>";
    }
    include "../includes/footer.php";
    ?>
</div>
</body>
</html>
