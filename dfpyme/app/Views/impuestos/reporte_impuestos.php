<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
Entradas de productos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="col-12">
        <p class="text-center text-primary">Informe de impuestos </p>
        <div class="card">

            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-3">
                        <label for="" class="form-label">Fecha incial </label>
                        <input type="text" class="form-control" id="fecha_inicial" value="<?php echo date('Y-m-d') ?>">
                    </div>
                    <div class="col-3">
                        <label for="" class="form-label">Fecha final </label>
                        <input type="text" class="form-control" id="fecha_inicial" value="<?php echo date('Y-m-d') ?>">
                    </div>
                    <div class="col-3">
                        <label for="" class="form-label text-light">Fecha final </label>
                        <a href="#" class="btn btn-outline-success active w-100">
                            Buscar
                        </a>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">
                        <label for="" class="form-label">Factura inicial</label>
                    </div>
                    <div class="col-3">
                        <label for="" class="form-label">Factura final</label>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <button class="btn btn-outline-success">EXCEL</button>
                    </div>
                </div>


                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Dia </th>
                            <td scope="col">Fecha </th>
                            <td scope="col">Base INC </th>
                            <td scope="col">INC 8 </th>
                            <td scope="col">Base IVA 5 </th>
                            <td scope="col"> IVA 5 </th>
                            <td scope="col">Base 19 </th>
                            <td scope="col">IVA 19 </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                        </tr>


                    </tbody>
                </table>


            </div>
        </div>
    </div>
</div>


<script>

</script>







<?= $this->endSection('content') ?>