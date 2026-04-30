<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
REPORTE DE VENTAS POR HORA
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <p class="text-center text-primary h3 ">Reporte de ventas </p>
    <div class="card">
        <div class="card-body">
            <div class="container mt-4">

                <form id="formReporteVentas" method="GET" action="<?= base_url('reportes/ventas_mensuales') ?>">
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
                            <button type="submit" class="btn btn-primary w-100">
                                🔍 Consultar
                            </button>
                        </div>

                    </div>
                </form>



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
                    <tbody>
                        <tr>
                            <td>2026-04-01</td>
                            <td>$100.000</td>
                            <td>$19.000</td>
                            <td>$8.000</td>
                            <td>$127.000</td>
                            <td>
                                <button class="btn btn-sm btn-primary">Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2026-04-05</td>
                            <td>$250.000</td>
                            <td>$47.500</td>
                            <td>$20.000</td>
                            <td>$317.500</td>
                            <td>
                                <button class="btn btn-sm btn-primary">Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2026-04-10</td>
                            <td>$80.000</td>
                            <td>$15.200</td>
                            <td>$6.400</td>
                            <td>$101.600</td>
                            <td>
                                <button class="btn btn-sm btn-primary">Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2026-04-12</td>
                            <td>$150.000</td>
                            <td>$28.500</td>
                            <td>$12.000</td>
                            <td>$190.500</td>
                            <td>
                                <button class="btn btn-sm btn-primary">Ver</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2026-04-15</td>
                            <td>$300.000</td>
                            <td>$57.000</td>
                            <td>$24.000</td>
                            <td>$381.000</td>
                            <td>
                                <button class="btn btn-sm btn-primary">Ver</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>



<?= $this->endSection('content') ?>