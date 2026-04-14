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

                <!-- 🔵 OPERACIÓN -->
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fas fa-hotel text-primary"></i>
                    </span>
                    <h4 class="text-section">Operación</h4>
                </li>

                <!-- RESERVAS -->
                <li class="nav-item">
                    <a href="<?= base_url('reservas/gestion') ?>" class="nav-link ajax-link"
                        data-title="Reservas"
                        data-icon="fas fa-calendar-check">
                        <i class="fas fa-calendar-check text-success"></i>
                        <p>Reservas</p>
                    </a>
                </li>

                <!-- CHECK-IN -->
                <li class="nav-item">
                    <a href="<?= base_url('reportes/registro') ?>" class="nav-link ajax-link"
                        data-title="Check-in"
                        data-icon="fas fa-book-open">

                        <i class="fas fa-book-open text-primary"></i>
                        <p>Registro</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#reportesMenu" class="nav-link">
                        <i class="fas fa-chart-bar text-warning"></i>
                        <p>Configuración</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="reportesMenu">
                        <ul class="nav nav-collapse">

                            <li>
                                <a href="<?= base_url('reportes/vehiculos') ?>" class="ajax-link">
                                    <i class="fas fa-car"></i>
                                    <span class="sub-item">Vehículos</span>
                                </a>
                            </li>

                            <li>
                                <a href="<?= base_url('reportes/ventas') ?>" class="ajax-link">
                                    <i class="fas fa-bed"></i>
                                    <span class="sub-item">Habitaciones</span>
                                </a>
                            </li>

                            <li>
                                <a href="<?= base_url('reportes/registro') ?>" class="ajax-link">
                                    <i class="fas fa-book"></i>
                                    <span class="sub-item">Hospedaje</span>
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