<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>


<div class="container-fluid">

    <div id="main-content">

    </div>

    <div id="loader-overlay" style="display:none;">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

</div>

<script>
    const BASE_URL = "<?= base_url() ?>";
</script>

<script src="<?= base_url('assets/script/habitaciones/confirmarIngreso.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/seleccionarCliente.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/guardarReserva.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/buscarCliente.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/buscarCiudadProcedencia.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/buscarPlaca.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/buscarCiudadDestino.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/saveHabitacion.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/soloNumeros.js') ?>"></script>
<script src="<?= base_url('assets/script/habitaciones/checkIn.js') ?>"></script>

<script>
    function seleccionarCiudadDestino(id, nombre) {

        document.getElementById('id_destino').value = id
        document.getElementById('destino').value = nombre
        document.getElementById('listaMunicipiosDestino').innerHTML = ""

    }
</script>
<script>
    function seleccionarPlaca(id, nombre) {

        document.getElementById('id_placa').value = id
        document.getElementById('placavehiculo').value = nombre
        document.getElementById('listaVehiculos').innerHTML = ""

    }
</script>
<script>
    function seleccionarCiudadProcedencia(id, nombre) {

        document.getElementById('id_procedencia').value = id
        document.getElementById('procedencia').value = nombre
        document.getElementById('listaMunicipiosProcedencia').innerHTML = ""

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
        let modalEl = document.getElementById('modalReserva');
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