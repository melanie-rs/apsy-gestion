<?php

// Iniciamos o recuperamos la sesión actual.
session_start();

// Eliminamos todas las variables guardadas en la sesión.
$_SESSION = [];

// Destruimos la sesión.
session_destroy();

// Redirigimos al usuario al login.
header('Location: login.php');
exit;
