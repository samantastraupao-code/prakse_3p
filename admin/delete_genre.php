<?php
require_once("connection.php"); // Make sure no trailing space

if(isset($_GET['Del'])) {
    $genre_id = $_GET['Del'];

    // DELETE query
    $query = "DELETE FROM genres WHERE genre_id = '$genre_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        header("Location: /prakse_3p/admin/genre.php"); // Use full path
        exit;
    } else {
        echo "Please Check Your Query: " . mysqli_error($con);
    }
} else {
    header("Location: /prakse_3p/admin/genre.php");
    exit;
}
?>