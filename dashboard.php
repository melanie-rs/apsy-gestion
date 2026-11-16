<?php

// Verificamos que el usuario haya iniciado sesión.
require_once 'includes/auth.php';

// Conectamos con la base de datos.
require_once 'includes/conexion.php';

// Cargamos las funciones auxiliares.
require_once 'includes/funciones.php';

// Configuración de la página.
$tituloPagina = 'Inicio';
$paginaActiva = 'inicio';

// Fecha actual.
$hoy = date('Y-m-d');
$mesActual = date('Y-m-01');
$nombreUsuario = $_SESSION['nombre'] ?? 'Usuario';

// Formateamos la fecha para mostrarla en español.
$dias = [
    'Sunday' => 'domingo',
    'Monday' => 'lunes',
    'Tuesday' => 'martes',
    'Wednesday' => 'miércoles',
    'Thursday' => 'jueves',
    'Friday' => 'viernes',
    'Saturday' => 'sábado'
];

$meses = [
    1 => 'enero',
    2 => 'febrero',
    3 => 'marzo',
    4 => 'abril',
    5 => 'mayo',
    6 => 'junio',
    7 => 'julio',
    8 => 'agosto',
    9 => 'septiembre',
    10 => 'octubre',
    11 => 'noviembre',
    12 => 'diciembre'
];

$nombreDia = $dias[date('l')];
$nombreMes = $meses[(int) date('n')];

$fechaHoy = ucfirst($nombreDia) . ' ' . date('d') .
            ' de ' . $nombreMes . ' de ' . date('Y');

// ========================================
// 1. MÉTRICAS DEL DASHBOARD
// ========================================

// Cantidad de turnos de hoy, sin contar los cancelados.
$sql = "SELECT COUNT(*) AS total
        FROM turnos
        WHERE fecha = ? AND estado != 'cancelado'";

$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $hoy);
$stmt->execute();
$turnosHoy = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Cantidad de turnos pendientes de hoy.
$sql = "SELECT COUNT(*) AS total
        FROM turnos
        WHERE fecha = ? AND estado = 'pendiente'";

$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $hoy);
$stmt->execute();
$turnosPendientes = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Cantidad de turnos confirmados de hoy.
$sql = "SELECT COUNT(*) AS total
        FROM turnos
        WHERE fecha = ? AND estado = 'confirmado'";

$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $hoy);
$stmt->execute();
$turnosConfirmados = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// Cantidad total de clientes activos.
$sql = "SELECT COUNT(*) AS total
        FROM clientes
        WHERE activo = 1";

$resultado = $conexion->query($sql);
$totalClientes = $resultado->fetch_assoc()['total'];

// Clientes registrados durante el mes actual.
$sql = "SELECT COUNT(*) AS total
        FROM clientes
        WHERE activo = 1
        AND creado_en >= ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $mesActual);
$stmt->execute();
$clientesNuevos = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

// ========================================
// 2. PRÓXIMOS TURNOS DE HOY
// ========================================

$sql = "SELECT
            t.id,
            t.hora_inicio,
            t.estado,
            c.nombre AS nombre_cliente,
            c.apellido AS apellido_cliente,
            p.nombre AS nombre_profesional,
            p.apellido AS apellido_profesional,
            s.nombre AS nombre_servicio
        FROM turnos t
        INNER JOIN clientes c ON t.cliente_id = c.id
        INNER JOIN profesionales p ON t.profesional_id = p.id
        INNER JOIN servicios s ON t.servicio_id = s.id
        WHERE t.fecha = ?
        AND t.estado != 'cancelado'
        ORDER BY t.hora_inicio ASC
        LIMIT 5";

$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $hoy);
$stmt->execute();
$resultado = $stmt->get_result();

$proximosTurnos = [];

while ($fila = $resultado->fetch_assoc()) {
    $proximosTurnos[] = $fila;
}

$stmt->close();

// ========================================
// 3. EQUIPO DE HOY
// ========================================

$sql = "SELECT
            p.id,
            p.nombre,
            p.apellido,
            p.especialidad,
            COUNT(t.id) AS cantidad_turnos
        FROM profesionales p
        LEFT JOIN turnos t
            ON p.id = t.profesional_id
            AND t.fecha = ?
            AND t.estado != 'cancelado'
        WHERE p.activo = 1
        GROUP BY p.id, p.nombre, p.apellido, p.especialidad
        ORDER BY cantidad_turnos DESC, p.nombre ASC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $hoy);
