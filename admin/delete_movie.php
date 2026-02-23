<?php
require_once("connection.php"); // nav liekās atstarpes un nav admin/ priekšā

if(isset($_GET['Del'])) {
    $movie_id = $_GET['Del'];

    // DELETE query
    $query = "DELETE FROM movies WHERE movie_id = '$movie_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        header("Location: movie.php"); // atgriež uz movie.php
        exit;
    } else {
        echo "Please Check Your Query: " . mysqli_error($con);
    }
} else {
    header("Location: movie.php");
    exit;
}
?>