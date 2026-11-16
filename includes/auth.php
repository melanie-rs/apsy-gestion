<?php
// Iniciar la sesión si todavía no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario inició sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
