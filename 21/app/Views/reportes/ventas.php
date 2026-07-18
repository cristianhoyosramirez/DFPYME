<style type="text/css">
    thead tr td {
        position: sticky;
        top: 0;
        background-color: #ffffff;
        z-index: 10;
    }

    .table-responsive {
        height: 550px;
        overflow: auto;
    }

    .badge-tipo {
        font-size: 11px;
        padding: 5px 8px;
    }

    .badge-contado {
        background-color: #198754;
        color: #fff;
    }

    .badge-credito {
        background-color: #0d6efd;
        color: #fff;
    }

    .badge-abono {
        background-color: #ffc107;
        color: #000;
    }

    .texto-ingreso {
        color: #0d6efd;
        font-weight: 600;
    }

    .texto-abono {
        color: #d39e00;
        font-weight: 600;
    }
</style>

<?php

$movimientosCaja = [];

/*
|--------------------------------------------------------------------------
| INGRESOS
|--------------------------------------------------------------------------
*/

foreach ($movimientos as $movimiento) {

    $movimientosCaja[] = [

        'tipo' => (
            $movimiento['forma_pago'] == 1
            ? 'CONTADO'
            : 'CRÉDITO'
        ),

        'fecha' => $movimiento['fecha'],
        'hora' => $movimiento['hora'],

        'documento' => (
            $movimiento['id_estado'] == 8
            ? model('facturaElectronicaModel')
                ->select('numero')
                ->where('id', $movimiento['id_factura'])
                ->first()['numero']
            : $movimiento['documento']
        ),

        'venta' => $movimiento['valor'],
        'propina' => $movimiento['propina'],
        'total' => $movimiento['total_documento'],

        'efectivo' => $movimiento['efectivo'],
        'transferencia' => $movimiento['recibido_transferencia'],
        'total_pago' => $movimiento['total_pago'],

        'usuario' => $movimiento['id_usuario_facturacion'],

        'forma_pago' => (
            $movimiento['forma_pago'] == 1
            ? 'Contado'
            : 'Crédito'
        ),

        'clase_pago' => (
            !empty($movimiento['id_clase_pago'])
            ? (
                model('clasePagoModel')
                    ->select('nombre')
                    ->where('id', $movimiento['id_clase_pago'])
                    ->first()['nombre'] ?? 'Efectivo'
            )
            : 'Efectivo'
        ),

        'id' => $movimiento['id'],
        'nit_cliente' => $movimiento['nit_cliente'],
        'nombre_cliente' => $movimiento['nombrescliente']
    ];
}

/*
|--------------------------------------------------------------------------
| ABONOS
|--------------------------------------------------------------------------
*/
//dd($abonos);
if (!empty($abonos)) {

    foreach ($abonos as $abono) {

        $movimientosCaja[] = [

            'tipo' => 'ABONO',

            'fecha' => $abono['fechafactura_forma_pago'],
            'hora' => $abono['hora'],

            'documento' => $abono['numerofactura_venta'],

            'venta' => $abono['valor_pago'],
            'propina' => 0,
            'total' => $abono['valor_pago'],

            'efectivo' => $abono['efectivo'],
            'transferencia' => $abono['electronico'],
            'total_pago' => $abono['valor_pago'],

            'usuario' => $abono['idusuario'],

            'forma_pago' => 'Crédito',
            'clase_pago' => $abono['nombre_clase_pago'],

            'id' => $abono['idfactura_forma_pago'],
            'nit_cliente' => null,
            'nombre_cliente' => null
        ];
    }
}

/*
|--------------------------------------------------------------------------
| ORDENAR
|--------------------------------------------------------------------------
*/

usort($movimientosCaja, function ($a, $b) {

    $fechaA = strtotime($a['fecha'] . ' ' . $a['hora']);
    $fechaB = strtotime($b['fecha'] . ' ' . $b['hora']);

    return $fechaB <=> $fechaA;
});

?>

<div class="row">
    <div class="col-10">

    </div>
    <div class="col-2 text-end">
        <form action="<?php echo base_url() ?>/reportes/exportar_excel" method="post">
            <input type="hidden" value="<?php echo $id_apertura ?>" name="id_apertura" id="id_apertura">
            <button type="submit" class="btn btn-outline-success  btn-icon">
                Excel
            </button>
        </form>
    </div>
