<?php $session = session(); ?>
<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
Reporte de costos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!-- Select 2 -->

<!-- Jquery date picker  -->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">


<div class="container">
    <p class="text-center text-primary h3">Informe de costo por producto </p>

    <div class="my-4"></div> <!-- Added space between the title and date inputs -->
    <!-- Agregar una barra de progreso -->


    <input type="hidden" id="url" value="<?php echo base_url() ?>">
    <form action="<?php echo base_url() ?>/factura_directa/exportCostoExcel" method="POST">
        <div class="row">
            <div class="col-2">
                <label for="" class="form-label">Fecha inicial </label>
                <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_inicial" name="fecha_inicial">
            </div>
            <div class="col-2">
                <label for="" class="form-label">Fecha final </label>
                <input type="date" class="form-control" value="<?php echo date('Y-m-d') ?>" id="fecha_final" name="fecha_final">
            </div>
            <div class="col-2">
                <label for="" class="form-label text-light">Fech </label>
                <a href="#" onclick="buscarProductos()" class="btn btn-outline-success w-100">Buscar</a>
            </div>
            <div class="col-2">
                <label for="" class="form-label text-light">Fech </label>
                <button type="button" class="btn btn-outline-success "> Excel </button>
            </div>
        </div>
    </form>


    <div id="processing-bar" style="display: none;">
        <p class="text-primary h3">Procesando petición</p>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
        </div>
    </div>
    <div class="my-3"></div> <!-- Added space between the buttons and the table -->

    <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
        <table class="table table-striped table-hover">
            <thead class="table-dark" style="position: sticky; top: 0; z-index: 1000;">
                <tr>
                    <td>Factura</th>
                    <td>Fecha</th>
                    <td>Código</th>
                    <td>Producto</th>
                    <td>Cantidad</th>
                    <td>Costo base sin IVA unidad </th>
                    <td>Costo base sin IVA total </th>
                    <td>Costo + IVA unidad</th>
                    <td>Costo + IVA total </th>
                    <td>Valor venta base unidad </th>
                    <td>Valor venta base total </th>
                    <td>ICO 8 % total </th>
                    <td>IVA 5 % total </th>
                    <td>IVA 19 % total </th>
                    <td>Total venta </th>
                </tr>
            </thead>
            <tbody id="datos_costos">


                <?php if (!empty($productos)): ?>

                    <?php foreach ($productos as $detalle): ?>



                        <tr>
                            <!-- Factura -->
                            <td>
                                <?php echo $detalle['numero']; ?>
                            </td>

                            <!-- Fecha -->
                            <td>
                                <?php echo $detalle['fecha']; ?>
                            </td>

                            <!-- Código -->
                            <td>
                                <?php echo $detalle['codigo']; ?>
                            </td>

                            <!-- Nombre producto -->
                            <td>
                                <?php echo $detalle['nombreproducto']; ?>
                            </td>

                            <!-- Cantidad -->
                            <td>
                                <?php echo number_format($detalle['cantidad'], 0, ',', '.'); ?>
                            </td>

                            <!-- Costo base sin IVA unidad -->
                            <td>
                                <?php
                                $valor_base = $detalle['costo'];
                                echo number_format($valor_base, 0, ',', '.');
                                ?>
                            </td>

                            <!-- Costo base sin IVA total -->
                            <td>
                                <?php
                                echo number_format(
                                    $valor_base * $detalle['cantidad'],
                                    0,
                                    ',',
                                    '.'
                                );
                                ?>
                            </td>

                            <!-- Costo + IVA unidad -->
                            <td>
                                <?php
                                $iva_porcentaje = $detalle['iva'];
                                $factor_iva = 1 + ($iva_porcentaje / 100);

                                $valor_con_iva = $valor_base * $factor_iva;

                                echo number_format($valor_con_iva, 0, ',', '.');
                                ?>
                            </td>

                            <!-- Costo + IVA total -->
                            <td>
                                <?php
                                echo number_format(
                                    $valor_con_iva * $detalle['cantidad'],
                                    0,
                                    ',',
                                    '.'
                                );
                                ?>
                            </td>

                            <!-- Valor venta base unidad -->
                            <td>
                                <?php
                                $precio_unitario = $detalle['precio_unitario'];

                                echo number_format($precio_unitario, 0, ',', '.');
                                ?>
                            </td>

                            <!-- Valor venta base total -->
                            <td>
                                <?php
                                $valor_venta_base_total = $precio_unitario * $detalle['cantidad'];

                                echo number_format($valor_venta_base_total, 0, ',', '.');
                                ?>
                            </td>

                            <!-- ICO 8 % total -->
                            <!-- ICO 8 % total -->
                            <td>
                                <?php
                                $ico_8_total = 0;

                                $aplica_ico = $detalle['aplica_ico'] ?? false;
                                $porcentaje_ico = $detalle['ico'] ?? 0;
                                $valor_ico = $detalle['valor_ico'] ?? 0;

                                if (
                                    $aplica_ico &&
                                    (float) $porcentaje_ico == 8
                                ) {
                                    $ico_8_total = $valor_ico;
                                }

                                echo number_format($ico_8_total, 0, ',', '.');
                                ?>
                            </td>

                            <!-- IVA 5 % total -->
                            <td>
                                <?php
                                $iva_5_total = 0;

                                if ((float) $detalle['iva'] == 5) {
                                    $iva_5_total = $detalle['valor_iva'];
                                }

                                echo number_format($iva_5_total, 0, ',', '.');
                                ?>
                            </td>

                            <!-- IVA 19 % total -->
                            <td>
                                <?php
                                $iva_19_total = 0;

                                if ((float) $detalle['iva'] == 19) {
                                    $iva_19_total = $detalle['valor_iva'];
                                }

                                echo number_format($iva_19_total, 0, ',', '.');
                                ?>
                            </td>

                            <!-- Total venta -->
                            <td>
                                <?php
                                echo number_format($detalle['total'], 0, ',', '.');
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>>
                    <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($total)): ?>
        <div class="text-end h3 text-primary">
            <span id="total">Total: <?php echo number_format($total[0]['total'], 0, '.', '.') ?></span><br>
            <!--  <span id="iva">Iva:<?php echo number_format($total[0]['iva'], 0, '.', '.') ?></span><br>
            <span id="ico">Impoconsumo:<?php echo number_format($total[0]['inc'], 0, '.', '.') ?></span> -->

        </div>
    <?php endif ?>

    <?php if (empty($productos)): ?>
        <p class="text-danger text-center h3" id="mensajeProducto">No hay datos para la fecha <?php echo date('Y-m-d'); ?></p>
    <?php endif; ?>
