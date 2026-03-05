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
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];

            //Ja admin tad pāsūtīs uz movies.php
            if ($user["role"] === "admin") {
                header("Location: admin/movie/movie.php");
            } else {
                header ("Location: index.php");
            }
            exit();
        } else {
            // Ja parole ir nepareiza
            $error = "Wrong password.";
        }
    } else {
        // Ja lietotājs nav atrasts datubāzē
        $error = "User not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/style_login.css">
</head>
<body>

<div class="container">
    <h2>Log in</h2>

    <div class="topnav">
        <a href="index.php">Home</a>
        <a class="active" href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div> 

    <?php if (isset($error)): // Pārbauda kļūdas ?>
        <div class="error">
            <p><?= htmlspecialchars($error) ?></p> 
        </div>
    <?php endif; ?>
    
    <form method="post" action="login.php" novalidate> <!-- Formas nosūtīšana uz login.php -->
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login"> 
        <a href="index.php" class="button_link">Back</a>
    </form>
</div>

</body>
</html>
