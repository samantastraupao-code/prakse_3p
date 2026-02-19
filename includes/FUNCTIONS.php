<?php

//Funkcija, kas apstrādā ievadītos datus, lai novērstu drošības problēmas
function test_input($data) {
  $data = trim($data); //noņem liekās atstarpes no sākuma un beigām
  $data = stripslashes($data); //Noņem atpakaļ slīpsvītras
  $data = htmlspecialchars($data);
  return $data; //Atgriež apstrādātos datus
}

//SQL vaicājums, lai iegūtu visus lietotājus no datubāzes
$sql = "SELECT * FROM `users`";

//izveido savienojumu ar datubāzi
$con = mysqli_connect("localhost","root", "","prakse_3p");


?>