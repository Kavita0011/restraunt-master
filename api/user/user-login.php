<?php  
include("/xampp/htdocs/restraunt/includes/db.php");
$user_id=0;
$user_password="";
$user_email=$_REQUEST['user_email'];
$user_password=$_REQUEST['user_password'];
if(in_array($user_email,$user_emails) &&in_array($user_password,$user_passwords)){
    header("Location: http://localhost/restraunt/api/dashboard.php?user_id=$user_id");
    exit();
}else{
echo"please enter correct credentials";
}?>
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
        <form action="#" method="POST">
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