</div>

<br>

<div class="table-responsive">

    <table class="table table-striped table-hover align-middle">

        <thead class="table-dark">

            <tr>
                <td>TIPO</td>
                <td>FECHA</td>
                <td>HORA</td>
                <td>DOCUMENTO</td>
                <td>NIT</td>
                <td>CLIENTE</td>
                <td>VENTA</td>
                <td>PROPINA</td>
                <td>TOTAL</td>
                <td>EFECTIVO</td>
                <td>TRANSFERENCIA</td>
                <td>T PAGO</td>
                <td>C PAGO</td>

                <td>USUARIO</td>
                <td>ACCIÓN</td>
            </tr>

        </thead>

        <tbody>

            <?php foreach ($movimientosCaja as $movimiento): ?>

                <?php

                $nombre_usuario = model('usuariosModel')
                    ->select('nombresusuario_sistema')
                    ->where('idusuario_sistema', $movimiento['usuario'])
                    ->first();

                ?>

                <tr>

                    <td>

                        <?php if ($movimiento['tipo'] == 'CONTADO'): ?>

                            <span class="badge badge-contado badge-tipo">
                                CONTADO
                            </span>

                        <?php elseif ($movimiento['tipo'] == 'CRÉDITO'): ?>

                            <span class="badge badge-credito badge-tipo">
                                CRÉDITO
                            </span>

                        <?php else: ?>

                            <span class="badge badge-abono badge-tipo">
                                ABONO
                            </span>

                        <?php endif; ?>

                    </td>

                    <td>
                        <?php echo $movimiento['fecha'] ?>
                    </td>

                    <td>

                        <?php

                        $hora = new DateTime($movimiento['hora']);
                        echo $hora->format('h:i A');

                        ?>

                    </td>

                    <td>

                        <?php if ($movimiento['tipo'] == 'ABONO'): ?>

                            <span>
                                <?php echo $movimiento['documento'] ?>
                            </span>

                        <?php else: ?>

                            <span>
                                <?php echo $movimiento['documento'] ?>
                            </span>

                        <?php endif; ?>

                    </td>

                    <td>

                        <?php

                        if (!empty($movimiento['nit_cliente'])) {

                            echo $movimiento['nit_cliente'];
                        } else {

                            // OTRA OPERACIÓN
                            $datos = model('facturaFormaPagoModel')->select('id_factura,id_estado')->where('idfactura_forma_pago', $movimiento['id'])->first();

                            $datos_cliente = model('pagosModel')->select('nit_cliente')
                                ->where('id_factura', $datos['id_factura'])
                                ->where('id_estado', $datos['id_estado'])
                                ->first();

                            echo $datos_cliente['nit_cliente'];
                        }

                        ?>

                    </td>

                    <td>

                        <?php

                        if (!empty($movimiento['nombre_cliente'])) {

                            echo $movimiento['nombre_cliente'];
                        } else {

                            $datos = model('facturaFormaPagoModel')
                                ->select('cliente.nombrescliente')
                                ->join('pagos', 'pagos.id_factura = factura_forma_pago.id_factura AND pagos.id_estado = factura_forma_pago.id_estado')
                                ->join('cliente', 'cliente.nitcliente = pagos.nit_cliente')
                                ->where('factura_forma_pago.idfactura_forma_pago', $movimiento['id'])
                                ->first();

                            echo $datos['nombrescliente'];
                        }

                        ?>

                    </td>

                    <td>
                        $ <?php echo number_format($movimiento['venta'], 0, ",", ".") ?>
                    </td>

                    <td>
                        $ <?php echo number_format($movimiento['propina'], 0, ",", ".") ?>
                    </td>

                    <td>
                        $ <?php echo number_format($movimiento['total'], 0, ",", ".") ?>
                    </td>

                    <td>
                        $ <?php echo number_format($movimiento['efectivo'], 0, ",", ".") ?>
                    </td>

                    <td>
                        $ <?php echo number_format($movimiento['transferencia'], 0, ",", ".") ?>
                    </td>

                    <td>
                        $ <?php echo number_format($movimiento['total_pago'], 0, ",", ".") ?>
                    </td>

                    <td>

                        <?php

                        if ($movimiento['tipo'] == 'CRÉDITO') {

                            echo 'Crédito';
                        } else {

                            if ($movimiento['clase_pago'] == 0) {
                                echo "Efectivo";
                            } else if ($movimiento['clase_pago'] != 0 and !empty($movimiento['clase_pago'])) {

                                //$clase_pago = model('clasePagoModel')->select('nombre')->where('id',$movimiento['clase_pago'])->first();
                                //echo $clase_pago['nombre'];

                                //$pago = model('cierreModel')->clasePago($movimiento['clase_pago']);
                                //d($pago);

                                echo $movimiento['clase_pago'];

                                
                            }
                        }

                        ?>

                    </td>



                    <td>
                        <?php echo $nombre_usuario['nombresusuario_sistema'] ?? '' ?>
                    </td>

                    <td>

                        <?php if ($movimiento['tipo'] == 'ABONO'): ?>

                            <a
                                href="#"
                                class="btn btn-outline-dark btn-icon"
                                onclick="editar_abono(<?php echo $movimiento['id'] ?>)">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">

                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                                    <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />

                                    <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />

                                </svg>

                            </a>

                        <?php elseif ($movimiento['tipo'] != 'CRÉDITO'): ?>

                            <a
                                href="#"
                                class="btn btn-outline-dark btn-icon"
                                onclick="editar_pago(<?php echo $movimiento['id'] ?>)">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round">

                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                                    <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />

                                    <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />

                                </svg>

                            </a>

                        <?php else: ?>

                            <span>
                                Sin acción
                            </span>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>



