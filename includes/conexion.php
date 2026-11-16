<?php

// Datos necesarios para conectarnos a MySQL.
// Son constantes porque no necesitamos modificarlos.
const DB_HOST    = 'localhost';
const DB_USUARIO = 'root';
const DB_CLAVE   = '';
const DB_NOMBRE  = 'apsy';

// Configura la zona horaria de PHP para Argentina.
date_default_timezone_set('America/Argentina/Cordoba');

// Configura MySQLi para que los errores generen excepciones.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    // Crea la conexión con el servidor y la base de datos.
    $conexion = new mysqli(
        DB_HOST,
        DB_USUARIO,
        DB_CLAVE,
        DB_NOMBRE
    );

    // Configura la codificación de caracteres.
    $conexion->set_charset('utf8mb4');

    // Configura la zona horaria de MySQL.
    $conexion->query("SET time_zone = '-03:00'");

} catch (mysqli_sql_exception $e) {

    // Guarda el detalle técnico del error.
    error_log('APSY - error de conexión: ' . $e->getMessage());

    // Informa que ocurrió un error interno del servidor.
    http_response_code(500);

    // Muestra un mensaje entendible y detiene la ejecución.
    exit('No se pudo conectar con la base de datos. '
       . 'Verificá que MySQL esté iniciado en XAMPP y que la base "apsy" exista.');
}
