<?php
$error_message='';
if (isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
    $user_email = $_REQUEST['email'];
$user_password = $_REQUEST['password'];
    include("/xampp/htdocs/restraunt/includes/db.php");
    include("/xampp/htdocs/restraunt/includes/functions.php");

    $table = 'users';
    $columns = '`email`,`password`';
    $users = fetchRecords($conn, $table, $columns);

    foreach ($users as $user) {
        if ($user['email'] === $user_email && ($user['password'] === $user_password)) {
            session_start();
            $_SESSION["user_email"] = $user_email;
            // echo $_SESSION["user_email"];
            header("Location: http://localhost/restraunt/api/user/dashboard.php?user_email=$user_email");
            // exit();
            break;
        } else {
           $error_message= "please enter correct credentials";
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