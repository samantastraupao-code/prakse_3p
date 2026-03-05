<?php
// Iekļauj funkciju un konfigurācijas failus
include 'includes/FUNCTIONS.php';
include 'includes/CONFIG.php';

// Inicializē kļūdu masīvu
$errors = [];

// Pārbauda, vai pieprasījums ir POST un vai ir kādi dati
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST)) {

    // Apstrādā ievadītos datus un noņem liekās atstarpes
    $username = test_input($_POST["username"] ?? '');
    $email = test_input($_POST["email"] ?? '');
    $password = test_input($_POST["password"] ?? ''); 
    $confirmpassword = test_input($_POST["checkpassword"] ?? ''); 

    // Parveido paroli, lai to droši saglabātu datubāzē
    $theGoodHashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Validē lietotājvārdu
    if (empty($username)) {
        $errors[] = "Username is required and cannot be empty.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }

    // Validē e-pastu
    if (empty($email)) {
        $errors[] = "Email is required and cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validē paroli
    if (empty($password)) {
        $errors[] = "Password is required and cannot be empty.";
    } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $password)) {
        $errors[] = "Your password must include at least 8 characters, one uppercase letter, one number, and one symbol.";
    } elseif ($password !== $confirmpassword) {
        $errors[] = "Password must be entered twice!";
    }

    // Ja nav kļūdu, turpina ar datubāzes operācijām
    if (empty($errors)) {
        // Izveido savienojumu ar datubāzi
        $con = mysqli_connect("localhost", "root", "", "prakse_3p");

        // Pārbauda, vai savienojums izdevās
        if (!$con) {
            $errors[] = "Couldn't connect to MySQL: " . mysqli_connect_error();
        } else {
            // Pārbauda, vai lietotājvārds jau eksistē
            $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $errors[] = "This username is already taken.";
            }
            $stmt->close();

            // Ja nav kļūdu, ievieto jauno lietotāju datubāzē
            if (empty($errors)) {
                $stmt = $con->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                if (!$stmt) {
                    $errors[] = "Prepare failed: " . $con->error;
                } else {
                    $stmt->bind_param("sss", $username, $email, $theGoodHashedPassword);
                    if (!$stmt->execute()) {
                        $errors[] = "Execute failed: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }

            // Aizver savienojumu ar datubāzi
            $con->close();
        }
    }

    // Pārbauda, vai ir kļūdas pirms pāradresēšanas
    if (empty($errors)) {
        session_start(); 
        $_SESSION["username"] = $username;
        $_SESSION["email"] = $email;
        header("Location: index.php"); // Pāradresē uz profila lapu
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>

    <link rel="stylesheet" href="css/style_register.css">
</head>
<body>



<div class="container">
    <h2>Register</h2> 

    <div class="topnav">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a class="active" href="register.php">Register</a>
    </div>
    

        <form method="post" action="" novalidate>
            <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            
            <input type="email" name="email" placeholder="E-mail" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            
            <input type="password" name="password" placeholder="Password" required>
            
            <input type="password" name="checkpassword" placeholder="Confirm password" required>
            
            <input type="submit" value="Register">
        </form>


    <?php if (!empty($errors)): // Pārbauda, vai ir kļūdas ?>
        <div class="error">
            <?php foreach ($errors as $error):?>
                <p><?= htmlspecialchars($error) ?></p> <!-- Izvada kļūdas -->
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

     <p> Already have an account? <a href="login.php"> Login</a></p> 
</div>



</body>
</html>
