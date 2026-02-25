<?php
require_once("../connection.php"); // connection.php is one level up

if(isset($_GET['Del'])) {
    $user_id = $_GET['Del'];

    // DELETE query
    $query = "DELETE FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        // Absolute path redirection
        header("Location: users.php");
        exit;
    } else {
        echo "Please Check Your Query: " . mysqli_error($con);
    }
} else {
    header("Location: users.php");
    exit;
}
?>