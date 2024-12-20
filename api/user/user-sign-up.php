<?php
// Include database connection and functions
include("/xampp/htdocs/restraunt/includes/db.php"); // Update with the actual path
include("/xampp/htdocs/restraunt/includes/functions.php"); // Update with the actual path

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    // Prepare data for insertion
    $userData = [
        'email' => $user_email,
        'password' => password_hash($password, PASSWORD_BCRYPT), // Securely hash the password
        'name' => $name,
        'phone' => $phone,
        'created_at' => date('Y-m-d H:i:s'),
    ];

    // Insert the user record
    $isInserted = insertRecord($conn, 'users', $userData);

    if ($isInserted) {
        session_start();
        $_SESSION["user_email"] = $user_email;
        // echo $_SESSION["user_email"];
        header("Location: http://localhost/restraunt/api/user/dashboard.php?user_email=$user_email");
        exit();
    } else {
        echo "Failed to register. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="../../assets/css/login.css"> <!-- Custom CSS -->
</head>
<body>
    <div class="login-container">
        <h1>User Sign Up</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>
            <button type="submit" class="login-button">Sign Up</button>
        </form>
        <p class="signup-text">Already have an account? <a href="user-login.php">Sign in</a></p>
    </div>
</body>
</html>
