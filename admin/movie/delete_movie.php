<?php
require_once("../connection.php"); // connection.php is one level up

if(isset($_GET['Del'])) {
    $movie_id = $_GET['Del'];

    // DELETE query
    $query = "DELETE FROM movies WHERE movie_id = '$movie_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        // Absolute path redirection
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