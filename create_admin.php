<?php
include 'includes/CONFIG.php';

$username = "admin";
$password = password_hash("admin", PASSWORD_DEFAULT);
$role = "admin";

$sql = "INSERT INTO users (username, password, role) 
        VALUES ('$username', '$password', '$role')";

if (mysqli_query($con, $sql)) {
    echo "Admin user created!";
} else {
    echo "Error: " . mysqli_error($con);
}
?>