<?php

// Escapa texto para mostrarlo de forma segura en HTML.
function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// Obtiene las iniciales de un nombre y apellido.
function obtenerIniciales($nombre, $apellido) {
    $inicialNombre = mb_substr($nombre ?? '', 0, 1, 'UTF-8');
    $inicialApellido = mb_substr($apellido ?? '', 0, 1, 'UTF-8');

    return mb_strtoupper($inicialNombre . $inicialApellido, 'UTF-8');
}

// Devuelve la clase CSS correspondiente al estado de un turno.
function claseEstado($estado) {
    $clases = [
        'pendiente' => 'estado-pendiente',
        'confirmado' => 'estado-confirmado',
        'cancelado' => 'estado-cancelado',
        'completado' => 'estado-completado'
    ];

    return $clases[$estado] ?? 'estado-pendiente';
}

// Devuelve el nombre del estado para mostrar en pantalla.
function textoEstado($estado) {
    $nombres = [
        'pendiente' => 'Pendiente',
        'confirmado' => 'Confirmado',
        'cancelado' => 'Cancelado',
        'completado' => 'Completado'
    ];

    return $nombres[$estado] ?? ucfirst($estado);
}

// Convierte una fecha al formato día/mes/año.
function fechaLegible($fecha) {
    if (empty($fecha)) {
        return '';
    }

    $timestamp = strtotime($fecha);

    return $timestamp ? date('d/m/Y', $timestamp) : '';
}

// Convierte una hora al formato de 24 horas.
function horaLegible($hora) {
    if (empty($hora)) {
        return '';
    }

    return date('H:i', strtotime($hora));
}

// Devuelve un saludo según la hora del día.
function obtenerSaludo() {
    $hora = (int) date('H');

    if ($hora < 12) {
        return 'Buenos días';
    } elseif ($hora < 20) {
        return 'Buenas tardes';
    }

    return 'Buenas noches';
}