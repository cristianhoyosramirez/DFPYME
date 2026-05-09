<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>


<div class="container-fluid">

    <div id="main-content">

    </div>

    <div id="loader-overlay" style="display:none;">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

</div>

<?= $this->include('habitaciones/modalReserva') ?>

<script>
    const BASE_URL = "<?= base_url() ?>";
</script>

<script src="<?= base_url('assets/script/habitaciones/checkin_reserva.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/seleccionarCliente.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/guardarReserva.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/buscarCliente.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/buscarCiudadProcedencia.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/buscarPlaca.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/buscarCiudadDestino.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/saveHabitacion.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/soloNumeros.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/checkIn.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/seleccionarCiudadDestino.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/seleccionarPlaca.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/seleccionarCiudadProcedencia.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/confirmar_reserva.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/procesar_confirmacion.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/generarReserva.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/nuevaReserva.js') ?>"></script>
<script src="<?= base_url('assets/script/reservas/cancelar_reserva.js') ?>"></script>
<script src="<?= base_url('assets/script/reservas/buscarHabitacion.js') ?>"></script>
<script src="<?= base_url('assets/script/reservas/buscarHabitacionFecha.js') ?>"></script>

<?= $this->include('reservas/modalConfirmarReserva'); ?>
<?= $this->include('reservas/modalCheckIn'); ?>
<?= $this->include('reservas/modalConfirmarIngreso'); ?>
<?= $this->include('habitaciones/modalNuevaReserva'); ?>


<!-- Modal -->
<div class="modal fade" id="modalEditarReserva" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloReserva">Datos reserv</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistroHuesped">

                    <!-- IDs ocultos -->
                    <input type="hidden" id="id_de_habitacion" name="id_de_habitacion">
                    <input type="hidden" id="id_habitacion_reserva" name="id_habitacion_reserva">
                    <input type="hidden" id="id_cliente" name="id_cliente">
                    <input type="hidden" id="id_reserva_edicion" name="id_reserva_edicion">

                    <div class="card-body p-4">

                        <div class="row g-3">

                            <!-- =========================
        FILA 1
    ========================== -->

                            <!-- Fecha -->
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small">
                                    Fecha reserva
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>

                                    <input type="date"
                                        class="form-control"
                                        name="fecha_reserva"
                                        id="fechaReserva"
                                        required>
                                </div>
                            </div>

                            <!-- Habitación -->
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small">
                                    Habitación
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-bed"></i>
                                    </span>

                                    <input type="text"
                                        class="form-control bg-light"
                                        name="habitacion_reserva"
                                        id="habitacionReserva"
                                        readonly>
                                </div>
                            </div>

                            <!-- Valor -->
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small">
                                    Valor hospedaje
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-dollar-sign"></i>
                                    </span>

                                    <input type="text"
                                        class="form-control"
                                        name="valor_hospedaje"
                                        id="valorHospedaje"
                                        inputmode="numeric"
                                        placeholder="0"
                                        oninput="formatCurrency(this)"
                                        required>
                                </div>
                            </div>

                            <!-- =========================
        FILA 2
    ========================== -->

                            <!-- Huésped -->
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small">
                                    Huésped
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>

                                    <input type="text"
                                        class="form-control bg-light"
                                        id="nombreCompleto"
                                        readonly>
                                </div>
                            </div>

                            <!-- Vehículo -->
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small">
                                    Vehículo
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-car"></i>
                                    </span>

                                    <input type="text"
                                        class="form-control text-uppercase"
                                        name="placaVehiculo"
                                        id="placaVehiculoEditar"
                                        placeholder="ABC123">

                                    <span class="input-group-text">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                </div>
                            </div>



                            <!-- =========================
        FILA 3
    ========================== -->

                            <!-- Procedencia -->
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small">
                                    Procedencia
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>

                                    <input type="text"
                                        class="form-control"
                                        id="procedenciaEditar"
                                        name="procedencia"
                                        placeholder="Ciudad de origen">

                                    <button type="button"
                                        class="btn btn-outline-secondary">

                                        <i class="fas fa-times"></i>

                                    </button>

                                </div>
                            </div>

                            <!-- Destino -->
                            <div class="col-12 col-md-3">
                                <label class="form-label fw-semibold small">
                                    Destino
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="fas fa-map-pin"></i>
                                    </span>

                                    <input type="text"
                                        class="form-control"
                                        id="destinoEditar"
                                        name="destino"
                                        placeholder="Ciudad destino">

                                    <button type="button"
                                        class="btn btn-outline-secondary">

                                        <i class="fas fa-times"></i>

                                    </button>

                                </div>
                            </div>

                            <!-- =========================
        FILA 4
    ========================== -->

                            <!-- Notas -->
                            <div class="col-3">

                                <label class="form-label fw-semibold small">
                                    Notas / Observaciones
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text align-items-start pt-2">
                                        <i class="fas fa-sticky-note"></i>
                                    </span>

                                    <textarea class="form-control"
                                        rows="3"
                                        id="notasReserva"
                                        name="notas_reserva"
                                        placeholder="Comentarios adicionales de la reserva..."></textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Aceptar</button>

            </div>
        </div>
    </div>
