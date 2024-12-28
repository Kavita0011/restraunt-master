<?php
// Include database connection
require_once '../../includes/db.php'; // Ensure this file creates a valid $conn object

// Function to generate a random OTP
function generateOTP($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

// Start session and fetch user and order details
$user_id = $_SESSION['user_id'] ?? null;
$order_id = $_GET['order_id'] ?? null;

// Validate user session and order ID
if (!$user_id) {
    die("<div class='error-message'>You must be logged in to access this page. <a href='/login.php'>Login here</a></div>");
}
if (!filter_var($order_id, FILTER_VALIDATE_INT)) {
    die("<div class='error-message'>Invalid order ID.</div>");
}

try {
    // Fetch order and delivery details
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
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order_details = $result->fetch_assoc();

    if (!$order_details) {
        throw new Exception("Order not found or unauthorized access.");
    }

    // Generate and update OTP if the order is "Out for Delivery"
    if ($order_details['current_status'] === 'Out for Delivery') {
        $otp = generateOTP();
        $otp_sql = "UPDATE orders SET otp = ? WHERE order_id = ?";
        $otp_stmt = $conn->prepare($otp_sql);
        if (!$otp_stmt) {
            throw new Exception("Error preparing OTP statement: " . $conn->error);
        }
        $otp_stmt->bind_param("ii", $otp, $order_id);
        $otp_stmt->execute();
        $order_details['otp'] = $otp; // Include OTP in the displayed data
    }
} catch (Exception $e) {
    die("<div class='error-message'>" . htmlspecialchars($e->getMessage()) . "</div>");
}
?>

<!-- Display Order Details -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <style>
        .container {
            max-width: 100%;
            margin: auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .order-details, .otp-section {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            background: #f9f9f9;
        }

        .order-details h3, .otp-section h3 {
            margin-top: 0;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($order_details)): ?>
            <div class="order-details">
                <h3>Order Details</h3>
                <p><strong>Order ID:</strong> <?= htmlspecialchars($order_details['order_id']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($order_details['current_status']) ?></p>
                <p><strong>Total Price:</strong> $<?= htmlspecialchars(number_format($order_details['total_price'], 2)) ?></p>
                <p><strong>Delivery Address:</strong> <?= htmlspecialchars($order_details['delivery_address']) ?></p>

                <?php if (!empty($order_details['delivery_partner_name'])): ?>
                    <p><strong>Delivery Partner:</strong> <?= htmlspecialchars($order_details['delivery_partner_name']) ?></p>
                    <p><strong>Contact:</strong> <?= htmlspecialchars($order_details['delivery_partner_phone']) ?></p>
                <?php else: ?>
                    <p><strong>Delivery Partner:</strong> Not assigned yet</p>
                <?php endif; ?>
            </div>

            <?php if ($order_details['current_status'] === 'Out for Delivery'): ?>
                <div class="otp-section">
                    <h3>Order Delivery Confirmation</h3>
                    <p>Your order is on its way! Use the following OTP to confirm delivery:</p>
                    <p><strong>OTP:</strong> <?= htmlspecialchars($order_details['otp'] ?? 'N/A') ?></p>
                    <form action="confirm_delivery.php" method="POST">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_details['order_id']) ?>">
                        <label for="otp">Enter OTP:</label>
                        <input type="text" id="otp" name="otp" required>
                        <button type="submit">Confirm Delivery</button>
                    </form>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="error-message">No order details found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
