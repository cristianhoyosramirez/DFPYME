<!-- Sidebar -->
<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="#" class="logo">
                <img src="<?= base_url('assets/img/kaiadmin/logo_light1.svg') ?>" alt="Logo" class="navbar-brand" height="30">
            </a>

            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- SECCIÓN PRINCIPAL -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fas fa-chart-pie text-primary"></i>
                    </span>
                    <h4 class="text-section">Reportes</h4>
                </li>

                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link ajax-link"
                        data-title="Dashboard"
                        data-icon="fas fa-chart-pie">
                        <i class="fas fa-tachometer-alt text-info"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- REPORTES -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#movimientosMenu" class="nav-link">
                        <i class="fas fa-file-alt text-warning"></i>
                        <p>Registro</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="movimientosMenu">
                        <ul class="nav nav-collapse">

                            <!-- REPORTE HABITACIONES -->
                            <li>
                                <a href="<?= base_url('reportes/ventas') ?>" class="ajax-link"
                                    data-title="Reporte Habitaciones" data-icon="fas fa-bed">
                                    <i class="fas fa-bed text-primary"></i>
                                    <span class="sub-item">Habitaciones</span>
                                </a>
                            </li>

                            <!-- REPORTE VEHÍCULOS -->
                            <li>
                                <a href="<?= base_url('reportes/vehiculos') ?>" class="ajax-link"
                                    data-title="Reporte Vehículos" data-icon="fas fa-car">
                                    <i class="fas fa-car text-success"></i>
                                    <span class="sub-item">Vehículos</span>
                                </a>
                            </li>

                            <!-- REPORTE RESERVAR -->
                            <li>
                                <a href="<?= base_url('reportes/registro') ?>" class="ajax-link"
                                    data-title="Reporte Reservas" data-icon="fas fa-calendar-check">
                                    <i class="fas fa-calendar-check text-danger"></i>
                                    <span class="sub-item">Reservar</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <!-- CERRAR SESIÓN -->
                <li class="nav-item">
                    <a href="<?= base_url('logout') ?>" class="nav-link">
                        <i class="fas fa-sign-out-alt text-secondary"></i>
                        <p>Cerrar sesión</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->