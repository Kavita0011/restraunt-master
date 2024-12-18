<?php
$user_email = $_REQUEST['email'];
$user_password = $_REQUEST['password'];
if (isset($user_email) && isset($user_password)) {
    include("/xampp/htdocs/restraunt/includes/db.php");
    include("/xampp/htdocs/restraunt/includes/functions.php");

    $table = 'users';
    $columns = '`email`,`password`';
    $users = fetchRecords($conn, $table, $columns);

    foreach ($users as $user) {
        if ($user['email'] === $user_email && ($user['password'] === $user_password)) {
            header("Location: http://localhost/restraunt/api/user/dashboard.php?user_email=$user_email&user_password=$user_password");
            exit();
            break;
        } else {
            echo "please enter correct credentials";
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
        <form method="GET">
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
    </div>
    <p class="dashboard">Don't have an account? <a href="dashboard.php">dashboard</a></p>
    <!-- user-login form ends  -->
</body>

</html>