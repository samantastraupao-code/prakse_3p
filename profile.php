<?php
session_start();
include 'includes/CONFIG.php';

//Pārsūta lietotāju un login lapu, ja nav pierakstījies
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

//Sadabū pierakstījušā lietotāja datus
$username = $_SESSION["username"];
$sql = "SELECT username, email FROM users WHERE username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profils</title>
 
</head>
<body>

  <main class="profile-container">
    <section class="user-info">
      <h2 class="username"><?= htmlspecialchars($user['username']); ?></h2>
      <p class="email"><?= htmlspecialchars($user['email']); ?></p>
      <a href="logout.php" class="btn-logout">Izrakstīties</a>
    </section>
  </main>
</body>
</html>