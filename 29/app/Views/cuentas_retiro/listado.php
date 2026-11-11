<?php $user_session = session(); ?>
<?= $this->extend('template/mesas') ?>
<?= $this->section('title') ?>
LISTADO DE CUENTAS RERIRO DE DINERO
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
                    nombre_cuenta:nombre_cuenta
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