<?php
// Iekļauj konfigurācijas un funkciju failus
include_once 'includes/CONFIG.php';
include_once 'includes/FUNCTIONS.php';

// Pārbauda, vai GET pieprasījumā ir parametrs 'uid'
if(isset($_GET['uid'])){
  // Pieņem tikai uid kā parametru, NEKAS cits netiek apstrādāts
  $uid = $_GET['uid'];

  // Izveido SQL vaicājumu, lai atlasītu lietotāju ar konkrētu ID
  $sql = "SELECT * FROM users WHERE id = '$uid' ";
  // Izpilda SQL vaicājumu
  $result = mysqli_query ($con, $sql);

  // Pārbauda, vai vaicājums atgriež kādu rezultātu
  if(mysqli_num_rows($result) < 1){
    // Ja nav atrasts neviens lietotājs, iestata rezultātu uz false
    $result = false;
  }
} else {
  // Ja 'uid' nav norādīts, iestata rezultātu uz false
  $result = false;
}
// Sasa1323@! (Šī rinda izskatās kā nejauša, iespējams, ka tā ir kļūda vai paraugs)
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GET</title>
</head>

<body>

  <?php if($result): // Pārbauda, vai ir atrasts lietotājs ?>
      <div>
        <?php while($user = mysqli_fetch_assoc($result)): // Iterē cauri atrastajiem lietotājiem ?>
          <h1><?= $user['username'] ?></h1> <!-- Izvada lietotāja vārdu -->
          <p> <?= $user['email'] ?></p> <!-- Izvada lietotāja e-pastu -->
        <?php endwhile; ?>
      </div>
      <?php else: // Ja lietotājs nav atrasts ?>
        <p>Nav atrasts tāds lietotājs </p>
    <?php endif; ?>
</body>

</html>
