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
        $errors[] = "Lietotājvārds ir nepieciešams un nevar būt tukšs.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Lietotājvārdam ir jābūt vismaz 3 simboliem garam!";
    }

    // Validē e-pastu
    if (empty($email)) {
        $errors[] = "E-mail ir nepieciešams un nevar būt tukšs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Nepareizs e-mail formāts.";
    }

    // Validē paroli
    if (empty($password)) {
        $errors[] = "Parole ir nepieciešama un nevar būt tukša.";
    } elseif (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", $password)) {
        $errors[] = "Parolē jāietver vismaz 8 simboli, 1 lielais burts, 1 cipars un 1 simbols!";
    } elseif ($password !== $confirmpassword) {
        $errors[] = "Parole jāievada otrreiz!";
    }

    // Ja nav kļūdu, turpina ar datubāzes operācijām
    if (empty($errors)) {
        // Izveido savienojumu ar datubāzi
        $con = mysqli_connect("localhost", "root", "", "prakse_3p");

        // Pārbauda, vai savienojums izdevās
        if (!$con) {
            $errors[] = "Neizdevās savienoties ar MySQL: " . mysqli_connect_error();
        } else {
            // Pārbauda, vai lietotājvārds jau eksistē
            $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $errors[] = "Lietotājvārds jau eksistē.";
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
        session_start(); // Sāk sesiju
        $_SESSION["username"] = $username; // Saglabā lietotājvārdu sesijā
        $_SESSION["email"] = $email; // Saglabā e-pastu sesijā
        header("Location: index.php"); // Pāradresē uz profila lapu
        exit(); // Beidz izpildi
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reģistrācija</title>
</head>
<body>



<div class="container">
    <h2>Reģistrēties</h2> 

    <div class="topnav">
        <a href="index.php">Sākums</a>
        <a href="login.php">Ielogoties</a>
        <a class="active" href="register.php">Reģistrēties</a>
    </div>
    
    <form method="post" action="" novalidate>
        <input type="text" name="username" placeholder="Lietotājvārds" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        
        <input type="email" name="email" placeholder="E-pasts" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        
        <input type="password" name="password" placeholder="Parole" required>
        
        <input type="password" name="checkpassword" placeholder="Apstiprināt paroli" required>
        
        <input type="submit" value="Reģistrēties">
  
    </form>

    <?php if (!empty($errors)): // Pārbauda, vai ir kļūdas ?>
        <div class="error">
            <?php foreach ($errors as $error):?>
                <p><?= htmlspecialchars($error) ?></p> <!-- Izvada kļūdas -->
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

 <p>Jau ir konts? <a href="login.php">Pieteikties</a></p>

</body>
</html>
