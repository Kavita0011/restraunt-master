<?php
include("/xampp/htdocs/restraunt/includes/db.php");

// Fetch orders
$sql = "SELECT * FROM order_trackings ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-4">Order Tracking System</h1>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['product_name']) ?></h5>
                                <p class="card-text"><strong>Order ID:</strong> <?= htmlspecialchars($row['order_id']) ?></p>
                                <p class="card-text"><strong>Status:</strong> <?= htmlspecialchars($row['order_status']) ?></p>
                                <p class="card-text"><strong>Tracking Info:</strong> <?= htmlspecialchars($row['tracking_info']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No orders found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