$stmt->execute();
$resultado = $stmt->get_result();

$equipoHoy = [];

while ($fila = $resultado->fetch_assoc()) {
    $equipoHoy[] = $fila;
}

$stmt->close();

?>


<?php require 'includes/header.php'; ?>

<?php require 'includes/sidebar.php'; ?>

<div class="main-area">

    <?php require 'includes/topbar.php'; ?>

    <main class="page-content">

        <!-- ENCABEZADO -->
        <section class="page-header">
            <div>
                <h1><?php echo obtenerSaludo(); ?>, <?php echo escapar($nombreUsuario); ?></h1>
                <p>Este es el resumen de actividad de tu estudio.</p>
                <p class="dashboard-date">
                    <i class="fa-regular fa-calendar"></i>
                    <?php echo escapar($fechaHoy); ?>
                </p>
            </div>

            <div class="page-header-actions">
                <button type="button" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Nuevo turno
                </button>
            </div>
        </section>

        <!-- MÉTRICAS -->
        <section class="metrics-grid">

            <article class="metric-card highlight">
                <div class="metric-top">
                    <span class="metric-label">Turnos de hoy</span>
                    <div class="metric-icon">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                </div>

                <div class="metric-value">
                    <?php echo (int) $turnosHoy; ?>
                </div>

                <p class="metric-description">Turnos programados para hoy</p>
            </article>

            <article class="metric-card">
                <div class="metric-top">
                    <span class="metric-label">Pendientes</span>
                    <div class="metric-icon">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                </div>

                <div class="metric-value">
                    <?php echo (int) $turnosPendientes; ?>
                </div>

                <p class="metric-description">Esperando confirmación</p>
            </article>

            <article class="metric-card">
                <div class="metric-top">
                    <span class="metric-label">Confirmados</span>
                    <div class="metric-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                </div>

                <div class="metric-value">
                    <?php echo (int) $turnosConfirmados; ?>
                </div>

                <p class="metric-description">Turnos confirmados para hoy</p>
            </article>

            <article class="metric-card">
                <div class="metric-top">
                    <span class="metric-label">Clientes totales</span>
                    <div class="metric-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>

                <div class="metric-value">
                    <?php echo (int) $totalClientes; ?>
                </div>

                <p class="metric-description">
                    <?php echo (int) $clientesNuevos; ?> nuevos este mes
                </p>
            </article>

        </section>

        <!-- PRÓXIMOS TURNOS -->
        <section class="card dashboard-appointments">

            <div class="card-header">
                <div>
                    <h2>Próximos turnos</h2>
                    <p>Los turnos programados para hoy</p>
                </div>

                <a href="turnos.php" class="dashboard-link">
                    Ver agenda
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <?php if (count($proximosTurnos) > 0): ?>

                <div class="table-container">
                    <table class="data-table">

                        <thead>
                            <tr>
                                <th>Horario</th>
                                <th>Cliente</th>
                                <th>Profesional</th>
                                <th>Servicio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($proximosTurnos as $turno): ?>

                                <tr>
                                    <td>
                                        <strong>
                                            <?php echo escapar(horaLegible($turno['hora_inicio'])); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?php
                                        echo escapar(
                                            $turno['nombre_cliente'] . ' ' .
                                            $turno['apellido_cliente']
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo escapar(
                                            $turno['nombre_profesional'] . ' ' .
                                            $turno['apellido_profesional']
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php echo escapar($turno['nombre_servicio']); ?>
                                    </td>

                                    <td>
                                        <span class="estado <?php echo escapar(claseEstado($turno['estado'])); ?>">
                                            <?php echo escapar(textoEstado($turno['estado'])); ?>
                                        </span>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>
                </div>

            <?php else: ?>

                <div class="empty-state">
                    <i class="fa-regular fa-calendar"></i>
                    <strong>No hay turnos para hoy</strong>
                    <p>Cuando se registren nuevos turnos, aparecerán acá.</p>
                </div>

            <?php endif; ?>

        </section>