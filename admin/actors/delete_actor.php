<?php
require_once("../connection.php"); // Make sure no trailing space

if(isset($_GET['Del'])) {
    $actor_id = $_GET['Del'];

    // DELETE query
    $query = "DELETE FROM actors WHERE actor_id = '$actor_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        // Redirect back to the actors list
        header("Location: actors.php"); // relative path works
        exit;
    } else {
        echo "Please Check Your Query: " . mysqli_error($con);
    }
} else {
    // If no ID provided, go back to list
    header("Location: actors.php");
    exit;
}
?>