<!-- Sidebar -->
<div class="sidebar sidebar-style-2" data-background-color="dark">
	<div class="sidebar-logo">
		<div class="logo-header" data-background-color="dark">
			<a href="/" class="logo">
				<img src="assets/img/kaiadmin/logo_light.svg" alt="Logo" class="navbar-brand" height="20">
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

				<!-- INVENTARIO -->
				<li class="nav-section">
					<span class="sidebar-mini-icon">
						<i class="fa fa-box"></i>
					</span>
					<h4 class="text-section">Inventario</h4>
				</li>

				<li class="nav-item">
					<a href="<?= base_url('categorias') ?>"
						class="nav-link ajax-link"
						data-title="Gestión de categorías"
						data-icon="fas fa-tags">

						<i class="fas fa-tags"></i>

						<p>Categorías</p>
						
					</a>
				</li>

				<li class="nav-item">
					<a href="<?= base_url('productos') ?>" class="nav-link ajax-link">
						<i class="fas fa-boxes"></i>
						<p>Productos</p>
					</a>
				</li>


				<!-- VENTAS -->
				<li class="nav-section">
					<span class="sidebar-mini-icon">
						<i class="fa fa-shopping-cart"></i>
					</span>
					<h4 class="text-section">Ventas</h4>
				</li>

				<li class="nav-item active">
					<a href="/pos" class="nav-link ">
						<i class="fas fa-shopping-cart"></i>
						<p>Punto de Venta (POS)</p>
					</a>

				</li>

			

				<li class="nav-item">
					<a href="/clientes" class="nav-link">
						<i class="fas fa-user-friends"></i>
						<p>Clientes</p>
					</a>
				</li>

				<!-- CAJA -->
				<li class="nav-section">
					<span class="sidebar-mini-icon">
						<i class="fa fa-coins"></i>
					</span>
					<h4 class="text-section">Caja</h4>
				</li>

				<li class="nav-item submenu ">
					<a  href="#menuCaja">
						<i class="fas fa-coins"></i>
						<p>Gestión de Caja</p>
						<span class="caret"></span>
					</a>
					<div class="collapse show" id="menuCaja">
						<ul class="nav nav-collapse">
							<li >
								<a href="/caja/apertura">
									<span class="sub-item">Apertura de Caja</span>
								</a>
							</li>
							<li>
								<a href="/caja/cierre">
									<span class="sub-item">Cierre de Caja</span>
								</a>
							</li>
							<li>
								<a href="/caja/movimientos">
									<span class="sub-item">Movimientos</span>
								</a>
							</li>
						</ul>
					</div>
				</li>

				<!-- REPORTES -->
				<li class="nav-section">
					<span class="sidebar-mini-icon">
						<i class="fa fa-chart-bar"></i>
					</span>
					<h4 class="text-section">Reportes</h4>
				</li>

				<li class="nav-item">
					<a href="/reportes/ventas" class="nav-link">
						<i class="fas fa-chart-line"></i>
						<p>Reporte de Ventas</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="/reportes/inventario" class="nav-link">
						<i class="fas fa-warehouse"></i>
						<p>Reporte de Inventario</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="/reportes/caja" class="nav-link">
						<i class="fas fa-balance-scale"></i>
						<p>Reporte de Caja</p>
					</a>
				</li>

				<!-- CONFIGURACIÓN -->
				<li class="nav-section">
					<span class="sidebar-mini-icon">
						<i class="fa fa-cog"></i>
					</span>
					<h4 class="text-section">Configuración</h4>
				</li>

				<li class="nav-item">
					<a href="/configuracion/empresa" class="nav-link">
						<i class="fas fa-building"></i>
						<p>Empresa</p>
					</a>
				</li>

				<li class="nav-item">
					<a href="/configuracion/usuarios" class="nav-link">
						<i class="fas fa-users-cog"></i>
						<p>Usuarios y Roles</p>
					</a>
				</li>

			</ul>
		</div>
	</div>
</div>
<!-- End Sidebar -->