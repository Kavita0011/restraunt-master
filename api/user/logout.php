<?php
if (isset($user_id)) {
    include("/xampp/htdocs/restraunt/includes/db.php");
    include("/xampp/htdocs/restraunt/includes/functions.php");

    $table = 'users';
    $columns = '`user_id`';
    $users = fetchRecords($conn, $table, $columns);

    foreach ($users as $user) {
        if ($user['user_id'] === $user_id ) {
// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to login page or home page
header("Location: user-login.php"); // Replace with your login page URL
exit();

            break;
        } else {
            echo "please enter correct credentials";
        }
    }
}

?>
