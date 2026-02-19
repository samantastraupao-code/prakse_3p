<?php
session_start(); // Sāk sesiju, lai jau reģistrētie lietotāji varētu piekļūt saviem datiem
include 'includes/CONFIG.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($con, $_POST["username"]); //Aizsargā lietotājvārdu pret SQL
    $password = $_POST["password"];

    // Pārbauda, vai lietotājs eksistē datubāzē
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($con, $sql);

    // Pārliecinās, ka atrasts tikai viens lietotājs ar šo lietotājvārdu
    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        // Pārbauda, vai parole ir pareiza
        if (password_verify($password, $user["password"])) {
            $_SESSION["username"] = $user["username"]; // Saglabā lietotājvārdu sesijā

            // Pāradresē lietotāju uz profila lapu
            header("Location: profile.php");
            exit();
        } else {
            // Ja parole ir nepareiza
            $error = "Nepareiza parole.";
        }
    } else {
        // Ja lietotājs nav atrasts datubāzē
        $error = "Lietotājs nav atrasts.";
    }
}
?>
<?php

include 'includes/CONFIG.php';

// Pārbauda, vai pieprasījums ir POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Iegūst lietotājvārdu un paroli no POST datiem
    $username = mysqli_real_escape_string($con, $_POST["username"]); //Aizsargā lietotājvārdu pret SQL
    $password = $_POST["password"]; //Iegūst paroli

    // Pārbauda, vai lietotājs eksistē datubāzē
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($con, $sql); // Izpilda SQL vaicājumu

    // Pārliecinās, ka atrasts tikai viens lietotājs ar šo lietotājvārdu
    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result); //Iegūst lietotāja datus
        // Pārbauda, vai parole ir pareiza
        if (password_verify($password, $user["password"])) {
            $_SESSION["username"] = $user["username"]; // Saglabā lietotājvārdu sesijā
            // Pāradresē lietotāju uz profila lapu
            header("Location: profile.php");
            exit(); // Beidz izpildi
        } else {
            // Ja parole ir nepareiza
            $error = "Nepareiza parole.";
        }
    } else {
        // Ja lietotājs nav atrasts datubāzē
        $error = "Lietotājs nav atrasts.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pieteikties</title>
</head>
<body>

<div class="container">
    <h2>Pieteikties</h2>
    <?php if (isset($error)): // Pārbauda kļūdas ?>
        <div class="error">
            <p><?= htmlspecialchars($error) ?></p> 
        </div>
    <?php endif; ?>
    <form method="post" action="login.php" novalidate> <!-- Formas nosūtīšana uz login.php -->
        <input type="text" name="username" placeholder="Lietotājvārds" required>
        <input type="password" name="password" placeholder="Parole" required>
        <input type="submit" value="Pieteikties"> 
        <a href="index.php" class="button_link">Atpakaļ</a>
    </form>
</div>

</body>
</html>
