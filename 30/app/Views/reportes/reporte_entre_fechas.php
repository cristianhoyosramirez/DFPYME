<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
REPORTE DE VENTAS
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <p class="text-center text-primary h3 ">Reporte de ventas </p>
    <div class="card">
        <div class="card-body">
            <div class="container mt-4">


                <div class="row">

                    <!-- Fecha Inicial -->
                    <div class="col-md-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicial</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <!-- Fecha Final -->
                    <div class="col-md-3">
                        <label for="fecha_fin" class="form-label">Fecha Final</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required value="<?= date('Y-m-d') ?>">
                    </div>


                    <!-- Botón Buscar -->
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" onclick="buscar_ventas_fecha()">
                            🔍 Consultar
                        </button>
                    </div>

                </div>




                <div class="mb-3"></div>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Fecha</th>
                            <td scope="col">Base</th>
                            <td scope="col">IVA</th>
                            <td scope="col">INC</th>
                            <td scope="col">Total</th>
                            <td scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="ventas_fecha">


                        <?= $this->include('reportes/ventas_fechas') ?>


                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>

<script>
    async function buscar_ventas_fecha() {

        const fecha_inicio = document.getElementById('fecha_inicio').value;
        const fecha_fin = document.getElementById('fecha_fin').value;

        if (!fecha_inicio || !fecha_fin) {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Debe seleccionar ambas fechas.'
            });
            return;
        }

        try {

            Swal.fire({
                title: 'Consultando información...',
                html: 'Por favor espere mientras se obtienen los datos.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch('<?= base_url('reportes/buscar_ventas_fecha') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    fecha_inicial: fecha_inicio,
                    fecha_final: fecha_fin
                })
            });

            if (!response.ok) {
                throw new Error('Error en la consulta');
            }

            const data = await response.json();

            Swal.close();

            document.getElementById('ventas_fecha').innerHTML = data.ventas;

            Swal.fire({
                icon: 'success',
                title: 'Consulta realizada',
                text: 'Los resultados se cargaron correctamente.',
                timer: 2000,
                showConfirmButton: false
            });

        } catch (error) {

            Swal.close();

            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible consultar la información.'
            });

        }

    }
</script>



<?= $this->endSection('content') ?>