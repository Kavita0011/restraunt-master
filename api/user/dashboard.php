<?php
// user_dashboard.php
session_start();

// Dummy user data (replace this with database connection)

// include('../../includes/header.php'); 
// Include the database connection file and functions file
include("/xampp/htdocs/restraunt/includes/db.php");
include("/xampp/htdocs/restraunt/includes/functions.php");


// Check if the 'email' and 'password' fields are set in the request
if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
    // Store the provided email and password in variables
    $user_email = $_REQUEST['email'];
    $user_password = $_REQUEST['password'];


    // Specify the table and columns to fetch data from
    $table = 'users';
    $columns = '*';
    // $conditions=['user_email'=>$user_email];
    // Fetch all user records from the database using a custom function
    $user_details = fetchRecords($conn, $table, $columns);
    $user="hello";
    // Loop through each user record to validate credentials
    // foreach ($users as $user) {
    //     // Check if the provided email and password match a record
    //     if ($user['email'] === $user_email && ($user['password'] === $user_password)) {
    //         // Redirect the user to the dashboard if credentials are correct
    //         header("Location: http://localhost/restraunt/api/user/dashboard.php?user_email=$user_email&user_password=$user_password");
    //         exit(); // Stop further script execution after redirection
    //         break; // Break out of the loop
    //     } else {
    //         // Set an error message if credentials do not match
    //         $error_message = "Please enter correct credentials";
    //     }
    // }
}


// Fetch menu items
// $user_email = $_REQUEST['user_email'];
// $sql = "SELECT * FROM `users` WHERE `email`='$user_email'";
// $result = $conn->query($sql);
//  while ($row = $result->fetch_assoc()) {
// $user = [
//     'name' => $row['name'],
//     'email' => $row['email'],
//     'membership' => 'Gold Member',
//     'profile_picture' => 'https://via.placeholder.com/100', // Example placeholder image
// ];
//  }
// Dummy recent orders (replace this with database connection)
$recent_orders = [
    ['item' => 'Margherita Pizza', 'date' => '2024-06-10', 'status' => 'Delivered'],
    ['item' => 'Pasta Alfredo', 'date' => '2024-06-12', 'status' => 'Preparing'],
    ['item' => 'Caesar Salad', 'date' => '2024-06-15', 'status' => 'Delivered'],
];

// Dummy reservations
$reservations = [
    ['date' => '2024-06-20', 'time' => '7:00 PM', 'guests' => 4],
    ['date' => '2024-06-25', 'time' => '8:00 PM', 'guests' => 2],
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant User Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Restaurant App</h2>
            <a href="#">🏠 Dashboard</a>
            <a href="#">🍽️ My Orders</a>
            <a href="#">📅 Reservations</a>
            <a href="#">👤 Profile</a>
            <a href="#">⚙️ Settings</a>
            <a href="logout.php?user_id=$user[user_id]">🚪 Logout</a>

        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Profile Header -->
            <div class="profile-header">
                <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
                <div>
                    <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong><?php echo htmlspecialchars($user['membership']); ?></strong></p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="dashboard-section">
                <h3>🍽️ Recent Orders</h3>
                <table>
                    <tr>
                        <th>Item</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($recent_orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['item']); ?></td>
                            <td><?php echo htmlspecialchars($order['date']); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- Reservations -->
            <div class="dashboard-section">
                <h3>📅 Upcoming Reservations</h3>
                <?php foreach ($reservations as $res): ?>
                    <div class="reservation-card">
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($res['date']); ?></p>
                        <p><strong>Time:</strong> <?php echo htmlspecialchars($res['time']); ?></p>
                        <p><strong>Guests:</strong> <?php echo htmlspecialchars($res['guests']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="data"><?php  var_dump($user); ?></div>
    </div>
</body>

</html>