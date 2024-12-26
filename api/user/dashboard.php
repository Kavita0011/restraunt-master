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
        <style>
            .tab-content {
                display: none;
            }

            .tab-content.active {
                display: block;
            }

            .sidebar a {
                cursor: pointer;
            }
        </style>
        <script>
            function showTab(tabId) {
const contents = document.querySelectorAll('.tab-content');
contents.forEach(content => content.classList.remove('active'));

document.getElementById(tabId).classList.add('active');
}

document.addEventListener('DOMContentLoaded', () => {
showTab('dashboard'); // Show the dashboard tab by default
});
        </script>
    </head>

    <body>
        <div
            class="container">
            <!-- Sidebar Section -->
            <div class="sidebar">
                <h2>Restaurant App</h2>
                <a onclick="showTab('dashboard')">🏠 Dashboard</a>
                <a onclick="showTab('orders')">Orders</a>
                <a onclick="showTab('reservations')">📅 Reservations</a>
                <a onclick="showTab('Track-order')">Track your order</a>
                <a onclick="showTab('settings')">⚙️ Settings</a>
                <a onclick="showTab('Logout')">🚪 Logout</a>
            </div>

            <!-- Main Content Section -->
            <div
                class="main-content">
                <!-- Dashboard Section -->
                <div
                    id="dashboard" class="tab-content">
                    <?php if ($user): ?>
                        <div class="profile-header">
                            <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'https://via.placeholder.com/100'); ?>" alt="Profile Picture">
                            <div>
                                <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                                <p><?php echo htmlspecialchars($user['email']); ?></p>
                                <p>
                                    <strong><?php echo htmlspecialchars($user['membership'] ?? 'Regular Member'); ?></strong>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p><?php echo htmlspecialchars($error_message); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Orders Section -->
                <div id="orders" class="tab-content">
                    <h3>🍽️ Recent Orders</h3>
                    <?php include 'myorders.php' ?>
                </div>

                <!-- Reservations Section -->
                <div id="reservations" class="tab-content">
                    <h3>📅 Upcoming Reservations</h3>
                    <?php if (!empty($reservations)): ?>
                        <?php foreach ($reservations as $res): ?>
                            <div class="reservation-card">
                                <p>
                                    <strong>Date:</strong>
                                    <?php echo htmlspecialchars($res['date']); ?>
                                </p>
                                <p>
                                    <strong>Time:</strong>
                                    <?php echo htmlspecialchars($res['time']); ?>
                                </p>
                                <p>
                                    <strong>Guests:</strong>
                                    <?php echo htmlspecialchars($res['guests']); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No upcoming reservations found.</p>
                    <?php endif; ?>
                </div>

                <!-- Settings Section -->
                <div id="settings" class="tab-content">
                    <h3>⚙️ Settings</h3>
                        <?php include 'settings.php'; ?>
                </div>
                <!-- Track your order Section -->
                <div id="Track-order" class="tab-content">
                    <h3>Track your order</h3>
                   
                    <?php include '/xampp/htdocs/restraunt/view/tracking-delivery.php'; ?>
                </div>
                <!-- Logout Section -->
                <div id="Logout" class="tab-content">
                    <h3>Logging out</h3>
                    <?php include 'logout.php' ?>
                </div>
               
            </div>

        </div>
    </body>

</html>