</div>




<script>
    async function editarReserva(id_reserva) {

        try {

            const response = await fetch('<?= base_url('habitaciones/datosReserva') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_reserva: id_reserva
                })
            });

            const result = await response.json();

            if (result.response === 'ok') {

                // Ejemplo de llenado de campos
                document.getElementById('fechaReserva').value = result.fecha;
                document.getElementById('habitacionReserva').value = result.habitacion;
                document.getElementById('valorHospedaje').value = result.precio;
                document.getElementById('placaVehiculoEditar').value = result.vehiculo;
                document.getElementById('nombreCompleto').value =
                    result.numero_documento + ' / ' + result.nombres;
                document.getElementById('procedenciaEditar').value = result.origen;
                document.getElementById('destinoEditar').value = result.destino;
                document.getElementById('notasReserva').value = result.notas;
                document.getElementById('tituloReserva').innerHTML = result.numero_reserva;

                // Abrir modal después de cargar datos
                const modal = new bootstrap.Modal(
                    document.getElementById('modalEditarReserva')
                );

                modal.show();

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message
                });

            }

        } catch (error) {

            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un problema al consultar la reserva'
            });

        }

    }
</script>








<script>
    async function actualizar_fecha_reserva(id_reserva, fecha_reserva) {

        try {

            if (!fecha_reserva) {

                Swal.fire({
                    icon: 'warning',
                    text: 'Debe seleccionar una fecha'
                });

                return;
            }

            // Spinner SweetAlert
            Swal.fire({
                title: 'Actualizando fecha',
                text: 'Espere un momento...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch('<?= base_url('reservas/actualizarFechaReserva') ?>', {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify({
                    id_reserva: id_reserva,
                    fecha_reserva: fecha_reserva
                })

            });

            const data = await response.json();

            // Cerrar spinner
            Swal.close();

            if (data.success == 'ok') {

                Swal.fire({
                    icon: 'success',
                    title: 'Fecha actualizada',
                    timer: 1500,
                    showConfirmButton: false
                });

            } else {

                Swal.fire({
                    icon: 'error',
                    text: data.message || 'No fue posible actualizar'
                });

            }

        } catch (error) {

            // Cerrar spinner si ocurre error
            Swal.close();

            console.error(error);

            Swal.fire({
                icon: 'error',
                text: 'Ocurrió un error al actualizar'
            });

        }

    }
</script>



<script>
    async function cambiarEstado(id_estado) {
        try {
            const response = await fetch('<?= base_url('reservas/busquedaPorEstado') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_estado: id_estado
                })
            });

            const result = await response.json();

            if (result.success === true) {

                document.getElementById('reservasHabitaciones').innerHTML = result.reservas;

            } else {
                alert(result.message || 'Error al consultar');
            }

        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>


<script>
    async function eliminar_reserva(id_reserva) {

        const confirmacion = await Swal.fire({
            title: '¿Eliminar reserva?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (!confirmacion.isConfirmed) return;

        try {

            Swal.fire({
                title: 'Eliminando...',
                html: 'Procesando solicitud',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch('<?= base_url('reservas/eliminar') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_reserva: id_reserva
                })
            });

            const data = await response.json();

            if (data.status === 'ok') {

                Swal.fire({
                    icon: 'success',
                    title: 'Eliminada',
                    text: 'La reserva fue eliminada correctamente',
                    timer: 1500,
                    showConfirmButton: false
                });

                // 🔥 quitar fila de la tabla sin recargar
                const fila = document.getElementById('reserva' + id_reserva);
                if (fila) fila.remove();

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo eliminar'
                });

            }

        } catch (error) {

            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'Intenta nuevamente'
            });

            console.error(error);
        }
    }
