<?php 
include('../includes/header.php'); 
include('../includes/db.php'); 

// Fetch menu items
$sql = "SELECT * FROM Menu WHERE is_available = 1";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    
<main>
    <section class="menu">
        <h1>Our Menu</h1>
        <div class="menu-items">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="menu-item">
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['item_name']; ?>">
                    <h2><?php echo $row['item_name']; ?></h2>
                    <p><?php echo $row['description']; ?></p>
                    <p><strong>Price: $<?php echo $row['price']; ?></strong></p>
                    <button class="btn order-btn" data-id="<?php echo $row['menu_id']; ?>">Order Now</button>
                </div>
            <?php } ?>
        </div>
    </section>
</main>
</body>
</html>
<?php include('includes/footer.php'); ?>
