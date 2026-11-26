<?php $user_session = session(); ?>
<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
CUENTAS RETIRO DE DINERO
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<!--Sart row-->
<div class="container">
    <div class="row text-center align-items-center flex-row-reverse">
        <div class="col-lg-auto ms-lg-auto">

        </div>
        <div class="col-lg-auto ms-lg-auto">
            <p class="text-primary h3">Cuentas de Retiro </p>
        </div>
        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            <a class="nav-link"><img style="cursor:pointer;" src="<?php echo base_url(); ?>/Assets/img/atras.png" width="20" height="20" onClick="history.go(-1);" title="Sección anterior"></a>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">

        <!-- CUENTAS -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex align-items-center">

                    <h3 class="card-title mb-0">Cuentas</h3>

                    <!--     <a href="<?= base_url('devolucion/crear_cuenta'); ?>"
                        class="btn btn-warning ms-auto">
                        <i class="fas fa-plus"></i> Agregar cuenta
                    </a> -->

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-warning ms-auto" data-bs-toggle="modal" data-bs-target="#crearCuenta">
                        Agregar cuenta
                    </button>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless">
                            <thead class="table-dark">
                                <tr>
                                    <td>Nombre cuenta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?= $this->include('cuentas_retiro/tr_cuentas') ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- CUENTAS ASOCIADAS -->
        <div class="col-md-6">
            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">

                    <h3 class="card-title mb-0" id="nombre_de_cuenta">
                        Cuenta
                    </h3>

                    <div class="d-flex gap-2">


                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless">
                            <thead class="table-dark">
                                <tr>
                                    <td>Sub cuentas </th>
                                </tr>
                            </thead>
                            <tbody id="listadoCuentasRetiro">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
<?= $this->include('cuentas_retiro/modal_editar_cuenta_retiro') ?>

<!-- Modal -->
<div class="modal fade" id="subcuenta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="crearCuenta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Agregar una cuenta </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('devolucion/agregar_cuenta') ?>" method="POST">

                    <input type="hidden"
                        name="usuario_apertura"
                        value="<?= esc($user_session->id_usuario) ?>">

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8 col-lg-7">

                            <div class="mb-3">
                                <label for="nombre_cuenta" class="form-label fw-semibold">
                                    Nombre de la cuenta
                                </label>

                                <input type="text"
                                    class="form-control"
                                    id="nombre_cuenta"
                                    name="nombre_cuenta"
                                    placeholder="Ingrese el nombre de la cuenta"
                                    value="<?= old('nombre_cuenta') ?>"
                                    autofocus
                                    autocomplete="off">

                                <?php if (session('errors.nombre_cuenta')): ?>
                                    <div class="text-danger small mt-1">
                                        <?= session('errors.nombre_cuenta') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="ti ti-plus me-1"></i>
                                    Crear cuenta
                                </button>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAgregarSubCuenta" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarSubCuenta">Agregar sub cuenta </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="idCuenta" hidden>
                <div class="row">
                    <div class="col">
                        <label for="" class="form-lable">Cuenta</label>
                        <input type="text" id="nombreCuenta" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <label for="" class="form-lable">Sub cuenta</label>
                        <input type="text" id="nombreSuCuenta" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-outline-danger">Cancelar </button>
                <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal" onclick="crearSubcuenta()">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="url" value="<?= base_url() ?>">

<script>
    async function eliminarRubro(id) {

        const resultado = await Swal.fire({
            title: '¿Eliminar rubro?',
            text: 'Esta acción eliminará el rubro seleccionado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                actions: 'gap-3',
                confirmButton: 'btn btn-outline-danger px-4',
                cancelButton: 'btn btn-outline-secondary px-4'
            }
        });

        if (!resultado.isConfirmed) return;

        try {

            Swal.fire({
                title: 'Eliminando rubro...',
                text: 'Por favor espere.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            const response = await fetch(`${document.getElementById('url').value}/devolucion/eliminarRubro`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: id
                })
            });

            if (!response.ok) {
                throw new Error('Error en la solicitud al servidor.');
            }

            const data = await response.json();

            if (data.status) {

                await Swal.fire({
                    icon: 'success',
                    title: '¡Eliminado!',
                    text: data.mensaje,
                    timer: 1500,
                    showConfirmButton: false
                });

                // Eliminar fila del listado
                document.getElementById('fila' + id)?.remove();

            } else {

                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: data.mensaje
                });

            }

        } catch (error) {

            console.error('Error:', error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible eliminar el rubro. Inténtelo nuevamente.'
            });
        }
    }
</script>


