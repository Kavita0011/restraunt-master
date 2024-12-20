<?php
session_start();

// Destroy session and redirect to login
session_destroy();
header("Location:  http://localhost/restraunt/api/user/user-login.php"); // Replace with your login page URL
exit();
?>
