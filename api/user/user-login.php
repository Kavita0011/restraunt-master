<?php
// Check if the 'email' and 'password' fields are set in the request
if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
    // Store the provided email and password in variables
    $user_email = $_REQUEST['email'];
    $user_password = $_REQUEST['password'];

    // Include the database connection file and functions file
    include("/xampp/htdocs/restraunt/includes/db.php");
    include("/xampp/htdocs/restraunt/includes/functions.php");

    // Specify the table and columns to fetch data from
    $table = 'users';
    $columns = '`email`,`password`';

    // Fetch all user records from the database using a custom function
    $users = fetchRecords($conn, $table, $columns);

    // Loop through each user record to validate credentials
    foreach ($users as $user) {
        // Check if the provided email and password match a record
        if ($user['email'] === $user_email && ($user['password'] === $user_password)) {
            // Redirect the user to the dashboard if credentials are correct
            header("Location: http://localhost/restraunt/api/user/dashboard.php?user_email=$user_email&user_password=$user_password");
            exit(); // Stop further script execution after redirection
            break; // Break out of the loop
        } else {
            // Set an error message if credentials do not match
            $error_message = "Please enter correct credentials";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <!-- custom login page css  -->
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>

<body>
    <!-- user login-form starts here -->
    <div class="login-container"> 
    <h1>User Login</h1>
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
        <p class="signup-text">Don't have an account? <a href="user-sign-up.php">Sign up</a></p>
        <!-- error message -->
        <div class="error_message">
        <?php
        echo "$error_message";
        ?>
    </div> 

    </div>

    <!-- user-login form ends  -->
</body>

</html>