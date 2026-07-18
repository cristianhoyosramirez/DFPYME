<?php

namespace App\Controllers\actualizaciones;

use App\Controllers\BaseController;

class ParametrizacionController extends BaseController
{
    public function parametrizacion()
    {

        $codigo_pantalla = model('configuracionPedidoModel')->select('codigo_pantalla')->first();
        $altura = model('configuracionPedidoModel')->select('altura')->first();
        $mostrar_boton_mitad = model('configuracionPedidoModel')->select('mostrar_boton_mitad')->first();
        $mesero = model('configuracionPedidoModel')->select('mostrarmesero')->first();
        $nota = model('configuracionPedidoModel')->select('notaPedido')->first();
        $justificaionPedido = model('configuracionPedidoModel')->select('justificacion_pedido')->first();
        $justificaionProducto = model('configuracionPedidoModel')->select('justificacion_producto')->first();


        return view('parametrizacion/parametrizacion', [
            'codigo_pantalla' => $codigo_pantalla['codigo_pantalla'],
            'altura' => $altura['altura'],
            'mostrar_boton_mitad' => $mostrar_boton_mitad['mostrar_boton_mitad'],
            'mesero' => $mesero['mostrarmesero'],
            'nota' => $nota['notaPedido'],
            'justificacionPedido' => $justificaionPedido['justificacion_pedido'],
            'justificacionProducto' => $justificaionProducto['justificacion_producto'],
        ]);
    }


    function actualizar_codigo()
    {
        $opcion = $this->request->getPost('opcion');

        $update = model('configuracionPedidoModel')->set('codigo_pantalla', $opcion)->update();

        if ($update) {
            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    }
    function actualizar_altura()
    {
        $altura = $this->request->getPost('altura');

        $update = model('configuracionPedidoModel')->set('altura', $altura)->update();

        if ($update) {
            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    }

    function encabezadoComanda()
    {

        $json = $this->request->getJSON();
        $valor = $json->valor;

        $update = model('configuracionPedidoModel')->set('espacios_comanda_encabezado', $valor)->update();


        return $this->response->setJSON([
            'response' => 'success',
        ]);
    }
    function pieComanda()
    {

        $json = $this->request->getJSON();
        $valor = $json->valor;

        $update = model('configuracionPedidoModel')->set('espacios_comanda_pie', $valor)->update();


        return $this->response->setJSON([
            'response' => 'success',
        ]);
    }

    function actualizar_boton_mitad()
    {

        $json = $this->request->getJSON();
        $valor = $json->mostrar_boton_mitad;

        $actualizar = model('configuracionPedidoModel')->set('mostrar_boton_mitad', $valor)->update();

        if ($actualizar) {
            return $this->response->setJSON([
                'response' => 'success',
            ]);
        }
    }

    function actualizar_mesero()
    {

        $json = $this->request->getJSON();
        $valor = $json->mostrar_boton_mitad;

        $actualizar = model('configuracionPedidoModel')->set('mostrarmesero', $valor)->update();

        if ($actualizar) {
            return $this->response->setJSON([
                'response' => 'success',
            ]);
        }
    }

    function actualizar_nota()
    {

        $json = $this->request->getJSON();
        $valor = $json->mostrar_boton_mitad;

        $actualizar = model('configuracionPedidoModel')->set('notaPedido', $valor)->update();

        if ($actualizar) {
            return $this->response->setJSON([
                'response' => 'success',
            ]);
        }
    }

    function consultarPrecio()
    {

        // $json = $this->request->getJSON();
        //$id_producto = $json->id_tabla_producto;

        $id_producto = $this->request->getPost('id_tabla_producto');

        $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto)->first();
        $valorVenta = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $codigo_interno['codigointernoproducto'])->first();

        $returnData = array(
            "resultado" => 1,
            "precio" => $valorVenta['valorventaproducto']
        );
        echo  json_encode($returnData);;
    }

    public function actualizarJustificacionPedido()
    {

        $justificacion = $this->request->getPost('justificacion');

        $data = [
            'justificacion_pedido' => $justificacion
        ];

        $actualizar = model('configuracionPedidoModel')->set($data)->update();

        if ($actualizar) {
            return $this->response->setJSON([
                'status' => 'ok',
                'mensaje' => 'Justificación actualizada correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'mensaje' => 'No se pudo actualizar la justificación'
            ]);
        }
    }

    public function actualizarJustificacionProducto()
    {

        $justificacion = $this->request->getPost('justificacion');



        $data = [
            'justificacion_producto' => $justificacion
        ];

        $actualizar = model('configuracionPedidoModel')->set($data)->update();

        if ($actualizar) {
            return $this->response->setJSON([
                'status' => 'ok',
                'mensaje' => 'Justificación actualizada correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'mensaje' => 'No se pudo actualizar la justificación'
            ]);
        }
    }
    public function actualizarPrefactura()
    {

        $valor = $this->request->getPost('valor');

        $data = [
            'borrarFe' => $valor
        ];

        $actualizar = model('configuracionPedidoModel')->set($data)->update();

        if ($actualizar) {
            return $this->response->setJSON([
                'status' => 'ok',
                'mensaje' => 'Configuración aplicada correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'mensaje' => 'No se pudo actualizar la justificación'
            ]);
        }
    }

