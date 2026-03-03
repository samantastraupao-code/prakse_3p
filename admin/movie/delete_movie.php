<?php
require_once("../connection.php");

if(isset($_GET['Del'])) {
    $movie_id = $_GET['Del'];

    $query = "DELETE FROM movies WHERE movie_id = '$movie_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        header("Location: movie.php");
        exit;
    } else {
        echo "Please Check Your Query: " . mysqli_error($con);
    }
} else {
    header("Location: movie.php");
    exit;
}
?>