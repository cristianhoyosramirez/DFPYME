<div class="card container">
    <div class="card-body">
        <div class="row">
            <div class="col-1 text-end">
                <form action="<?php echo $action_url; ?>" method="POST">
                    <input type="hidden" value="<?php echo $fecha_apertura ?>" name="fecha_reporte">
                    <input type="hidden" value="<?php echo $id_apertura; ?>" name="id_apertura">

                    <button type="submit" title="Exportar a pdf" class="btn btn-outline-danger w-150 btn-icon">
                        Pdf
                    </button>
                </form>
            </div>

            <div class="col-1">

                <button type="submit" title="Imprimir" class="btn btn-outline-green w-150 btn-icon" onclick="imprimir_fiscal()">
                    Imprimir
                </button>

            </div>


        </div>
        <div class="row">
            <div class="col-6">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th><?php echo $nombre_comercial ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $nombre_juridico ?></td>
                        </tr>
                        <tr>
                            <td>Nit: <?php echo $nit ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $nombre_regimen ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $direccion . " " . $nombre_ciudad . " " . $nombre_departamento ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php //dd($ico_devolucion); 
            ?>
            <div class="col-6">
                <table class="table table-borderless">

                    <tbody>
                        <tr>
                            <th><?php echo $titulo ?></th>
                        </tr>
                        <tr>
                            <td>N°:<?php echo $consecutivo ?></td>
                        </tr>
                        <tr>
                            <td>Caja N° 1 </td>
                        </tr>
                        <tr>
                            <td>Fecha: <?php echo $fecha_apertura; ?> </td>
                        </tr>
                    </tbody>

            </div>
        </div>
        <div class="col-12 row ">
            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <th>Registro inicial</td>
                        <td><?php echo $registro_inicial ?></td>
                        <th>Registro final</td>
                        <td><?php echo $registro_final ?></td>
                        <th>Total registros</td>
                        <td><?php echo $total_registros ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>
        <p class="h2 text-primary"> TOTALES POR TARIFA IVA</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Tarifa </th>
                    <td scope="col">Base grabable</th>
                    <td scope="col">Valor iva </th>
                    <td scope="col">Valor total </th>
                </tr>
            </thead>
            <tbody>



                <?php foreach ($iva as $detalle) { ?>
                    <tr>
                        <th><?php echo $detalle['tarifa_iva'] ?>%</th> <!-- TARIFA IVA  -->
                        <td><?php echo "$" . number_format($detalle['base'], 0, ",", ".") ?></td> <!-- BASE -->
                        <td><?php echo "$" . number_format($detalle['total_iva'], 0, ",", ".") ?></td> <!-- TOTAL IVA  -->
                        <td><?php echo "$" . number_format($detalle['valor_venta'], 0, ",", ".") ?></td> <!-- TOTAL  -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p class="h2 text-primary"> IMPUESTO AL CONSUMO</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Tarifa </th>
                    <td scope="col">Base grabable</th>
                    <td scope="col">Valor ICO</th>
                    <td scope="col">Valor total</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($ico as $detalle) { ?>
                    <tr>
                        <th><?php echo $detalle['tarifa_ico'] ?>%</th> <!-- TARIFA ICO  -->
                        <td><?php echo "$" . number_format($detalle['base'], 0, ",", ".") ?></td> <!-- BASE -->
                        <td><?php echo "$" . number_format($detalle['total_ico'], 0, ",", ".") ?></td> <!-- TOTAL ICO  -->
                        <td><?php echo "$" . number_format($detalle['valor_venta'], 0, ",", ".") ?></td> <!-- TOTAL  -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p class="h2 text-primary">DETALLE DE LA VENTA</p>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td scope="col">VENTAS CONTADO </th>
                    <td scope="col">VENTAS CRÉDITO</th>
                    <td scope="col">TOTAL VENTAS</th>
                </tr>
            </thead>
            <tbody>
                <tr>

                    <td><?php echo "$" . number_format($vantas_contado, 0, ",", ".") ?></td>
                    <td>$0</td>
                    <td><?php echo "$" . number_format($vantas_contado, 0, ",", ".") ?></td>
                </tr>
            </tbody>
        </table>



        <p class="h2 text-primary">IVA EN DEVOLUCIONES</p>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <td>Número de factura</th>
                    <td>Tarifa</th>
                    <td>Base</th>
                    <td>IVA</th>
                    <td>Subtotal</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $total_base_iva = 0;
                $total_impuesto_iva = 0;
                $total_general_iva = 0;

                foreach ($iva_devolucion as $item):

                    $total_base_iva += $item['base_gravable'];
                    $total_impuesto_iva += $item['valor_iva'];
                    $total_general_iva += ($item['base_gravable'] + $item['valor_iva']);
                ?>
                    <tr>
                        <td>Devolución general</td>
                        <td><?= $item['porcentaje_iva'] ?>%</td>
                        <td><?= number_format($item['base_gravable'], 0, ",", ".") ?></td>
                        <td><?= number_format($item['valor_iva'], 0, ",", ".") ?></td>
                        <td><?= number_format($item['base_gravable'] + $item['valor_iva'], 0, ",", ".") ?></td>
                    </tr>
                <?php endforeach; ?>

                <!--   <tr class="bg-muted-lt  fw-bold">
                    <td colspan="2" class="text-end">TOTAL IVA DEVOLUCIONES</td>
                    <td><?= number_format($total_base_iva, 0, ",", ".") ?></td>
                    <td><?= number_format($total_impuesto_iva, 0, ",", ".") ?></td>
                    <td><?= number_format($total_general_iva, 0, ",", ".") ?></td>
                </tr> -->

            </tbody>
        </table>


        <p class="h2 text-primary">IMPUESTO AL CONSUMO EN DEVOLUCIONES</p>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <td>Número de factura</th>
                    <td>Tarifa</th>
                    <td>Base</th>
                    <td>INC</th>
                    <td>Subtotal</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $total_base_inc = 0;
                $total_impuesto_inc = 0;
                $total_general_inc = 0;

                foreach ($ico_devolucion as $item):

                    $total_base_inc += $item['base_gravable'];
                    $total_impuesto_inc += $item['valor_ico'];
                    $total_general_inc += ($item['base_gravable'] + $item['valor_ico']);
                ?>
                    <tr>
                        <td>Devolución general</td>
                        <td><?= $item['porcentaje_ico'] ?>%</td>
                        <td><?= number_format($item['base_gravable'], 0, ",", ".") ?></td>
                        <td><?= number_format($item['valor_ico'], 0, ",", ".") ?></td>
                        <td><?= number_format($item['base_gravable'] + $item['valor_ico'], 0, ",", ".") ?></td>
                    </tr>
                <?php endforeach; ?>

                <!--     <tr class="bg-muted-lt fw-bold">
                    <td colspan="2" class="text-end">TOTAL INC DEVOLUCIONES</td>
                    <td><?= number_format($total_base_inc, 0, ",", ".") ?></td>
                    <td><?= number_format($total_impuesto_inc, 0, ",", ".") ?></td>
                    <td><?= number_format($total_general_inc, 0, ",", ".") ?></td>
                </tr> -->

            </tbody>
        </table>

        <?php
        $total_devoluciones = $total_general_iva + $total_general_inc;
        ?>

        <table class="table table-bordered">
            <tr class="table-dark">
                <th colspan="2" class="text-center">
                    RESUMEN GENERAL DE DEVOLUCIONES
                </th>
            </tr>
            <!--  <tr>
                <td>Total devoluciones IVA</td>
                <td><?= number_format($total_general_iva, 0, ",", ".") ?></td>
            </tr>
            <tr>
                <td>Total devoluciones INC</td>
                <td><?= number_format($total_general_inc, 0, ",", ".") ?></td>
            </tr> -->
            <tr class="table-success fw-bold fs-4">
                <td>TOTAL GENERAL DEVOLUCIONES NOTA CRÉDITO </td>
                <td><?= number_format($total_devoluciones, 0, ",", ".") ?></td>
            </tr>
        </table>



        <p class="text-start h3 text-primary">Formas de pago </p>


        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <td>Medio de pago</th>
                    <td class="text-end">Valor</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $total_pagos = 0;

                foreach ($pago as $keyPago) {

                    $nombre_comercial = model('medioPagoModel')->getNombre($keyPago['medio_pago']);
                    $total = model('medioPagoModel')->getTotal($keyPago['medio_pago'], $id_apertura);

                    $valor = $total[0]['total'] ?? 0;
                    $total_pagos += $valor;
                ?>
                    <tr>
                        <td><?= $nombre_comercial[0]['nombre_comercial'] ?></td>
                        <td class="text-end"><?= number_format($valor, 0, ",", ".") ?></td>
                    </tr>
                <?php } ?>

                <tr class="table-success fw-bold">
                    <td>TOTAL PAGOS</td>
                    <td class="text-end"><?= number_format($total_pagos, 0, ",", ".") ?></td>
                </tr>

            </tbody>
        </table>



    </div>
</div>