<script>
    async function editar_abono(id) {

        try {

            $("#modal_propinas").modal("hide");

            let url = document.getElementById("url").value;

            let response = await $.ajax({
                data: {
                    id: id
                },
                url: url + "/reportes/datos_abono",
                type: "POST"
            });

            let resultado = JSON.parse(response);

            if (resultado.resultado == 1) {

                $('#editar_abono_nit').val(resultado.nit_cliente);
                $('#editar_abono_nombre').val(resultado.nombre_cliente);
                $('#editar_abono_efectivo').val(resultado.efectivo);
                $('#editar_abono_transferencia').val(resultado.transferencia);
                $('#editar_abono_total').val(resultado.total_pagado);
                $('#nuevo_efectivo').val(resultado.efectivo);
                $('#nueva_transferencia').val(resultado.transferencia);
                $('#banco').val(resultado.id_clase_pago);
                $('#editar_abono_total').val(resultado.total);

                $('#editar_numero_factura').val(resultado.numero_factura);
                $('#editar_fecha_factura').val(resultado.fecha_factura);
                $('#editar_valor_factura').val(resultado.total_factura);
                $('#editar_saldo_factura').val(resultado.saldo);
                $('#id_abono_edicion').val(resultado.id);

                $('#edicion_total_actual').html(resultado.total);

                $("#modal_editar_abono").modal("show");





            }

        } catch (error) {

            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible cargar la información del abono'
            });
        }
    }
</script>



<script>
    function editar_pago(id) {

        $("#modal_propinas").modal("hide");

        var url = document.getElementById("url").value;

        $.ajax({
            data: {
                id
            },
            url: url +
                "/" +
                "reportes/datos_pagos",
            type: "POST",
            success: function(resultado) {

                var resultado = JSON.parse(resultado);

                if (resultado.resultado == 1) {

                    $('#efectivo_factura').val(resultado.efectivo);
                    $('#transferencia_factura').val(resultado.transferencia);
                    $('#id_factura').val(resultado.id);
                    $('#formasPago').html(resultado.forma_pago);
                    $('#nit_cliente').val(resultado.nit_cliente);
                    $('#nombre_cliente').val(resultado.nombre_cliente);
                    $('#valor_total').val(resultado.total);
                    $('#valor_efectivo_factura').val(resultado.efectivo);
                    $('#valor_transferencia_factura').val(resultado.transferencia);
                    $('#valor_total_factura').val(resultado.total_pagado);
                    $('#nombre_pago').val(resultado.nombre_pago);

                    $("#editar_pagos").modal("show");
                }
            },
        });
    }
</script>