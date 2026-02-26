<?php
require_once("../connection.php"); // Make sure this path is correct

if(isset($_GET['Del'])) {
    $user_id = intval($_GET['Del']); // Sanitize input

    // 1️⃣ Delete all reviews by this user
    $delete_reviews = "DELETE FROM reviews WHERE user_id = $user_id";
    $result_reviews = mysqli_query($con, $delete_reviews);
    if(!$result_reviews) {
        die("Error deleting reviews: " . mysqli_error($con));
    }

    // 2️⃣ Delete the user
    $delete_user = "DELETE FROM users WHERE user_id = $user_id";
    $result_user = mysqli_query($con, $delete_user);

    if($result_user) {
        header("Location: users.php"); // Redirect back to users list
        exit;
    } else {
        echo "Error deleting user: " . mysqli_error($con);
    }
} else {
    header("Location: users.php");
    exit;
}
?>