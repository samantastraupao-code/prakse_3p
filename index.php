<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ratemovie</title>
</head>
<body>

<h1>Welcome to Ratemovie 🎬</h1>

<?php if (isset($_SESSION["username"])): ?>
    <p>Hello, <?= htmlspecialchars($_SESSION["username"]) ?>!</p>
    <a href="profile.php">Go to Profile</a><br>
    <a href="logout.php">Logout</a>
<?php else: ?>
    <a href="login.php">Login</a><br>
    <a href="register.php">Register</a>
<?php endif; ?>

</body>
</html>
