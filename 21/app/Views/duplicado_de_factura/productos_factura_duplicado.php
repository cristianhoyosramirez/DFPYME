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

    /* =====================================================
       CONTENEDOR
    ===================================================== */

    .factura-container {
        max-width: 1000px;
        margin: auto;
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }


    /* =====================================================
       ENCABEZADO
    ===================================================== */

    .header-factura {
        background: #111827;
        color: #ffffff;
        padding: 18px 20px;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .titulo {
        font-size: 23px;
        font-weight: 700;
        line-height: 1.2;
    }

    .fecha {
        font-size: 13px;
        margin-top: 3px;
        opacity: 0.85;
    }


    /* =====================================================
       CONTENIDO
    ===================================================== */

    .contenido {
        padding: 15px;
    }


    /* =====================================================
       INFORMACIÓN DE FACTURA
    ===================================================== */

    .factura-info {
        display: grid;
        grid-template-columns: 1fr 1fr 1.5fr;
        gap: 7px;
        margin-bottom: 10px;
    }

    .info-item {
        display: flex;
        flex-direction: column;

        min-width: 0;

        padding: 7px 9px;

        background: #f8f9fa;

        border: 1px solid #e5e7eb;
        border-radius: 6px;
    }

    .info-label {
        font-size: 9px;
        font-weight: 700;
        color: #6b7280;

        text-transform: uppercase;

        margin-bottom: 2px;
    }

    .info-value {
        font-size: 12px;
        font-weight: 600;
        color: #111827;

        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }


    /* =====================================================
       TABLA DE PRODUCTOS
    ===================================================== */

    .productos-container {
        height: 140px;

        overflow-y: auto;
        overflow-x: hidden;

        border: 1px solid #e5e7eb;
        border-radius: 6px;
    }

    .tabla-productos {
        width: 100%;
        border-collapse: collapse;

        table-layout: fixed;

        font-size: 12px;
    }


    /* Encabezado */

    .tabla-productos thead {
        position: sticky;
        top: 0;
        z-index: 2;

        background: #1f2937;
        color: #ffffff;
    }

    .tabla-productos th {
        padding: 7px 6px;

        font-size: 11px;
        font-weight: 600;

        text-align: center;

        white-space: nowrap;
    }


    /* Celdas */

    .tabla-productos td {
        padding: 5px 7px;

        border-bottom: 1px solid #e5e7eb;

        font-size: 11px;

        vertical-align: middle;
    }

    .tabla-productos tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    .tabla-productos tbody tr:hover {
        background: #f3f4f6;
    }


    /* =====================================================
       ANCHOS DE COLUMNAS
    ===================================================== */

    .tabla-productos .codigo {
        width: 15%;
    }

    .tabla-productos .descripcion {
        width: 35%;
    }

    .tabla-productos .cantidad {
        width: 12%;
    }

    .tabla-productos .valor {
        width: 19%;
    }

    .tabla-productos .total {
        width: 19%;
    }


    /* =====================================================
       SCROLL
    ===================================================== */

    .productos-container::-webkit-scrollbar {
        width: 5px;
    }

    .productos-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .productos-container::-webkit-scrollbar-thumb {
        background: #9ca3af;
        border-radius: 10px;
    }


    /* =====================================================
       ALINEACIÓN
    ===================================================== */

    .text-center {
        text-align: center;
    }

    .text-right,
    .text-end {
        text-align: right;
    }


    /* =====================================================
       RESUMEN
    ===================================================== */

    .resumen {
        width: 320px;

        margin-left: auto;
        margin-top: 15px;

        border-collapse: collapse;
    }

    .resumen td {
        border: 1px solid #d1d5db;

        padding: 8px 10px;

        font-size: 13px;
    }

    .resumen tr:nth-child(even) {
        background: #f9fafb;
    }

    .total-final {
        background: #111827;

        color: #ffffff;

        font-weight: bold;

        font-size: 15px;
    }


    /* =====================================================
       TÍTULOS DE SECCIÓN
    ===================================================== */

    .titulo-seccion {
        margin-top: 20px;
        margin-bottom: 8px;

        font-size: 16px;
        font-weight: 700;

        color: #111827;
    }


    /* =====================================================
       TABLA DE ABONOS
    ===================================================== */

    .tabla-abonos {
        width: 100%;
        border-collapse: collapse;

        font-size: 12px;
    }

    .tabla-abonos thead {
        background: #1f2937;
        color: #ffffff;
    }

    .tabla-abonos th {
        padding: 7px;

        font-size: 11px;
        font-weight: 600;

        text-align: center;
    }

    .tabla-abonos td {
        padding: 6px 7px;

        border-bottom: 1px solid #e5e7eb;

        font-size: 11px;
    }

    .tabla-abonos tbody tr:nth-child(even) {
        background: #f9fafb;
    }


    /* =====================================================
       BADGE
    ===================================================== */

    .badge {
        background: #16a34a;

        color: #ffffff;

        padding: 3px 8px;

        border-radius: 20px;

        font-size: 10px;

        display: inline-block;
    }


    /* =====================================================
       SIN PRODUCTOS
    ===================================================== */

    .sin-productos {
        padding: 12px !important;

        color: #6b7280;

        text-align: center;
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 768px) {

        .factura-container {
            border-radius: 8px;
        }

        .header-factura {
            padding: 15px;
        }

        .header-top {
            flex-direction: column;
            align-items: flex-start;
        }

        .titulo {
            font-size: 20px;
        }

        .contenido {
            padding: 10px;
        }

        .factura-info {
            grid-template-columns: 1fr 1fr;
        }

        .info-item:last-child {
            grid-column: 1 / -1;
        }

        .resumen {
            width: 100%;
        }

        .productos-container {
            height: 140px;
        }

        .tabla-productos th,
        .tabla-productos td {
            padding: 5px;
        }

        .tabla-abonos th,
        .tabla-abonos td {
            padding: 5px;
        }
    }


    @media (max-width: 480px) {

        .factura-info {
            grid-template-columns: 1fr;
        }

        .info-item:last-child {
            grid-column: auto;
        }

        .tabla-productos {
            font-size: 10px;
        }

        .tabla-productos th {
            font-size: 10px;
        }

        .tabla-productos td {
            font-size: 10px;
        }
    }
</style>


<div class="factura-container">

    <div class="contenido">

        <!-- INFORMACIÓN DE LA FACTURA -->
        <div class="factura-info">

            <div class="info-item">
                <span class="info-label">Documento</span>
                <span class="info-value">
                    <?= esc($numero_factura); ?>
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Fecha</span>
                <span class="info-value">
                    <?= esc($fecha_factura); ?>
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Cliente</span>
                <span class="info-value">
                    <?= esc($nit_cliente); ?>
                </span>
            </div>

        </div>


        <!-- PRODUCTOS -->
        <div class="productos-container">

            <table class="tabla-productos">

                <thead>

                    <tr>
                        <th class="codigo">Código</th>
                        <th class="descripcion">Descripción</th>
                        <th class="cantidad">Cant.</th>
                        <th class="valor">Vr. Unit.</th>
                        <th class="total">Total</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (!empty($productos)): ?>

                        <?php foreach ($productos as $detalle): ?>

                            <?php

                            $cantidad = (float) $detalle['cantidadproducto_factura_venta'];

                            $total = (float) $detalle['total'];

                            $valor_venta = $cantidad > 0
                                ? $total / $cantidad
                                : 0;

                            ?>

                            <tr>

                                <td class="codigo text-center">
                                    <?= esc($detalle['codigointernoproducto']); ?>
                                </td>

                                <td class="descripcion">
                                    <?= esc($detalle['nombreproducto']); ?>
                                </td>

                                <td class="cantidad text-center">
                                    <?= $cantidad; ?>
                                </td>

                                <td class="valor text-end">
                                    $<?= number_format(
                                            $valor_venta,
                                            0,
                                            ',',
                                            '.'
                                        ); ?>
                                </td>

                                <td class="total text-end">
                                    $<?= number_format(
                                            $total,
                                            0,
                                            ',',
                                            '.'
                                        ); ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="5" class="text-center sin-productos">
                                No hay productos registrados.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>



<!-- RESUMEN -->
<table class="resumen">

    <tr>
        <td>
            <strong>Total </strong>
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
                <th style="width: 7%;">#</th>
                <th style="width: 38%;">Fecha</th>
                <th style="width: 20%;">Hora</th>
                <th style="width: 20%;">Valor abonado</th>
                <th style="width: 15%;">Acción</th>
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
                var_dump($abonos);
                $timestamp = strtotime($abono['fechafactura_forma_pago']);

                $diaSemana = $dias[date('l', $timestamp)];
                $mes = $meses[date('F', $timestamp)];

                $fechaFormateada = $diaSemana . " " .
                    date('d', $timestamp) . " de " .
                    $mes . " del " .
                    date('Y', $timestamp);

                ?>

                <tr>

                    <!-- Número -->
                    <td class="text-center">
                        <?= $contador++; ?>
                    </td>

                    <!-- Fecha -->
                    <td>
                        <?= $fechaFormateada; ?>
                    </td>

                    <!-- Hora -->
                    <td class="text-center">
                        <?= date("g:i a", strtotime($abono['hora'])); ?>
                    </td>

                    <!-- Valor -->
                    <td class="text-end">
                        <strong>
                            $<?= number_format($abono['valor_pago'], 0, ',', '.'); ?>
                        </strong>
                    </td>

                    <!-- Acción -->
                    <td class="text-center">

                        <button
                            type="button"
                            class="btn  btn-outline-success"
                            onclick="imprimirComprobante()"
                            title="Imprimir comprobante">

                            <i class="bi bi-printer"></i>
                            Imprimir

                        </button>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>
<?php endif; ?>
</div>

</div>