<script>
    async function editarRubro(id) {

        let nombre = document.getElementById('rubro' + id).value;

        try {

            Swal.fire({
                title: 'Cargando información...',
                text: 'Por favor espere.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            const response = await fetch(`${document.getElementById('url').value}/devolucion/editar_rubro`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: id,
                    nombre: nombre
                })
            });

            if (!response.ok) {
                throw new Error('Error al consultar la información del rubro.');
            }

            const data = await response.json();

            Swal.close();

            if (data.status) {

                console.log(data);

                // Aquí puedes cargar los datos en el formulario de edición.
                // Ejemplo:
                // document.getElementById('nombre_rubro').value = data.rubro.nombre;

            } else {

                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: data.mensaje
                });

            }

        } catch (error) {

            console.error('Error:', error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible consultar la información del rubro.'
            });

        }
    }
</script>






<script>
    async function eliminarCuenta(id_cuenta) {


        try {

            const response = await fetch("<?= base_url('devolucion/eliminarCuenta') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id_cuenta: id_cuenta
                })
            });

            // Validar respuesta HTTP
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const data = await response.json();

            if (data.status === true) {

                Swal.fire({
                    icon: 'success',
                    title: 'Atención',
                    text: 'Creación exitosa.'
                });

                location.reload();



            } else {

                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: data.mensaje ?? 'No fue posible consultar las cuentas asociadas.'
                });
            }

        } catch (error) {

            console.error('Error en crearSubcuenta:', error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible consultar las cuentas asociadas.'
            });
        }
    }
</script>



<script>
    function subCuenta(id_cuenta, cuenta) {

        document.getElementById('idCuenta').value = id_cuenta;
        document.getElementById('nombreCuenta').value = cuenta;

        const modal = new bootstrap.Modal(
            document.getElementById('modalAgregarSubCuenta')
        );

        modal.show();
    }
</script>


<!-- <script>
    async function agregarSubCuenta(id_cuenta) {

        try {

            const response = await fetch(document.getElementById('url').value + '/agregarSubCuenta', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id_cuenta: id_cuenta
                })
            });

            if (!response.ok) {
                throw new Error('Error al consultar la subcuenta');
            }

            const resultado = await response.json();

            console.log(resultado);

            return resultado;

        } catch (error) {

            console.error('Error:', error);
            return null;

        }
    }
</script> -->


<script>
    async function crearSubcuenta() {

        let id_cuenta = document.getElementById('idCuenta').value;
        let sub_cuenta = document.getElementById('nombreSuCuenta').value;

        try {

            const response = await fetch("<?= base_url('devolucion/agregar_rubro') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id_cuenta: id_cuenta,
                    sub_cuenta: sub_cuenta
                })
            });

            // Validar respuesta HTTP
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const data = await response.json();

            if (data.status === true) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Creación exitosa.'
                });

                document.getElementById('listadoCuentasRetiro').innerHTML = data.sub_cuentas;
                document.getElementById('nombre_de_cuenta').innerHTML = data.nombre_cuenta;



            } else {

                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: data.mensaje ?? 'No fue posible consultar las cuentas asociadas.'
                });
            }

        } catch (error) {

            console.error('Error en crearSubcuenta:', error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible consultar las cuentas asociadas.'
            });
        }
    }
</script>


<script>
    async function verCuentasAsociadas(id) {

        try {

            // Mostrar spinner
            Swal.fire({
                title: 'Consultando cuentas',
                text: 'Por favor, espere...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch("<?= base_url('devolucion/rubros_listado') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id
                })
            });

            const data = await response.json();

            // Cerrar spinner
            Swal.close();

            if (data.status == true) {

                document.getElementById('listadoCuentasRetiro').innerHTML = data.sub_cuentas;
                document.getElementById('idCuenta').value = data.id;

            }

        } catch (error) {

            // Cerrar spinner
            Swal.close();

            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible consultar las cuentas asociadas'
            });

        }

    }
</script>

<script>
    async function editarCuenta(id) {

        let nombre_cuenta = document.getElementById(id + 'nombre_cuenta').value;

        try {

            // Mostrar spinner
            Swal.fire({
                title: 'Editando cuenta',
                text: 'Por favor, espere...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch("<?= base_url('devolucion/editar_cuenta') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id,
                    nombre_cuenta: nombre_cuenta
                })
            });

            const data = await response.json();

            // Cerrar spinner
            Swal.close();

            if (data.status == true) {

                document.getElementById('listadoCuentasRetiro').innerHTML = data.sub_cuentas;
                document.getElementById('idCuenta').value = data.id;

            }

        } catch (error) {

            // Cerrar spinner
            Swal.close();

            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible consultar las cuentas asociadas'
            });

        }

    }
</script>

<?= $this->endSection('content') ?>
<!-- end row -->