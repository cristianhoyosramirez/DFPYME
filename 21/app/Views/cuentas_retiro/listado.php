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
            <p class="text-primary h3">LISTA CUENTAS DE RETIRO </p>
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

                    <a href="<?= base_url('devolucion/crear_cuenta'); ?>"
                        class="btn btn-warning ms-auto">
                        <i class="fas fa-plus"></i> Agregar cuenta
                    </a>

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
                <div class="card-header">
                    <h3 class="card-title">Cuentas Asociadas</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless">
                            <thead class="table-dark">
                                <tr>
                                    <td>Cuenta asociada</th>
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



<script>
    async function verCuentasAsociadas(id) {

        try {

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

            if (data.status == true) {
                document.getElementById('listadoCuentasRetiro').innerHTML = data.sub_cuentas
            }

            // Aquí puedes llenar un modal o una tabla
            // document.getElementById('contenido_modal').innerHTML = data.html;

        } catch (error) {

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