    public function consultarFactura()
    {

        $json = $this->request->getJSON();


        $id = $json->id;
        // $id = 88907; 

        $existeNc = model('notaCreditoModel')
            ->where('id_factura', $id)
            ->countAllResults();

        if ($existeNc > 0) {

            return $this->response->setJSON([
                'status' => false,
                'mensaje' => 'Hay una nota crédito en curso ',

            ]);
        } else {


            $motivos = model('razonNotaCreditoModel')->where('id', 1)->findAll();

            // Consultar factura
            $factura = model('facturaElectronicaModel')->getDocumento($id);
            $productos = model('facturaElectronicaModel')->getProductos($id);
            //dd( $productos);

            $total_ico = model('kardexModel')
                ->selectSum('ico')->where('id_factura', $id)
                ->where('id_estado', 8)
                ->first();

            $total_iva = model('kardexModel')
                ->selectSum('iva')->where('id_factura', $id)
                ->where('id_estado', 8)
                ->first();


            /* 
            return $this->response->setJSON([
                'status'   => true,
                'mensaje'  => 'Configuración aplicada correctamente',
                'factura'  => view('ventas/notaCredito', [
                    'factura'   => $factura,
                    'motivos'   => $motivos,
                    'productos' => $productos,
                    'id'        => $id,
                    'sub_total' => number_format($factura[0]['total'] - (($total_iva['iva'] ?? 0) + ($total_ico['ico'] ?? 0)), 0, ',', '.'),
                    'iva' => number_format($total_iva['iva'] ?? 0, 0, ',', '.'),
                    'icn' => number_format($total_ico['ico'] ?? 0, 0, ',', '.')
                ]),
                'factura'=>$factura[0]['numero']

            ]); */

            return $this->response->setJSON([
                'status'   => true,
                'mensaje'  => 'Configuración aplicada correctamente',
                'factura'    => view('ventas/notaCredito', [
                    'factura'   => $factura,
                    'motivos'   => $motivos,
                    'productos' => $productos,
                    'id'        => $id,
                    'sub_total' => number_format(
                        $factura[0]['total'] - (($total_iva['iva'] ?? 0) + ($total_ico['ico'] ?? 0)),
                        0,
                        ',',
                        '.'
                    ),
                    'iva' => number_format($total_iva['iva'] ?? 0, 0, ',', '.'),
                    'icn' => number_format($total_ico['ico'] ?? 0, 0, ',', '.')
                ]),
                'numero_factura' => $factura[0]['numero'],
                'cliente'=>$factura[0]['nombrescliente']
            ]);
        }
    }

