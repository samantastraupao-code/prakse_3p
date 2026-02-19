<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php"); //Pēc izrakstīšanās lietotāju aizsūta atpakaļ uz index.php
exit();
?>