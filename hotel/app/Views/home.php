<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    #loader-overlay {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        transition: opacity 0.3s ease;
    }

    .loader-hidden {
        opacity: 0;
        pointer-events: none;
    }

    .spinner {
        width: 45px;
        height: 45px;
        border: 4px solid #dee2e6;
        border-top: 4px solid #0d6efd;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<div class="container-fluid">

    <div id="main-content">

    </div>

    <div id="loader-overlay" style="display:none;">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

</div>




<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {

        const content = document.getElementById("main-content");

        function showLoader() {
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function hideLoader() {
            Swal.close();
        }

        async function loadPage(url, pushState = true) {
            try {
                showLoader();

                const response = await fetch(url, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                if (!response.ok) {
                    throw new Error("Error en la carga");
                }

                const html = await response.text();

                content.style.opacity = 0;

                setTimeout(() => {
                    content.innerHTML = html;
                    content.style.transition = "opacity 0.3s ease";
                    content.style.opacity = 1;
                }, 150);

                if (pushState) {
                    history.pushState({
                        url
                    }, "", url);
                }

            } catch (error) {
                content.innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar la vista.
                </div>
            `;
            } finally {
                hideLoader();
            }
        }

        document.addEventListener("click", function(e) {
            const link = e.target.closest(".ajax-link");
            if (link) {
                e.preventDefault();
                const url = link.getAttribute("href");
                loadPage(url);
            }
        });

        window.addEventListener("popstate", function(e) {
            if (e.state && e.state.url) {
                loadPage(e.state.url, false);
            }
        });

    });
</script> -->

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const content = document.getElementById("main-content");

        /* =========================
           LOADER
        ========================== */
        function showLoader() {
            Swal.fire({
                title: 'Cargando...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
        }

        function hideLoader() {
            Swal.close();
        }

        /* =========================
           INICIALIZADOR DE VISTAS
        ========================== */
        function initView(url) {
            if (url.includes("reportes/ventas") && typeof initVentas === "function") {
                initVentas();
            }
            // Agrega más inicializadores según tus vistas:
            // if (url.includes("clientes")) initClientes();
        }

        /* =========================
           CARGA AJAX
        ========================== */
        async function loadPage(url) {
            try {
                showLoader();

                const response = await fetch(url, {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                if (!response.ok) throw new Error("Error en la carga");

                const html = await response.text();

                content.style.opacity = 0;

                setTimeout(() => {
                    content.innerHTML = html;
                    content.style.transition = "opacity 0.3s ease";
                    content.style.opacity = 1;

                    // Inicializa scripts de la vista
                    initView(url);
                }, 150);

            } catch (error) {
                content.innerHTML = `
                <div class="alert alert-danger">
                    Error al cargar la vista.
                </div>
            `;
                console.error(error);
            } finally {
                hideLoader();
            }
        }

        /* =========================
           CLICK EN LINKS AJAX
        ========================== */
        document.addEventListener("click", function(e) {
            const link = e.target.closest(".ajax-link");
            if (!link) return;

            e.preventDefault();
            const url = link.getAttribute("href");
            loadPage(url); // ⚡ Siempre AJAX, nunca cambia la URL
        });

        /* =========================
           PRIMERA CARGA
        ========================== */
        initView(window.location.pathname);

    });
</script>


<script>
    async function saveHabitacion() {
        const formHabitacion = document.getElementById('formHabitacion');
        const btnGuardar = document.getElementById('btnGuardarHabitacion');

        // Crear objeto con los datos del formulario
        const formData = new FormData(formHabitacion);
        const data = {};
        formData.forEach((value, key) => data[key] = value.trim());

        // Validaciones básicas
        if (!data.numero || !data.tipo || !data.capacidad || !data.precio || !data.estado) {
            Swal.fire('Error', 'Por favor complete todos los campos', 'warning');
            return;
        }

        try {
            // Deshabilitar botón mientras se procesa
            btnGuardar.disabled = true;

            // Mostrar loader
            Swal.fire({
                title: 'Creando habitación...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Llamada al backend
            const response = await fetch('<?= base_url() ?>/habitaciones/crear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            Swal.close();
            btnGuardar.disabled = false;

            if (result.success) {
                // Mostrar mensaje de éxito
                Swal.fire('¡Éxito!', 'Habitación creada correctamente', 'success');

                // Limpiar formulario
                formHabitacion.reset();

                // Cerrar el modal
                const modalEl = document.getElementById('modalHabitacion');
                const modal = bootstrap.Modal.getInstance(modalEl); // Obtener instancia existente
                if (modal) modal.hide();

                // Actualizar la tabla
                const tbody = document.getElementById('tablaHabitaciones');
                tbody.innerHTML = ''; // Limpiar contenido actual

                result.habitaciones.forEach(habitacion => {
                    const tbody = document.getElementById('tablaHabitaciones');
                    tbody.innerHTML = ''; // Limpiar contenido actual

                    result.habitaciones.forEach(habitacion => {
                        const tr = document.createElement('tr');

                        tr.innerHTML = `
        <td>${habitacion.nombre_mesa}</td>
        <td>${habitacion.tipo}</td>
        <td>${habitacion.capacidad}</td>
        <td>${Number(habitacion.precio).toLocaleString('es-CO', { minimumFractionDigits: 0 })}</td>
        <td>
            ${habitacion.estado_mesa == 0 
                ? '<span class="badge bg-success">Disponible</span>' 
                : '<span class="badge bg-danger">Ocupada</span>'}
        </td>
        <td>
            <!-- Reservar -->
            <button class="btn btn-primary btn-sm" title="Reservar habitación" onclick="reservar(${habitacion.id_mesa})">
                <i class="fas fa-bed"></i>
            </button>

            <!-- Editar -->
            <button class="btn btn-warning btn-sm" title="Editar habitación" onclick="editar(${habitacion.id_mesa})">
                <i class="fas fa-edit"></i>
            </button>

            <!-- Eliminar -->
            <button class="btn btn-danger btn-sm" title="Eliminar habitación" onclick="eliminar(${habitacion.id_mesa})">
                <i class="fas fa-trash-alt"></i>
            </button>

            <!-- Ver detalles -->
            <button class="btn btn-info btn-sm" title="Ver detalles de la habitación" onclick="verDetalles(${habitacion.id_mesa})">
                <i class="fas fa-eye"></i>
            </button>
        </td>
    `;

                        tbody.appendChild(tr);
                    });
                });
            } else {
                Swal.fire('Error', result.message || 'Ocurrió un error al crear la habitación', 'error');
            }

        } catch (error) {
            Swal.close();
            btnGuardar.disabled = false;
            Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            console.error(error);
        }
    }
</script>


<script>
async function agregarVehiculo() {
    const tipo = document.getElementById('tipo').value;
    const placa = document.getElementById('placa').value;
    const formVehiculo = document.getElementById('formVehiculo');

    if (!tipo || !placa) {
        Swal.fire('Error', 'Todos los campos son obligatorios', 'warning');
        return;
    }

    try {
        // Enviar datos al servidor
        const response = await fetch('<?= base_url("habitaciones/crearVehiculo") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ tipo, placa })
        });

        const result = await response.json();

        if (result.success) {
            // Mostrar mensaje de éxito
            Swal.fire('¡Éxito!', result.message, 'success');

            // Limpiar formulario
            formVehiculo.reset();

            // Cerrar modal
            const modalEl = document.getElementById('modalVehiculo');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            // Actualizar tabla
            const tbody = document.getElementById('tablaVehiculos');
            tbody.innerHTML = ''; // Limpiar tabla

            result.vehiculos.forEach(vehiculo => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${vehiculo.tipo}</td>
                    <td>${vehiculo.placa}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" title="Editar vehículo" onclick="editarVehiculo(${vehiculo.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" title="Eliminar vehículo" onclick="eliminarVehiculo(${vehiculo.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                        <button class="btn btn-info btn-sm" title="Ver detalles del vehículo" onclick="verDetallesVehiculo(${vehiculo.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

        } else {
            Swal.fire('Error', result.message, 'error');
        }

    } catch (error) {
        console.error(error);
        Swal.fire('Error', 'Ocurrió un error al guardar el vehículo', 'error');
    }
}
</script>





<?= $this->endSection() ?>