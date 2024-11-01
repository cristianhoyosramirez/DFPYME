<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container">

    <div class="card">
        <div class="card-header text-primary">
            CONSULTAS DE MOVIMIENTO DE PRODUCTO
        </div>
        <div class="card-body">

            <input type="hidden" id="url" value="<?php echo base_url() ?>">
            <input type="hidden" value="<?php echo $user_session->id_usuario ?>" id="id_usuario">

            <div class="row mb-3">

                <div class="col-3">
                    <label for="" class="form-label">Movimiento</label>
                    <select name="" class="form-select" id="concepto_busqueda">
                        <option value=""></option>
                        <option value="1">Entradas </option>
                        <option value="2">Salidas </option>
                        <option value="3">entradas y salidas </option>
                        <!-- <?php foreach ($conceptos as $detalle): ?>
                            <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre'] ?></option>
                        <?php endforeach ?> -->
                    </select>
                    <span id="error_concepto_busqueda"></span>
                </div>
                <div class="col-3">
                    <label for="" class="form-label">Producto</label>
                    <input type="hidden" id="id_producto">
                    <input type="text" class="form-control" id="producto" name="producto" autofocus>
                </div>

                <div class="col-3">
                    <div class="row">
                        <div class="col">
                            <label for="" class="form-label">Fecha inicial </label>
                            <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fecha_inicial">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Fecha final </label>
                            <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fecha_final">
                        </div>


                    </div>
                </div>

                <div class="col-3">
                    <label for="" class="form-label text-light">Bu</label>
                    <button type="button" class="btn btn-outline-primary" onclick="buscar_resultados()">Buscar </button>
                </div>


            </div>

            <!-- Contenedor principal -->
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-outline-success me-2">Imprimir</button>
                <button class="btn btn-outline-danger">PDF</button>
            </div>


            <table class="table" id="entradas_salidas">
                <thead class="table-dark">

                    <td>Fecha</td>
                    <td>Movimiento</td>
                    <td>Producto</td>
                    <td>Cantidad inicial</td>
                    <td>Cantidad movimiento </td>
                    <td>Cantidad final </td>
                    <td>Documento </td>
                    <td>Usuario</td>
                    <td>Nota</td>
                </thead>
                <tbody id="res_producto">


                </tbody>
            </table>
        </div>
    </div>


</div>





<?= $this->endSection('content') ?>