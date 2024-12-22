<?php 
// Start the session to maintain user login state
session_start();

// Check if the user is logged in by verifying the session
if (empty($_SESSION["user_email"])) {
    die("<div class='container'>You are not logged in. Please <a href='user-login.php'>log in</a>.</div>");
} 

// Include the database connection and helper functions
include("/xampp/htdocs/restraunt/includes/db.php"); // Database connection
include("/xampp/htdocs/restraunt/includes/functions.php"); // FetchRecords and other helper functions

// Fetch the logged-in user's email from the session
$user_email = $_SESSION['user_email'];
// Define the database table and columns to fetch user data
$table = 'users';
$columns = "*"; // Only fetch necessary columns
$conditions = ['email' => $user_email]; // Filter by email to get the logged-in user's data

// Use fetchRecords function to retrieve user details
$user_details = fetchRecords($conn, $table, $columns, $conditions);

// Initialize variables for dashboard data
$error_message = ''; // To store any error messages
$user = null; // Will hold the user's details if found
$recent_orders = []; // Mock data for recent orders
$reservations = []; // Mock data for reservations
// var_dump($user_details);
// Check if user details were retrieved successfully
if (!empty($user_details)) {
    $user = $user_details[0]; // Fetch the first record since email is unique
} else {
    $error_message = "Unable to fetch user data. Please try again.";
}

// Populate recent orders and reservations (mock data for demonstration purposes)
if ($user) {
    // Mock data for recent orders (replace this with actual database queries)
    $recent_orders = [
        ['item' => 'Margherita Pizza', 'date' => '2024-06-10', 'status' => 'Delivered'],
        ['item' => 'Pasta Alfredo', 'date' => '2024-06-12', 'status' => 'Preparing'],
        ['item' => 'Caesar Salad', 'date' => '2024-06-15', 'status' => 'Delivered'],
    ];

    // Mock data for reservations (replace this with actual database queries)
    $reservations = [
        ['date' => '2024-06-20', 'time' => '7:00 PM', 'guests' => 4],
        ['date' => '2024-06-25', 'time' => '8:00 PM', 'guests' => 2],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant User Dashboard</title>
    <!-- Link to the CSS file for styling -->
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar Section -->
        <div class="sidebar">
            <h2>Restaurant App</h2>
            <a href="#">🏠 Dashboard</a>
            <a href="../../view/menu.php">Menu</a>
            <a href="orders.php">Orders</a>
            <a href="../../view/tracking-delivery.php?delivery_id=1">Track your order</a>
            <a href="#">📅 Reservations</a>
            <a href="#">⚙️ Settings</a>
            <a href="logout.php">🚪 Logout</a>
        </div>

        <!-- Main Content Section -->
        <div class="main-content">
            <!-- Check if user details are available -->
            <?php if ($user): ?>
                <!-- Profile Header Section -->
                <div class="profile-header">
                    <!-- Display user's profile picture or a default image -->
                    <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'https://via.placeholder.com/100'); ?>" alt="Profile Picture">
                    <div>
                        <!-- Display user's name, email, and membership type -->
                        <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong><?php echo htmlspecialchars($user['membership'] ?? 'Regular Member'); ?></strong></p>
                    </div>
                </div>

                <!-- Recent Orders Section -->
                <div class="dashboard-section">
                    <h3>🍽️ Recent Orders</h3>
                    <table>
                        <tr>
                            <th>Item</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                        <!-- Loop through and display each recent order -->
                        <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['item']); ?></td>
                                <td><?php echo htmlspecialchars($order['date']); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <!-- Reservations Section -->
                <div class="dashboard-section">
                    <h3>📅 Upcoming Reservations</h3>
                    <!-- Loop through and display each reservation -->
                    <?php foreach ($reservations as $res): ?>
                        <div class="reservation-card">
                            <p><strong>Date:</strong> <?php echo htmlspecialchars($res['date']); ?></p>
                            <p><strong>Time:</strong> <?php echo htmlspecialchars($res['time']); ?></p>
                            <p><strong>Guests:</strong> <?php echo htmlspecialchars($res['guests']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Display an error message if user details are not available -->
                <p><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
