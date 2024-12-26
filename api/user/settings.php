<?php
// Start the session



// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the current user ID from the session
$user_id = $_SESSION['user_id'];

// Initialize variables
$name = '';
$email = '';
$password = '';
$success_message = '';
$error_message = '';

// Fetch current user details from the database
$query = "SELECT name, email FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
if ($row = mysqli_fetch_assoc($result)) {
    $name = $row['name'];
    $email = $row['email'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Password update logic
    if (!empty($_POST['password'])) {
        // Ensure password is long enough
        if (strlen($_POST['password']) < 6) {
            $error_message = "Password must be at least 6 characters long.";
        } else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
        }
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    }

    // If no error, update user settings
    if (empty($error_message)) {
        // Update the database with the new data
        if ($password) {
            // If password was changed, update all fields
            $query = "UPDATE users SET name = '$name', email = '$email', password = '$password' WHERE id = '$user_id'";
        } else {
            // If password was not changed, only update name and email
            $query = "UPDATE users SET name = '$name', email = '$email' WHERE id = '$user_id'";
        }

        if (mysqli_query($conn, $query)) {
            $success_message = "Settings updated successfully.";
        } else {
            $error_message = "Error updating settings: " . mysqli_error($conn);
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Settings</title>
</head>
<body>
    <h1>Update Your Profile</h1>

    <!-- Display success or error message -->
    <?php if (isset($success_message)) { echo "<p style='color: green;'>$success_message</p>"; } ?>
    <?php if (isset($error_message)) { echo "<p style='color: red;'>$error_message</p>"; } ?>

    <!-- Settings Form -->
    <form method="POST" action="">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <label for="password">New Password (leave blank to keep current password):</label><br>
        <input type="password" id="password" name="password"><br><br>

        <input type="submit" value="Save Changes">
    </form>

</body>
</html>
