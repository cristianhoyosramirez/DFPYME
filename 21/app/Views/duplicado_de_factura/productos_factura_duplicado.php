<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 20px;
    }

    .factura-container {
        max-width: 1000px;
        margin: auto;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.08);
    }

    .header-factura {
        background: #111827;
        color: white;
        padding: 25px;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .titulo {
        font-size: 28px;
        font-weight: bold;
    }

    .fecha {
        font-size: 14px;
        margin-top: 5px;
    }

    .contenido {
        padding: 20px;
    }

    .tabla-info {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
    }

    .tabla-info td {
        border: 1px solid #e5e7eb;
        padding: 12px;
        font-size: 14px;
    }

    .tabla-info tr:nth-child(even) {
        background: #f9fafb;
    }

    .label {
        font-weight: bold;
        background: #f3f4f6;
        width: 30%;
    }

    .tabla-productos {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .tabla-productos thead {
        background: #1f2937;
        color: white;
    }

    .tabla-productos th {
        padding: 14px;
        font-size: 14px;
        text-align: center;
    }

    .tabla-productos td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
    }

    .tabla-productos tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .resumen {
        width: 350px;
        margin-left: auto;
        margin-top: 25px;
        border-collapse: collapse;
    }

    .resumen td {
        border: 1px solid #d1d5db;
        padding: 12px;
        font-size: 14px;
    }

    .resumen tr:nth-child(even) {
        background: #f9fafb;
    }

    .total-final {
        background: #111827;
        color: white;
        font-weight: bold;
        font-size: 16px;
    }

    .titulo-seccion {
        margin-top: 40px;
        margin-bottom: 15px;
        font-size: 20px;
        font-weight: bold;
        color: #111827;
    }

    .tabla-abonos {
        width: 100%;
        border-collapse: collapse;
    }

    .tabla-abonos thead {
        background: #0f766e;
        color: white;
    }

    .tabla-abonos th {
        padding: 14px;
        font-size: 14px;
    }

    .tabla-abonos td {
        padding: 12px;
        border-bottom: 1px solid #d1fae5;
        font-size: 13px;
    }

    .tabla-abonos tbody tr:nth-child(even) {
        background: #f0fdfa;
    }

    .badge {
        background: #16a34a;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
    }

    @media(max-width:768px) {

        .header-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .resumen {
            width: 100%;
        }

        .tabla-productos {
            font-size: 12px;
        }

        .tabla-productos th,
        .tabla-productos td {
            padding: 8px;
        }

        .tabla-abonos th,
        .tabla-abonos td {
            padding: 8px;
        }
    }
</style>


<div class="factura-container">

    <!-- HEADER -->
    <div class="header-factura">

        <div class="header-top">

            <div>
                <div class="titulo">
                    FACTURA DE VENTA
                </div>

                <div class="fecha">
                    <?php echo $fecha_factura . " " . date("g:i a", strtotime($hora_factura)); ?>
                </div>
            </div>

            <div>
                <h2>
                    # <?php echo $numero_factura; ?>
                </h2>
            </div>

        </div>

    </div>


    <div class="contenido">

        <!-- INFORMACION -->
        <table class="tabla-info">

            <tr>
                <td class="label">
                    Número factura
                </td>

                <td>
                    <?php echo $numero_factura; ?>
                </td>
            </tr>

            <tr>
                <td class="label">
                    Fecha factura
                </td>

                <td>
                    <?php echo $fecha_factura; ?>
                </td>
            </tr>

            <tr>
                <td class="label">
                    Nit cliente
                </td>

                <td>
                    <?php echo $nit_cliente; ?>
                </td>
            </tr>

        </table>



        <!-- PRODUCTOS -->
        <table class="tabla-productos">

            <thead>

                <tr>
                    <th width="15%">Código</th>
                    <th width="35%">Descripción</th>
                    <th width="15%">Cantidad</th>
                    <th width="15%">Vr Unitario</th>
                    <th width="20%">Total</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($productos as $detalle) {

                    $valor_venta = $detalle['total'] / $detalle['cantidadproducto_factura_venta'];
                ?>

                    <tr>

                        <td class="text-center">
                            <?php echo $detalle['codigointernoproducto']; ?>
                        </td>

                        <td>
                            <?php echo $detalle['nombreproducto']; ?>
                        </td>

                        <td class="text-center">
                            <?php echo $detalle['cantidadproducto_factura_venta']; ?>
                        </td>

                        <td class="text-right">
                            <?php echo "$" . number_format($valor_venta, 0, ',', '.'); ?>
                        </td>

                        <td class="text-right">
                            <?php echo "$" . number_format($detalle['total'], 0, ',', '.'); ?>
                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>



        <!-- RESUMEN -->
        <table class="resumen">

            <tr>
                <td>
                    <strong>Total factura</strong>
                </td>

                <td class="text-right">
                    <?php echo "$" . number_format($total_factura, 0, ',', '.'); ?>
                </td>
            </tr>

            <?php if ($forma_pago == 2): ?>

                <tr>
                    <td>
                        <strong>Total abonos</strong>
                    </td>

                    <td class="text-right">
                        <?php echo "$" . number_format($total_abonos, 0, ',', '.'); ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>Saldo pendiente</strong>
                    </td>

                    <td class="text-right">
                        <?php echo "$" . number_format($saldo, 0, ',', '.'); ?>
                    </td>
                </tr>

                <tr class="total-final">
                    <td>
                        Estado
                    </td>

                    <td class="text-right">
                        PENDIENTE
                    </td>
                </tr>

            <?php endif ?>

        </table>


        <?php if (!empty($abonos)) : ?>
            <!-- ABONOS -->
            <div class="titulo-seccion">
                Historial de Abonos
            </div>


            <table class="tabla-abonos">

                <thead>

                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Valor abonado</th>
                    </tr>

                </thead>

                <tbody>

                    <?php
                    $contador = 1;

                    $dias = [
                        'Sunday' => 'Domingo',
                        'Monday' => 'Lunes',
                        'Tuesday' => 'Martes',
                        'Wednesday' => 'Miércoles',
                        'Thursday' => 'Jueves',
                        'Friday' => 'Viernes',
                        'Saturday' => 'Sábado'
                    ];

                    $meses = [
                        'January' => 'Enero',
                        'February' => 'Febrero',
                        'March' => 'Marzo',
                        'April' => 'Abril',
                        'May' => 'Mayo',
                        'June' => 'Junio',
                        'July' => 'Julio',
                        'August' => 'Agosto',
                        'September' => 'Septiembre',
                        'October' => 'Octubre',
                        'November' => 'Noviembre',
                        'December' => 'Diciembre'
                    ];
                    ?>



                    <?php foreach ($abonos as $abono): ?>

                        <?php

                        $timestamp = strtotime($abono['fechafactura_forma_pago']);

                        $diaSemana = $dias[date('l', $timestamp)];
                        $mes = $meses[date('F', $timestamp)];

                        $fechaFormateada = $diaSemana . " " .
                            date('d', $timestamp) . " de " .
                            $mes . " del " .
                            date('Y', $timestamp);

                        ?>

                        <tr>

                            <td>
                                <?php echo $contador++; ?>
                            </td>

                            <td>
                                <?php echo $fechaFormateada; ?>
                            </td>

                            <td>
                                <?php echo date("g:i a", strtotime($abono['hora'])); ?>
                            </td>

                            <td>
                                <strong>
                                    <?php echo "$" . number_format($abono['valor_pago'], 0, ',', '.'); ?>
                                </strong>
                            </td>

                        </tr>

                    <?php endforeach ?>



                </tbody>

            </table>
        <?php endif; ?>
    </div>

</div>