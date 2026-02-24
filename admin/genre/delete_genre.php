<?php
require_once("../connection.php"); // connection.php is 2 levels up from admin/genre/

if(isset($_GET['Del'])) {
    $genre_id = $_GET['Del'];

    // DELETE query
    $query = "DELETE FROM genres WHERE genre_id='$genre_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        // redirect back to genre list
        header("Location: genre.php");
        exit();
    } else {
        echo "Database error: " . mysqli_error($con);
    }
} else {
    // if no Del parameter, go back
    header("Location: genre.php");
    exit();
}
?>