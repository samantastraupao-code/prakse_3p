<?php
require_once("../connection.php"); 

if(isset($_GET['Del'])) {
    $genre_id = $_GET['Del'];

    $query = "DELETE FROM genres WHERE genre_id='$genre_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        header("Location: genre.php");
        exit();
    } else {
        echo "Database error: " . mysqli_error($con);
    }
} else {
    header("Location: genre.php");
    exit();
}
?>