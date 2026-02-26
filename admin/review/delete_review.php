<?php
require_once("../connection.php"); // Make sure no trailing space

if(isset($_GET['Del'])) {
    $review_id = $_GET['Del'];

    // DELETE query
    $query = "DELETE FROM reviews WHERE review_id = '$review_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        // Redirect back to the actors list
        header("Location: review.php"); // relative path works
        exit;
    } else {
        echo "Please Check Your Query: " . mysqli_error($con);
    }
} else {
    // If no ID provided, go back to list
    header("Location: review.php");
    exit;
}
?>