</div>
</div>

<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>

<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>

<script>
    async function buscarProductos() {
        document.getElementById("processing-bar").style.display = "block";
        url = document.getElementById('url').value;
        fecha_inicial = document.getElementById('fecha_inicial').value;
        fecha_final = document.getElementById('fecha_final').value;
        try {
            const respuesta = await fetch(url + '/factura_directa/BuscarReporteCosto', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    fecha_inicial: fecha_inicial,
                    fecha_final: fecha_final
                }) // Puedes enviar datos en el body si es necesario
            });



            if (!respuesta.ok) {
                throw new Error('No se pudo obtener el producto');
            }

            const response = await respuesta.json();

            if (response.response == "success") {
                document.getElementById("processing-bar").style.display = "none";
                document.getElementById('datos_costos').innerHTML = response.productos;
                document.getElementById('total').innerHTML = response.total;
                document.getElementById('iva').innerHTML = response.iva;
                //document.getElementById('inc').innerHTML = response.inc;
                document.getElementById('mensajeProducto').innerHTML = ""
            }

            if (response.response == "false") {
                sweet_alert_centrado('warning', 'No se encontraron registros')
                document.getElementById('datos_costos').innerHTML = "";
                document.getElementById("processing-bar").style.display = "none";
            }

            return response;
        } catch (error) {
            console.error('Error:', error);
            return null;
        }
    }
</script>





























<?= $this->endSection('content') ?>