    public function notaCredito()
    {
        $json = $this->request->getJSON();
        $id = $json->documento;
        $id_usuario = $json->id_usuario;
        $nota = $json->nota;
        $razon = $json->razon;
        //$razon ='ANULACION';
        //$nota = '';
        //$id_usuario = 6;
        //$id = 87669;

        $factura = model('facturaElectronicaModel')->getDocumento($id);
        //dd($factura);
        $productos = model('facturaElectronicaModel')->getProductos($id);

        $prefijo = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 120)->first();
        $numeroNc = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 121)->first();

        $total_ico = model('kardexModel')
            ->selectSum('ico')->where('id_factura', $id)
            ->where('id_estado', 8)
            ->first();

        $total_iva = model('kardexModel')
            ->selectSum('iva')->where('id_factura', $id)
            ->where('id_estado', 8)
            ->first();


        $data = [
            'tipo_ambiente'    => 'PRODUCCION',
            'id_status'        => 1,
            'caja'             => 1,
            'usuario_id'       => $id_usuario,
            'station'          => 'CAJA',
            'id_factura'       => $id,
            'nit_cliente'      => $factura[0]['nit_cliente'],
            'prefijo'          =>  $prefijo['numeroconsecutivo'],
            'numero'           => (int) $numeroNc['numeroconsecutivo'],
            'fecha'            => date('Y-m-d H:i:s'),
            'fecha_validacion' => date('Y-m-d H:i:s'),
            'impuesto'         => $total_ico['ico'] + $total_iva['iva'],
            'retencion'        => 0,
            'total'            => $factura[0]['total'],
            'razon'            => $razon,
            'nota'             => $nota,
            'propina'          => $factura[0]['propina'],
            'saldada'          => false,
            'uuid'             => '',
            'qrcode'           => '',
            'cufe'             => '',
            'pdf_url'          => '',
            'dian_message'     => '',
        ];
        /*  $data = [
            'tipo_ambiente' => 1,
            'id_status' => 1,
            'caja' => 1,
            'usuario' =>  $id_usuario,
            'station' => 'CAJA',
            'id_factura' => $id,
            'nit_cliente' => $factura[0]['nit_cliente'],
            'prefijo' => $prefijo['numeroconsecutivo'],
            'numero' => (int) $numeroNc['numeroconsecutivo'],
            'fecha' => date('Y-m-d'),
            'fecha_validacion' => date('Y-m-d'),
            'impuesto' => $total_ico['ico'] + $total_iva['iva'],
            'retencion' => 0,
            'total' => 0,
            'razon' => 1,
            'nota' => $nota,
            'saldada' => false,
            'uuid',
            'qrcode',
            'cufe',
            'pdf_url',
            'dian_message'
        ]; */

        /* $insertar = model('notaCreditoModel')->insert($data);

        if ($insertar) {

            $updateNumeroNc = model('consecutivosModel')->set('numeroconsecutivo', $numeroNc['numeroconsecutivo'] + 1)->where('idconsecutivos', 121)->update();
        } */

        $db = \Config\Database::connect();

        $db->transStart();

        $insertar = model('notaCreditoModel')->insert($data);

        if ($insertar) {
            model('consecutivosModel')
                ->set('numeroconsecutivo', (int) $numeroNc['numeroconsecutivo'] + 1, false)
                ->where('idconsecutivos', 121)
                ->update();
        }

        $db->transComplete();

        //32.0.,mnb vcxz.02
        //$insertar = model('notaCreditoModel')->insert($data);

        //dd($productos);

        //dd($productos);
        foreach ($productos as $detalleProductos) {

            //$id_medida = model('facturaElectronicaModel')->idUnidadMedida($detalleProductos['codigo']);
            //$valor_medida = model('facturaElectronicaModel')->valorUnidad($id_medida[0]['idvalor_unidad_medida']);


            $dato = [
                'id_nota' => $insertar,
                'codigo' => $detalleProductos['codigo'],
                'descripcion' => $detalleProductos['descripcion'],
                'unidad_medida' => $detalleProductos['unidad_medida'],
                'cantidad' => $detalleProductos['cantidad'],
                'iva' => $detalleProductos['iva'],
                'ic' => 0,
                'icn' => $detalleProductos['icn'],
                'precio_unitario' => $detalleProductos['precio_unitario'],
                'neto' => $detalleProductos['neto'],
                'total' => $detalleProductos['total'],
                'imp_ic' => $detalleProductos['imp_ic']
            ];

            //d($dato);
            $insert = model('itemNotaCreditoModel')->insert($dato);
        }


        return $this->response->setJSON([
            'status' => true,
        ]);
    }


    public function eliminarNotaCredito()
    {
        $json = $this->request->getJSON();

        if (!isset($json->id)) {
            return $this->response->setJSON([
                'status' => false,
                'mensaje' => 'Documento no recibido'
            ]);
        }

        $id = $json->id;

        $db = \Config\Database::connect();
        $db->transStart();

        model('itemNotaCreditoModel')
            ->where('id_nota', $id)
            ->delete();

        model('notaCreditoModel')
            ->where('id', $id)
            ->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status' => false,
                'mensaje' => 'Error al eliminar la nota crédito'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'mensaje' => 'Nota crédito eliminada correctamente',
            'id' => $id
        ]);
    }

    public function detalleNc()
    {
        $datos = $this->request->getJSON(true); // true devuelve un arreglo

        $id = $datos['id'];
        //$id = 25;
        $idFactura = model('notaCreditoModel')->select('id_factura')->where('id', $id)->first();

        $factura = model('facturaElectronicaModel')->getDocumento($idFactura['id_factura']);

        $nota_credito = model('notaCreditoModel')->select('prefijo,numero,fecha,fecha_validacion,razon,nota')->where('id', $id)->first();

        $productos = model('itemNotaCreditoModel')->select('codigo,descripcion,neto,total,cantidad,iva,icn')->where('id_nota', $id)->findAll();

        $total_ico = model('kardexModel')
            ->selectSum('ico')->where('id_factura', $id)
            ->where('id_estado', 8)
            ->first();

        $total_iva = model('kardexModel')
            ->selectSum('iva')->where('id_factura', $id)
            ->where('id_estado', 8)
            ->first();


        //dd($productos);

        //$datosNc

        return $this->response->setJSON([
            'status' => true,
            'nc' => view('nota_credito/productos', [
                'productos' => $productos,
                'sub_total' => number_format($factura[0]['total'] - (($total_iva['iva'] ?? 0) + ($total_ico['ico'] ?? 0)), 0, ',', '.'),
                'factura'   => $factura,
                'iva' => number_format($total_iva['iva'] ?? 0, 0, ',', '.'),
                'icn' => number_format($total_ico['ico'] ?? 0, 0, ',', '.'),
            ]),
            'numero_factura' => $factura[0]['numero'],
            'numero_nota_credito' => $nota_credito['prefijo'] . $nota_credito['numero'],
            'fecha_factura' => $factura[0]['fecha'],
            'fecha_nota_credito' => $nota_credito['fecha'],
            'cliente' => $factura[0]['nombrescliente'],
            'nit' => $factura[0]['nit_cliente'],
            'razon' => $nota_credito['razon'],
            'nota' => $nota_credito['nota']
        ]);
    }
}
