<?php
session_start();

require_once __DIR__ . '/includes/conexion.php';

// Si el usuario ya inició sesión, ir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$usuario = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validar campos obligatorios
    if ($usuario === '' || $password === '') {

        $error = 'Completá todos los campos.';

    } else {

        // Buscar el usuario en la base de datos
        $sql = "SELECT id, usuario, password_hash, nombre, apellido, rol
                FROM usuarios
                WHERE usuario = ?
                LIMIT 1";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $datosUsuario = $resultado->fetch_assoc();

        // Verificar que exista el usuario y que la contraseña coincida
        if (
            $datosUsuario &&
            password_verify($password, $datosUsuario['password_hash'])
        ) {

            // Renovar el identificador de sesión
            session_regenerate_id(true);

            // Guardar los datos del usuario en la sesión
            $_SESSION['usuario_id'] = $datosUsuario['id'];
            $_SESSION['usuario'] = $datosUsuario['usuario'];
            $_SESSION['nombre'] = $datosUsuario['nombre'];
            $_SESSION['apellido'] = $datosUsuario['apellido'];
            $_SESSION['rol'] = $datosUsuario['rol'];

            // Ir al panel principal
            header('Location: dashboard.php');
            exit;

        } else {

            $error = 'El usuario o la contraseña no son correctos. Revisá los datos e intentá de nuevo.';
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Apsy</title>

    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>

    <main class="login-container">

        <!-- PANEL IZQUIERDO: FOTOGRAFÍA Y MARCA -->
        <section class="login-brand">

            <div class="brand-overlay"></div>

            <div class="brand-logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="6" r="3"/>
                        <circle cx="6" cy="18" r="3"/>
                        <path d="m20 4-12 8"/>
                        <path d="m8 12 12 8"/>                      
                    </svg>
                </div>

                <span>Apsy</span>
            </div>

            <div class="brand-content">

                <div class="brand-line"></div>

                <h1>Más tiempo para lo que mejor hacés.</h1>

                <p>
                    Tu agenda en orden. Tu equipo conectado.
                    Cada cliente, en buenas manos.
                </p>

                <span class="brand-footer">
                    HECHO PARA EL RITMO DE TU SALÓN
                </span>

            </div>

        </section>

        <!-- PANEL DERECHO: FORMULARIO -->
        <section class="login-form-panel">

            <div class="help-link">
                <span>¿Necesitás ayuda?</span>
                <a href="#">Contactanos</a>
            </div>

            <div class="form-content">

                <div class="form-icon">
                    <svg viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="6" r="3"/>
                        <circle cx="6" cy="18" r="3"/>
                        <path d="m20 4-12 8"/>
                        <path d="m8 12 12 8"/>
                    </svg>
                </div>

                <h2>Bienvenida a Apsy</h2>

                <p class="form-subtitle">
                    Organizá tu peluquería en un solo lugar
                </p>

                <?php if ($error !== ''): ?>
                    <div class="error-message" role="alert">
                        <span class="error-icon">!</span>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">

                    <div class="form-group">
                        <label for="usuario">Usuario</label>

                        <div class="input-container">
                            <input
                                type="text"
                                id="usuario"
                                name="usuario"
                                placeholder="Ingresá tu usuario"
                                value="<?php echo htmlspecialchars($usuario); ?>"
                                autocomplete="username"
                                required
                            >

                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.7"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>

                        <div class="input-container">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Ingresá tu contraseña"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="toggle-password"
                                id="togglePassword"
                                aria-label="Mostrar contraseña"
                            >
                                <svg id="eyeIcon" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor"
                                    stroke-width="1.7" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        Ingresar <span>→</span>
                    </button>

                </form>

                <a href="#" class="forgot-password">
                    ¿Te olvidaste la contraseña?
                </a>

            </div>

            <footer class="login-footer">
                © 2026 Apsy · Todo tu salón, en un solo lugar.
            </footer>

        </section>

    </main>

    <script src="assets/js/login.js"></script>

</body>
</html>
