<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<div class="container">
    <div class="my-2">
        <div class="d-flex justify-content-between align-items-center">
            <p class="text-center w-100 m-0">Cruce de inventario</p>
            <div class="d-flex ms-3">
                <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_cruce_inventarios">
                    <button class="btn btn-outline-success ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Exportar a excel">Excel</button>
                </form>
                <!--  <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_sobrantes">
                    <button class="btn btn-outline-primary ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver sobrantes" data-bs-toggle="modal" data-bs-target="#sobrantes">Sobrantes</button>
             </form> -->
                <button type="button" class="btn btn-outline-primary d-flex ms-3" data-bs-toggle="modal" data-bs-target="#sobrantes">
                    Sobrantes
                </button>
                <button type="button" class="btn btn-outline-danger d-flex ms-3" data-bs-toggle="modal" data-bs-target="#faltantes">
                    faltantes
                </button>
                <!--  <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_faltantes">
                    <button class="btn btn-outline-danger ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver faltantes">Faltantes</button>
                </form> -->
            </div>
        </div>
    </div>

    <div class="card">
        <div class="car-body">

            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <td scope="col">Codigo </td>
                        <td scope="col">Producto </td>
                        <td scope="col">Cantidad conteo </td>
                        <td scope="col">Cantidad sistema </td>
                        <td scope="col">Diferencia inventario </td>
                        <td scope="col">Valor costo </td>
                        <td scope="col">Valor venta </td>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($conteo_manual)): ?>
                        <?php foreach ($inventario_sistema as $KeyInventarioFisico): ?>

                            <?php $producto = model('inventarioModel')->conteo_manual($KeyInventarioFisico['codigointernoproducto']); //d($producto);
                            ?>

                            <?php if (!empty($producto)): ?>

                                <tr>
                                    <td><?php echo $producto[0]['codigointernoproducto'];  ?></td>
                                    <td><?php echo $producto[0]['nombreproducto'];  ?></td>
                                    <td><?php echo $producto[0]['cantidad_inventario_fisico'];  ?></td>
                                    <td><?php echo $producto[0]['cantidad_inventario'];  ?></td>
                                    <td><?php echo $producto[0]['diferencia'];  ?></td>
                                    <td><?php echo number_format($producto[0]['valor_costo'], 0, ",", ".");  ?></td>
                                    <td><?php echo number_format($producto[0]['valor_venta'], 0, ",", ".");  ?></td>
                                </tr>

                            <?php endif ?>
                            <?php if (empty($producto)): ?>

                                <?php $dato_producto = model('inventarioModel')->getProducto($KeyInventarioFisico['codigointernoproducto']); ?>
                                <tr>
                                    <td><?php echo $KeyInventarioFisico['codigointernoproducto'];  ?></td>
                                    <td><?php echo $dato_producto[0]['nombreproducto'];  ?></td>
                                    <td> 0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td><?php echo number_format($dato_producto[0]['precio_costo'], 0, ",", ".");  ?></td>
                                    <td><?php echo number_format($dato_producto[0]['valorventaproducto'], 0, ",", ".");  ?></td>
                                </tr>

                            <?php endif ?>


                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
            <?php if (empty($conteo_manual)): ?>

                <p class="text-center text-primary h3">No productos para hacer cruce de inventario </p>

            <?php endif ?>
        </div>
    </div>
</div>






<!-- Modal -->
<div class="modal fade" id="sobrantes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reporte de productos sobrantes en el inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Codigo </td>
                            <td scope="col">Producto </td>
                            <td scope="col">Cantidad conteo </td>
                            <td scope="col">Cantidad sistema </td>
                            <td scope="col">Diferencia inventario </td>
                            <td scope="col">Valor costo </td>
                            <td scope="col">Valor venta </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($conteo_manual)): ?>
                            <?php $total_diferencia_venta = 0;
                            foreach ($inventario_sistema as $KeyInventarioFisico): ?>

                                <?php $producto = model('inventarioModel')->conteo_manual($KeyInventarioFisico['codigointernoproducto']); //d($producto);
                                ?>

                                <?php if (!empty($producto)): ?>
                                    <?php if ($producto[0]['diferencia'] > 0): ?>
                                        <tr>
                                            <td><?php echo $producto[0]['codigointernoproducto'];  ?></td>
                                            <td><?php echo $producto[0]['nombreproducto'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario_fisico'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario'];  ?></td>
                                            <td><?php echo $producto[0]['diferencia'];  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_costo'], 0, ",", ".");  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_venta'], 0, ",", ".");  ?></td>
                                        </tr>

                                        <?php
                                        // Sumar la diferencia al total
                                        $total_diferencia_venta += $producto[0]['diferencia'] * $producto[0]['valor_venta'];
                                        ?>

                                    <?php endif ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_sobrantes">
                    <button class="btn btn-outline-danger ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver faltantes">Exportar PDF</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="faltantes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reporte de productos faltantes en el inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <td scope="col">Codigodd </td>
                            <td scope="col">Producto </td>
                            <td scope="col">Cantidad conteo </td>
                            <td scope="col">Cantidad sistema </td>
                            <td scope="col">Diferencia inventario </td>
                            <td scope="col">Valor costo </td>
                            <td scope="col">Valor venta </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($conteo_manual)): ?>
                            <?php $total_diferencia_venta = 0;
                            foreach ($inventario_sistema as $KeyInventarioFisico): ?>

                                <?php $producto = model('inventarioModel')->conteo_manual($KeyInventarioFisico['codigointernoproducto']);
                                ?>

                                <?php if (!empty($producto)): ?>
                                    <?php if ($producto[0]['diferencia'] < 0): ?>
                                        <tr>
                                            <td><?php echo $producto[0]['codigointernoproducto'];  ?></td>
                                            <td><?php echo $producto[0]['nombreproducto'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario_fisico'];  ?></td>
                                            <td><?php echo $producto[0]['cantidad_inventario'];  ?></td>
                                            <td><?php echo $producto[0]['diferencia'];  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_costo'], 0, ",", ".");  ?></td>
                                            <td><?php echo number_format($producto[0]['valor_venta'], 0, ",", ".");  ?></td>
                                        </tr>
                                    <?php endif ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <form action="<?php echo base_url() ?>/consultas_y_reportes/reporte_faltantes">
                    <button class="btn btn-outline-danger ms-2" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ver faltantes">Exportar PDF</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Inicializa el modal
    const myModalElement = document.getElementById('sobrantes');
    const myModal = new bootstrap.Modal(myModalElement);

    // Muestra el modal
    myModal.show();

    /*  // Cambia dinámicamente el contenido
     const modalBody = myModalElement.querySelector('.sobrantes');
     modalBody.textContent = "Nuevo contenido dinámico."; */

    // Actualiza la posición y el estilo del modal
    myModal.handleUpdate();
</script>


<?= $this->endSection('content') ?>