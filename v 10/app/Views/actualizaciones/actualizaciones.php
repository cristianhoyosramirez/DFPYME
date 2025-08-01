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
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <td>Fecha de lanzamiento</th>
                        <td>Version </th>
                        <td>Descripión</th>
                        <td>Accion </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2025-07-29</td>
                        <td>10</td>
                        <td>Descripcion</td>
                        <td>
                            <button class="btn btn-icon btn-outline-success" title="Generar actulización" onclick="generarActulizacion(10)" >
                                <!-- Download SVG icon from http://tabler-icons.io/i/player-play -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 4v16l13 -8z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
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

<script>
    async function generarActulizacion(actulizacion) {
        
       

        try {
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