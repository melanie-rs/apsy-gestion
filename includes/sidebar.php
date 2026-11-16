<aside class="sidebar">

    <!-- Marca -->
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fa-solid fa-scissors"></i>
        </div>

        <div class="brand-info">
            <span class="brand-name">Apsy</span>
            <span class="brand-subtitle">GESTIÓN DE PELUQUERÍA</span>
        </div>
    </div>

    <!-- Navegación -->
    <nav class="sidebar-nav">

        <span class="nav-section-title">MENÚ PRINCIPAL</span>

        <a href="dashboard.php"
           class="nav-link <?php echo $paginaActiva === 'inicio' ? 'active' : ''; ?>">
            <i class="fa-solid fa-house"></i>
            <span>Inicio</span>
        </a>

        <a href="clientes.php"
           class="nav-link <?php echo $paginaActiva === 'clientes' ? 'active' : ''; ?>">
            <i class="fa-solid fa-users"></i>
            <span>Clientes</span>
        </a>

        <a href="profesionales.php"
           class="nav-link <?php echo $paginaActiva === 'profesionales' ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-tie"></i>
            <span>Profesionales</span>
        </a>

        <a href="turnos.php"
           class="nav-link <?php echo $paginaActiva === 'agenda' ? 'active' : ''; ?>">
            <i class="fa-regular fa-calendar-days"></i>
            <span>Agenda de turnos</span>
        </a>

    </nav>

    <!-- Información del salón -->
    <div class="sidebar-bottom">

        <div class="salon-card">
            <div class="salon-icon">
                <i class="fa-solid fa-store"></i>
            </div>

            <div class="salon-info">
                <strong>Estudio Apsy</strong>
                <span>Córdoba</span>
            </div>
        </div>

        <a href="logout.php" class="logout-link">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            <span>Cerrar sesión</span>
        </a>

    </div>

</aside>