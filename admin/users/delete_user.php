<?php
require_once("../connection.php");

if(isset($_GET['Del'])) {
    $user_id = intval($_GET['Del']); 

    // Izdzēš visas lietotāja atstātās atsauksmes
    $delete_reviews = "DELETE FROM reviews WHERE user_id = $user_id";
    $result_reviews = mysqli_query($con, $delete_reviews);
    if(!$result_reviews) {
        die("Error deleting reviews: " . mysqli_error($con));
    }

    // Izdzēš lietotāju
    $delete_user = "DELETE FROM users WHERE user_id = $user_id";
    $result_user = mysqli_query($con, $delete_user);

    if($result_user) {
        header("Location: users.php"); // Pārsūta atpakaļ uz user.php
        exit;
    } else {
        echo "Error deleting user: " . mysqli_error($con);
    }
} else {
    header("Location: users.php");
    exit;
}
?>