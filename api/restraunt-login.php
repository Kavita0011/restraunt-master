<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant login</title>
    <!-- custom login page css -->
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <!-- restaurant login page starts here -->
    <div class="login-container">
        <h1>Restaurant Login</h1>
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
        <p class="signup-text">Don't have an account? <a href="restraunt-sign-up.php">Sign up</a></p>
    </div>
    <!-- restaurant login page ends here -->
</body>
</html>