</script>

<script>
    let timersNotas = {};

    function setEstado(id, tipo, texto, mostrar = true) {
        const el = document.getElementById('estadoNota' + id);

        if (!el) return;

        el.classList.remove('guardando', 'guardado', 'error', 'd-none');
        el.classList.add(tipo);
        el.innerHTML = texto;

        if (mostrar) {
            el.classList.remove('d-none');
        }
    }

    function ocultarEstado(id) {
        const el = document.getElementById('estadoNota' + id);
        if (el) el.classList.add('d-none');
    }

    function autoGuardarNota(id) {

        const textarea = document.getElementById('nota' + id);
        const valor = textarea.value.trim();

        const estado = document.getElementById('estadoNota' + id);

        // 🧠 Si está vacío: ocultar estado y no guardar
        if (valor.length === 0) {
            clearTimeout(timersNotas[id]);
            ocultarEstado(id);
            return;
        }

        // ✏️ escribiendo
        setEstado(id, 'guardando', '✏️ Escribiendo...');

        // ⏳ debounce
        clearTimeout(timersNotas[id]);

        timersNotas[id] = setTimeout(() => {

            setEstado(id, 'guardando', '💾 Guardando...');

            fetch('<?= base_url('reservas/actualizarNota') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id_reserva: id,
                        notas: valor
                    })
                })
                .then(res => res.json())
                .then(data => {

                    if (data.status === 'ok') {

                        setEstado(id, 'guardado', '✔ Guardado');

                        // 🔥 ocultar después de 1.5s para limpieza visual
                        setTimeout(() => {
                            ocultarEstado(id);
                        }, 1500);

                    } else {
                        setEstado(id, 'error', '❌ Error al guardar');
                    }

                })
                .catch(() => {
                    setEstado(id, 'error', '❌ Error de conexión');
                });

        }, 800); // espera 0.8s sin escribir
    }
</script>


<script>
    function modalNuevaReserva() {


        const modalEl = document.getElementById('modalNuevaReserva');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

    }
</script>




<script>
    function cancelarHabitacion() {
        // Selecciona el modal y ciérralo
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalHabitacion'));
        if (modal) modal.hide();

        // Limpia el formulario
        const form = document.getElementById('formHabitacion');
        if (form) form.reset();
    }
</script>


<script>
    function limpiarHuesped() {
        document.getElementById('huesped').value = '';
        document.getElementById('listaClientes').innerHTML = '';

    }
</script>



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
                body: JSON.stringify({
                    tipo,
                    placa
                })
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

                document.getElementById('tablaVehiculos').innerHTML = result.vehiculos


            } else {
                Swal.fire('Error', result.message, 'error');
            }

        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Ocurrió un error al guardar el vehículo', 'error');
        }
    }
</script>


<script>
    // Función para abrir el modal y asignar valores
    function abrirModalReserva(id, nombre) {
        document.getElementById('id_de_habitacion').value = id;
        document.getElementById('nombre_habitacion').value = nombre;
        document.getElementById('nombre_habitacion').value = nombre;

        let modalEl = document.getElementById('modalReserva');
        let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.show();
    }

    // Función para cerrar modal y resetear todo el formulario
    function cerrarModalHuesped() {
        // Cerrar modal
        let modalEl = document.getElementById('modalNuevaReserva');
        let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.hide();

        // Resetear formulario
        const form = document.getElementById('formRegistroHuesped');
        form.reset(); // limpia inputs normales

        // Limpiar campos readonly o hidden
        document.getElementById('nombre_completo').value = '';
        document.getElementById('id_cliente').value = '';
        document.getElementById('id_de_habitacion').value = '';
        document.getElementById('nombre_habitacion').value = '';
        document.getElementById('observaciones').value = '';

        // Limpiar lista de clientes si hay autocompletado
        document.getElementById('listaClientes').innerHTML = '';
    }
</script>





<?= $this->endSection() ?>