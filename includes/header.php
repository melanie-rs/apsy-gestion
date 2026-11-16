<?php
// Obtener el título de la página
$tituloPagina = $tituloPagina ?? 'Apsy';

// Obtener la sección activa del menú
$paginaActiva = $paginaActiva ?? 'inicio';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($tituloPagina); ?> | Apsy</title>

    <!-- Tipografía -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Estilos generales -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/layout.css?v=2">
    <link rel="stylesheet" href="assets/css/componentes.css">

    <?php if ($paginaActiva === 'inicio'): ?>
        <link rel="stylesheet" href="assets/css/dashboard.css">
    <?php endif; ?>
</head>

<body>

<div class="app-container">