<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">

    <div class="d-flex justify-content-end my-3">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Nueva actualización
        </button>
    </div>


    <div class="card shadow-sm border-0 rounded-4 my-3">
        <div class="card-header text-center">
            Lista de actualizaciones disponibles
        </div>
        <div class="card-body">
            <input type="text" id="url" value="<?php echo base_url() ?>" hidden>
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header bg-dark text-white rounded-top-4">
                    <h5 class="mb-0">Historial de Actualizaciones</h5>
                </div>
                <div class="card-body p-0">
                  <?= $this->include('actualizaciones/tabla_actualizaciones') ?>
                </div>
            </div>

        </div>
    </div>


</div>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Actualizaciones disponibles </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingrese el nombre">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" placeholder="Ingrese la descripción" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" onclick="crearVersion()">Crear </button>
                <button type="button" class="btn btn-outline-danger">Cancelar </button>
            </div>
        </div>
    </div>
</div>

  <!-- Sweet alert -->
    <script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>

<script src="<?= base_url() ?>/Assets/script_js/actualizaciones/generarActualizaion.js"></script>


<!-- <script>
    async function generarActulizacion(actualizacion) {



        try {
            // Mostrar SweetAlert con spinner
            Swal.fire({
                title: 'Aplicando cambios...',
                html: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const url = document.getElementById('url').value.trim();
            const response = await fetch(url + '/actualizacion/crearVersion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    actualizacion: actualizacion,
                })
            });

            const data = await response.json();

            if (data.response == "success") {
                // Cerrar spinner y mostrar éxito
                Swal.fire({
                    title: 'Cambios aplicados',
                    text: 'La versión se guardó correctamente',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                // Aquí puedes cerrar modal, limpiar formulario, etc.
            } else {
                // Error del servidor (respuesta no OK)
                Swal.fire('Error', data.mensaje || 'No se pudo crear la versión', 'error');
            }

        } catch (error) {
            console.error('Error en crearVersion:', error);
            Swal.fire('Error', 'Hubo un problema de red o del servidor', 'error');
        }

    }
</script> -->

<script>
    async function crearVersion() {
        const nombre = document.getElementById('nombre').value.trim();
        const descripcion = document.getElementById('descripcion').value.trim();
        const url = document.getElementById('url').value.trim();

        if (!nombre) {
            Swal.fire('Falta el nombre', 'Por favor ingresa un nombre', 'warning');
            return;
        }

        try {
            const response = await fetch(url + '/actualizacion/crearVersion', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre: nombre,
                    descripcion: descripcion
                })
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire('Versión creada', 'La versión se guardó correctamente', 'success');
                // Aquí podrías cerrar el modal, limpiar el formulario, etc.
            } else {
                Swal.fire('Error', data.mensaje || 'No se pudo crear la versión', 'error');
            }
        } catch (error) {
            console.error('Error en crearVersion:', error);
            Swal.fire('Error', 'Hubo un problema de red o del servidor', 'error');
        }
    }
</script>


<?= $this->endSection('content') ?>