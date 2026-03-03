<?php
require_once("../connection.php");

if(isset($_GET['Del'])) {
    $actor_id = $_GET['Del'];

    $query = "DELETE FROM actors WHERE actor_id = '$actor_id'";
    $result = mysqli_query($con, $query);

    if($result) {
        header("Location: actors.php");
        exit;
    } else {
        echo "Please Check Your Query: " . mysqli_error($con);
    }
} else {
    header("Location: actors.php");
    exit;
}
?>