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
        echo '</div></div>';
    }
} else {
    echo "<p>No menu items found.</p>";
}
include "../includes/footer.php";

?>

</body>
</html>
