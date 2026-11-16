<header class="topbar">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <span>Estudio Apsy</span>
        <i class="fa-solid fa-chevron-right"></i>

        <span class="breadcrumb-current">
            <?php
            $nombresPaginas = [
                'inicio' => 'Inicio',
                'clientes' => 'Clientes',
                'profesionales' => 'Profesionales',
                'agenda' => 'Agenda de turnos'
            ];

            echo htmlspecialchars(
                $nombresPaginas[$paginaActiva] ?? 'Inicio'
            );
            ?>
        </span>
    </div>

    <!-- Acciones y perfil -->
    <div class="topbar-actions">

        <!-- Notificaciones (visual, sin funcionalidad por ahora) -->
        <button type="button"
                class="notification-button"
                aria-label="Notificaciones">
            <i class="fa-regular fa-bell"></i>
            <span class="notification-dot"></span>
        </button>

        <!-- Perfil del usuario -->
        <div class="user-profile">

            <div class="user-avatar">
                <?php
                $iniciales = strtoupper(
                    substr($_SESSION['nombre'] ?? '', 0, 1) .
                    substr($_SESSION['apellido'] ?? '', 0, 1)
                );

                echo htmlspecialchars($iniciales);
                ?>
            </div>

            <div class="user-info">
                <strong>
                    <?php
                    echo htmlspecialchars(
                        ($_SESSION['nombre'] ?? '') . ' ' .
                        ($_SESSION['apellido'] ?? '')
                    );
                    ?>
                </strong>

                <span>
                    <?php
                    echo htmlspecialchars(
                        ucfirst($_SESSION['rol'] ?? 'Usuario')
                    );
                    ?>
                </span>
            </div>

        </div>

    </div>

</header