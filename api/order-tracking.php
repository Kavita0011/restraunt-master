<?php
session_start();

// Include the database connection and helper functions
include("/xampp/htdocs/restraunt/includes/db.php"); // Database connection
include("/xampp/htdocs/restraunt/includes/functions.php"); // FetchRecords and other helper functions

// Check if the user is logged in
if (empty($_SESSION["user_email"])) {
    die("You are not logged in. Please <a href='user-login.php'>log in</a>.");
}

// Function to generate a random OTP
function generateOTP($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

// Fetch user and order details
$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? null;

// Validate order ID
if (!filter_var($order_id, FILTER_VALIDATE_INT)) {
    die("Invalid order ID.");
}

// Fetch the order and delivery details securely
$sql = "
    SELECT 
        o.order_id,
        o.current_status,
        o.total_price,
        o.delivery_address,
        o.delivery_lat,
        o.delivery_lng,
        dp.name AS delivery_partner_name,
        dp.phone AS delivery_partner_phone
    FROM 
        orders o
    LEFT JOIN 
        delivery_partners dp 
    ON 
        o.delivery_partner_id = dp.delivery_partner_id
    WHERE 
        o.order_id = ? AND o.user_id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("ii", $order_id, $user_id); // Bind parameters for order ID and user ID
$stmt->execute();
$result = $stmt->get_result();
$order_details = $result->fetch_assoc();

if (empty($order_details)) {
    die("Order not found or unauthorized access.");
}

// Generate OTP for order confirmation if not already generated
if ($order_details['current_status'] === 'Out for Delivery') {
    $otp = generateOTP();
    $otp_sql = "UPDATE orders SET otp = ? WHERE order_id = ?";
    $otp_stmt = $conn->prepare($otp_sql);
    if (!$otp_stmt) {
        die("Error preparing OTP statement: " . $conn->error);
    }
    $otp_stmt->bind_param("ii", $otp, $order_id);
    $otp_stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order</title>
    <link rel="stylesheet" href="/path/to/styles.css">
</head>
<body>
    <div class="container">
        <h1>Track Your Order</h1>
        <?php if ($order_details): ?>
            <div class="order-tracking">
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_details['order_id']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order_details['current_status']); ?></p>
                <p><strong>Delivery Partner:</strong> <?php echo htmlspecialchars($order_details['delivery_partner_name']); ?></p>
                <p><strong>Contact:</strong> <?php echo htmlspecialchars($order_details['delivery_partner_phone']); ?></p>
                <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order_details['delivery_address']); ?></p>
            </div>

            <?php if ($order_details['current_status'] === 'Out for Delivery'): ?>
                <h3>Confirm Order Delivery</h3>
                <form action="confirm_delivery.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_details['order_id']); ?>">
                    <label for="otp">Enter OTP:</label>
                    <input type="text" id="otp" name="otp" required>
                    <button type="submit">Confirm Delivery</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <p>Order not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
