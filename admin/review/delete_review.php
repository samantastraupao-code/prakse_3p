<?php
require_once("../connection.php");

if(isset($_GET['Del'])) {
    $review_id = $_GET['Del'];

    // Izdzēš atsauksmi
    $query = "DELETE FROM reviews WHERE review_id = '$review_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        // Pārsūta atpakaļ uz review.php
        header("Location: review.php");
        exit;
    } else {
        echo "Please Check Your Query: " . mysqli_error($con);
    }
} else {
    // Ja nav ID, zisūta atpakaļ uz review.php
    header("Location: review.php");
    exit;
}
?>