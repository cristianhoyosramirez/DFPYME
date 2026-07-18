<?php

namespace App\Libraries;

use \DateTime;
use \DateTimeZone;

class Inventario
{
    /**
     * Calcular imuestos
     * @param   $cod(igo_interno
     * 
     */
    public function actualizar_inventario($codigointerno, $id_tipo_inventario, $cantidad, $documento, $id_doc)
    {
        $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $codigointerno)->first();
        $id_pro = model('productoModel')->select('id')->where('codigointernoproducto', $codigointerno)->first();
        $inventario_final = $cantidad_inventario['cantidad_inventario'] - $cantidad;

        switch ($id_tipo_inventario) {
            case 1:
                // Salida directa: simplemente actualizar inventario
                $data = ['cantidad_inventario' => $inventario_final];
                model('inventarioModel')->set($data)->where('codigointernoproducto', $codigointerno)->update();
                break;

            case 3:
            case 7:
                // Subreceta o receta: descontar insumos
                $this->descontarInsumos($codigointerno, $cantidad, $documento, $id_doc, $id_pro);
                break;

            default:
                log_message('error', 'Tipo de inventario no reconocido: ' . $id_tipo_inventario);
                break;
        }
    }
    private function descontarInsumos($codigointerno, $cantidad, $documento, $id_doc, $id_pro)
    {
        $producto_fabricado = model('productoFabricadoModel')
            ->select('*')
            ->where('prod_fabricado', $codigointerno)
            ->findAll();



        foreach ($producto_fabricado as $detall) {

            $id_producto = model('productoModel')
                ->select('id')
                ->where('codigointernoproducto', $detall['prod_proceso'])
                ->first();

            if (!empty($id_producto)) {

                $cantidad_inventario = model('inventarioModel')
                    ->select('cantidad_inventario')
                    ->where('codigointernoproducto', $detall['prod_proceso'])
                    ->first();

                //if (!empty($cantidad_inventario['cantidad_inventario'])) {
                $descontar_de_inventario = $cantidad * $detall['cantidad'];
                $inventario_actual = $cantidad_inventario['cantidad_inventario'] - $descontar_de_inventario;

                // Actualiza el inventario
                $data = ['cantidad_inventario' => $inventario_actual];
                model('inventarioModel')
                    ->set($data)
                    ->where('codigointernoproducto', $detall['prod_proceso'])
                    ->update();

                // Registra el movimiento de insumo
                $movimiento = [
                    'fecha' => date('Y-m-d'),
                    'hora' => date("H:i:s"),
                    'id_producto' => $id_producto['id'],
                    'inventario_anterior' => $cantidad_inventario['cantidad_inventario'],
                    'inventario_actual' => $inventario_actual,
                    'id_doc' => $id_doc,
                    'tipo_doc' => $documento,
                    'id_pro_prin' => $id_pro['id'],
                ];

                model('MovimientoInsumosModel')->insert($movimiento);
                //}
            }
        }
    }

    public function devolucion($usuario, $nit_cliente, $codigo_producto_devolucion, $cantidad_devolucion, $precio_devo, $precio_devolucion, $id_apertura)
    {
        $valor_total_producto = $precio_devolucion * $cantidad_devolucion;

        $cantidadInventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto',  $codigo_producto_devolucion)->first();

        if ($cantidad_devolucion > 0 && $cantidad_devolucion != 0) {


            $id_tipo_inventario = model('productoModel')->select('id_tipo_inventario')->where('codigointernoproducto', $codigo_producto_devolucion)->first();

            if ($id_tipo_inventario['id_tipo_inventario'] == 1) {

                $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $codigo_producto_devolucion)->first();
                $inventario_final = $cantidad_inventario['cantidad_inventario'] + $cantidad_devolucion;

                $data = [
                    'cantidad_inventario' => $inventario_final,

                ];
                $model = model('inventarioModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('codigointernoproducto', $codigo_producto_devolucion);
                $actualizar = $model->update();
            } elseif ($id_tipo_inventario['id_tipo_inventario'] == 3) {

                $producto_fabricado = model('productoFabricadoModel')->select('*')->where('prod_fabricado', $codigo_producto_devolucion)->find();


                foreach ($producto_fabricado as $detalle) {
                    $sumar_inventario = $cantidad_devolucion * $detalle['cantidad'];

                    //echo $descontar_de_inventario."</br>"; ok

                    $cantidad_inventario = model('inventarioModel')->select('cantidad_inventario')->where('codigointernoproducto', $detalle['prod_proceso'])->first();

                    $data = [
                        'cantidad_inventario' => $cantidad_inventario['cantidad_inventario'] + $sumar_inventario,

                    ];

                    $model = model('inventarioModel');
                    $actualizar = $model->set($data);
                    $actualizar = $model->where('codigointernoproducto', $detalle['prod_proceso']);
                    $actualizar = $model->update();
                }
            }


            $numero_consecutivo = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 12)->first();


            $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
            $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
            $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

            $devolucion_venta = [
                'numero' => $numero_consecutivo['numeroconsecutivo'],
                'numerofactura' => "",
                'nitcliente' => $nit_cliente,
                'fecha' => date('Y-m-d'),
                'idusuario' => $usuario,
                'idcaja' => 1,
                'idturno' => 1,
                'hora' =>  date("H:i:s"),
                'id_apertura' => $id_apertura['numero'],
                'fecha_y_hora_devolucion' => $fecha_y_hora
            ];
            $insert = model('devolucionModel')->insert($devolucion_venta);



            $entradasSalidas = model('EntradasSalidasModel')->insert([
                'id_documento' => $insert,
                'id_operacion' => 1,
                'fecha'        => date('Y-m-d'),
                'tabla'        => 'devolucion_venta'
            ]);

            $actualizar_consecutivo = [
                'numeroconsecutivo' => $numero_consecutivo['numeroconsecutivo'] + 1
            ];

            $actualizar = model('consecutivosModel')->set($actualizar_consecutivo);
            $actualizar = model('consecutivosModel')->where('idconsecutivos', 12);
            $actualizar = model('consecutivosModel')->update();
            //var_dump($codigo_producto_devolucion);
            $aplica_ico = model('productoModel')->select('aplica_ico')->where('codigointernoproducto', $codigo_producto_devolucion)->first();

            if ($aplica_ico['aplica_ico'] == 't') {
                //echo "APLICA IMPUESTO AL CONSUMO";
                $id_ico = model('productoModel')->select('id_ico_producto')->where('codigointernoproducto', $codigo_producto_devolucion)->first();
                $ico = model('icoConsumoModel')->select('valor_ico')->where('id_ico', $id_ico['id_ico_producto'])->first();


                $temp = $ico['valor_ico'] / 100;
                $imp_cons = $temp + 1;
                $ipo_consumo = $precio_devolucion / $imp_cons;

                $id_devolucion = model('devolucionModel')->where('idusuario', $usuario)->insertID;

                $detalle_devolucion_venta = [
                    'id_devolucion_venta' => $id_devolucion,
                    'codigo' => $codigo_producto_devolucion,
                    'idmedida' => 3,
                    'idcolor' => 0,
                    'valor' => $ipo_consumo,
                    'descuento' => 0,
                    'iva' => 0,
                    'cantidad' => $cantidad_devolucion,
                    'impoconsumo' => $precio_devolucion - $ipo_consumo,
                    'ico' => $ico,
                    'valor_total_producto' => $valor_total_producto,
                    'fecha_y_hora_venta' => $fecha_y_hora,
                    'fecha_venta' => date('Y-m-d'),
                    'id_apertura' => $id_apertura['numero']
                ];

                $insert = model('detalleDevolucionVentaModel')->insert($detalle_devolucion_venta);

                $devolucion_venta_efectivo = [
                    'iddevolucion' => $id_devolucion,
                    'valor' => $precio_devolucion * $cantidad_devolucion
                ];

                $devolucion_venta = model('devolucionVentaEfectivoModel')->insert($devolucion_venta_efectivo);

                /*   if ($insert) {
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_producto_devolucion)->first();
                    $returnData = array(
                        "resultado" => 1,
                        "nombre_producto" => $nombre_producto['nombreproducto']
                    );
                    echo  json_encode($returnData);
                } */
            } else if ($aplica_ico['aplica_ico'] == 'f') {

                $id_iva = model('productoModel')->select('idiva')->where('codigointernoproducto', $codigo_producto_devolucion)->first();

                $iva = model('ivaModel')->select('valoriva')->where('idiva', $id_iva['idiva'])->first();
                $temp_iva = $iva['valoriva'] / 100;
                $imp_iva = $temp_iva + 1;
                $base_iva = $precio_devolucion / $imp_iva;
                $id_devolucion = model('devolucionModel')->where('idusuario', $usuario)->insertID;



                $detalle_devolucion_venta = [
                    'id_devolucion_venta' => $id_devolucion,
                    'codigo' => $codigo_producto_devolucion,
                    'idmedida' => 3,
                    'idcolor' => 0,
                    'valor' => $base_iva,
                    'descuento' => 0,
                    'iva' => $iva['valoriva'],
                    'cantidad' => $cantidad_devolucion,
                    'impoconsumo' => 0,
                    'ico' => 0,
                    'valor_total_producto' => $valor_total_producto,
                    'fecha_y_hora_venta' => $fecha_y_hora,
                    'fecha_venta' => date('Y-m-d'),
                    'id_apertura' => $id_apertura['numero'],
                    'saldo_anterior' => $cantidadInventario['cantidad_inventario'],
                    'nuevo_saldo' => $cantidadInventario['cantidad_inventario'] + $cantidad_devolucion
                ];


                $insert = model('detalleDevolucionVentaModel')->insert($detalle_devolucion_venta);

                $devolucion_venta_efectivo = [
                    'iddevolucion' => $id_devolucion,
                    'valor' => $precio_devolucion * $cantidad_devolucion
                ];

                $devolucion_venta = model('devolucionVentaEfectivoModel')->insert($devolucion_venta_efectivo);
                /* 
                if ($insert) {
                    $nombre_producto = model('productoModel')->select('nombreproducto')->where('codigointernoproducto', $codigo_producto_devolucion)->first();
                    $returnData = array(
                        "resultado" => 1,
                        "nombre_producto" => $nombre_producto['nombreproducto']
                    );
                    echo  json_encode($returnData);
                } */
            }
        } else {
            $returnData = array(
                "resultado" => 0,
            );
            echo  json_encode($returnData);
        }
    }
}
