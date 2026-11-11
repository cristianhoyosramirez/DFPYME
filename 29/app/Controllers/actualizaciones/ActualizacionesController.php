<?php

namespace App\Controllers\actualizaciones;

use App\Controllers\BaseController;
use App\Libraries\CalcularDescuento;

class ActualizacionesController extends BaseController
{
    public function index()
    {
        //$actualizaciones = model('actualizacionesBDModel')->findAll();
        return view('actualizaciones/actualizaciones');
    }

    function Bd()
    {

        /**
         * Actualiazacion del campo 
         */

        $impuestos = model('configuracionPedidoModel')->set('impuesto', 'true')->update();

        /**
         * Agregar a la tabla configuracion pedido una columna para agregar la url 
         */
        $update_bd = model('ActualizacionesModel')->add_colum_url();

        /**
         * Agregar a la tabla configuracion pedido una columna para la altura de las mesas 
         * 11 de Octubre de 2024  
         */
        $update_bd = model('ActualizacionesModel')->add_colum_altura();
        $update_altura = model('configuracionPedidoModel')->set('altura', 30)->update();

        /**
         * Agregar a la tabla configuracion pedido una columna para visualizar el codigo en pantalla o no 
         * Este campo me permite ver en pantalla al momento de llamar los productos si concateno el codigo con el nombre del producto 
         * 12 de Octubre de 2024 
         */
        $alter_codigo = model('ActualizacionesModel')->add_colum_codigo();
        $update_codigo = model('configuracionPedidoModel')->set('codigo_pantalla', false)->update();


        /**
         * Fecha de creación 2024-10-13 
         * Adicion de la  columna nombre_comercial  a la tabla medio_pago para mstrar un nombre de medio de pago entendible  
         */
        $nombre_comercial = model('ActualizacionesModel')->add_colum_nombre_comercial();

        /**
         * Fecha de creación 2024-10-13 
         * Despues de crear dicha columna la actualizamos con los nombres correctos 
         */
        $actualizar_nombre_comercial = model('ActualizacionesModel')->update_nombre_comercial();


        /**
         * Fecha de creación 2024-10-13 
         * agregar otro campo a la tabla forma pago el cual es ruta donde se almacena la ruta para ubicar el icono 
         */

        $add_column_ruta = model('ActualizacionesModel')->add_column_ruta();



        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Actualización a base de datos éxitosa ');
    }

    function crearVersion()
    {

        $json = $this->request->getJSON();
        $actualizacion = $json->actualizacion;
        //$actualizacion = 11;


        switch ($actualizacion) {
            case 1:
                $actualizar = model('ActualizacionesModel')->uno();

                return $this->response->setJSON([
                    'response' => 'success',

                ]);

                break;
            case 11:
                $actualizar = model('ActualizacionesModel')->once();

                return $this->response->setJSON([
                    'response' => 'success',

                ]);

                break;
            case 12:

                $actualizar = model('ActualizacionesModel')->doce();

                return $this->response->setJSON([
                    'response' => 'success',

                ]);

                break;
        }
    }


    public function crearActualizaciones()
    {
        echo "hola mundo";
        exit();
        return view('actualizaciones/generarActualizaciones');
    }

    function reinciarSecuencia()
    {

        $reset = model('pedidoModel')->resetNumeracion();

        if ($reset) {
            return $this->response->setJSON([
                'response' => 'success',

            ]);
        }
    }

    function ip()
    {
        $json = $this->request->getJSON();
        $ip   = $json->ip;

        model('configuracionPedidoModel')
            ->set(['ip' => $ip])
            ->where('id', 1)   // <--- MUY IMPORTANTE
            ->update();

        return $this->response->setJSON(['status' => 'success']);
    }

    function lista_precios()
    {
        $lista_precios = $this->request->getPost('lista_precios');



        $update = model('configuracionPedidoModel')->set('lista_precios_moviles', $lista_precios)->update();

        return $this->response->setJSON(['status' => 'success']);
    }



    public function descuento_porcentaje()
    {
        // Recibir datos enviados por AJAX en formato JSON.
        $datos = $this->request->getJSON(true);

        // Valores temporales para pruebas.
        // Cuando conectes el formulario, utiliza los valores recibidos por AJAX.
        /* $id_mesa = 1;
        $porcentaje = 10; */

        $id_mesa = $datos['id_mesa'] ?? null;
        $porcentaje = $datos['porcentaje'] ?? null;

        // Validar identificador de la mesa.
        if (!is_numeric($id_mesa) || (int) $id_mesa <= 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'response' => false,
                    'message'  => 'El identificador de la mesa no es válido.'
                ]);
        }

        // Validar porcentaje.
        if (
            !is_numeric($porcentaje) ||
            (float) $porcentaje <= 0 ||
            (float) $porcentaje > 100
        ) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'response' => false,
                    'message'  => 'El porcentaje debe ser mayor que 0 y máximo 100.'
                ]);
        }

        // Consultar el pedido asociado a la mesa.
        $pedido = model('pedidoModel')
            ->select('id')
            ->where('fk_mesa', (int) $id_mesa)
            ->first();

        if (empty($pedido)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'response' => false,
                    'message'  => 'No se encontró el pedido de la mesa.'
                ]);
        }

        $numero_pedido = $pedido['id'];

        $modelProductoPedido = model('productoPedidoModel');
        $modelPedido = model('pedidoModel');

        // Consultar los productos del pedido.
        $productos = $modelProductoPedido
            ->select('id, cantidad_producto, precio_original')
            ->where('numero_de_pedido', $numero_pedido)
            ->findAll();

        if (empty($productos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'response' => false,
                    'message'  => 'El pedido no tiene productos para descontar.'
                ]);
        }

        // Utilizar la misma conexión del modelo.
        $db = $modelProductoPedido->db;

        // Iniciar la transacción general del pedido.
        $db->transBegin();

        try {

            foreach ($productos as $producto) {

                // Validar precio original.
                if (
                    !isset($producto['precio_original']) ||
                    !is_numeric($producto['precio_original']) ||
                    (float) $producto['precio_original'] < 0
                ) {
                    $db->transRollback();

                    return $this->response
                        ->setStatusCode(400)
                        ->setJSON([
                            'response' => false,
                            'message'  => 'Un producto no tiene un precio original válido.',
                            'id'       => $producto['id']
                        ]);
                }

                // Crear la instancia de la clase de descuentos.
                $calcularDescuento = new CalcularDescuento();

                // Aplicar el descuento al producto.
                $resultado = $calcularDescuento->calcularDescuento(
                    (float) $porcentaje,
                    (float) $producto['precio_original'],
                    (float) $producto['cantidad_producto'],
                    (int) $producto['id']
                );

                // Verificar el resultado del descuento.
                if (
                    !is_array($resultado) ||
                    !isset($resultado['response']) ||
                    $resultado['response'] !== true
                ) {
                    $db->transRollback();

                    return $this->response
                        ->setStatusCode(500)
                        ->setJSON([
                            'response' => false,
                            'message'  => 'No fue posible aplicar el descuento a todos los productos.',
                            'id'       => $producto['id']
                        ]);
                }
            }

            // Calcular únicamente el total de este pedido.
            $total_producto_pedido = $modelProductoPedido
                ->selectSum('valor_total', 'total')
                ->where('numero_de_pedido', $numero_pedido)
                ->first();

            $total_producto_pedido = (float) (
                $total_producto_pedido['total'] ?? 0
            );

            // Actualizar el total del encabezado del pedido.
            $valor_actual_pedido = $modelPedido->select('valor_total')->where('id', $numero_pedido)->first()['valor_total'];

            $datos_pedido = [
                'valor_total' => $total_producto_pedido,
                'porcentaje_descuento' => $porcentaje,
                'valor_descuento' => $valor_actual_pedido - $total_producto_pedido,
                'valor_antes_descuento' => $valor_actual_pedido,
                'tipo_descuento' => '%',
                'tiene_descuento' => true

            ];


            $actualizar_pedido = $modelPedido
                ->set($datos_pedido)
                ->where('id', $numero_pedido)
                ->update();



            // Verificar errores antes de confirmar.
            if (
                $actualizar_pedido === false ||
                $db->transStatus() === false
            ) {
                $db->transRollback();

                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'response' => false,
                        'message'  => 'No fue posible actualizar el total del pedido.'
                    ]);
            }

            // Confirmar todos los cambios.
            $db->transCommit();

            $total_descuento = model('DescuentoModel')->totalDescuento($numero_pedido);

            $productos_pedido = $modelProductoPedido
                ->producto_pedido($numero_pedido);

            $propina = $modelPedido->select('propina')->where('id', $numero_pedido)->first()['propina'];


            return $this->response->setJSON([
                'response'  => true,
                'message'   => 'Descuento aplicado de: ' . number_format((float) $total_descuento[0]['descuento_total'], 0, ',', '.'),
                'descuento' => number_format((float) $total_descuento[0]['descuento_total'], 0, ',', '.'),
                'productos' => view('pedidos/productos_pedido', [
                    'productos' => $productos_pedido,
                    'pedido'    => $numero_pedido
                ]),
                'sub_total' => number_format((float) $total_producto_pedido, 0, ',', '.'),
                'total'     => number_format((float) $propina + $total_producto_pedido, 0, ',', '.')
            ]);
        } catch (\Throwable $e) {

            $db->transRollback();

            log_message(
                'error',
                'Error aplicando descuento porcentual: ' . $e->getMessage()
            );

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'response' => false,
                    'message'  => 'Ocurrió un error al aplicar el descuento.'
                ]);
        }
    }


    public function descuento_dinero()
    {
        // Recibir datos enviados por AJAX en formato JSON.
        $datos = $this->request->getJSON(true);

        $id_mesa = $datos['id_mesa'] ?? null;
        $descuento = $datos['descuento'] ?? null;

        // Validar identificador de la mesa.
        if (!is_numeric($id_mesa) || (int) $id_mesa <= 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'response' => false,
                    'message'  => 'El identificador de la mesa no es válido.'
                ]);
        }

        // Validar descuento monetario.
        if (
            !is_numeric($descuento) ||
            !is_finite((float) $descuento) ||
            (float) $descuento <= 0
        ) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'response' => false,
                    'message'  => 'El descuento debe ser un valor monetario mayor que cero.'
                ]);
        }

        $descuento = (float) $descuento;

        // Consultar el pedido asociado a la mesa.
        $modelPedido = model('pedidoModel');

        $pedido = $modelPedido
            ->select('id, valor_total')
            ->where('fk_mesa', (int) $id_mesa)
            ->first();

        if (empty($pedido)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'response' => false,
                    'message'  => 'No se encontró el pedido de la mesa.'
                ]);
        }

        $numero_pedido = $pedido['id'];

        $modelProductoPedido = model('productoPedidoModel');

        // Consultar los productos utilizando sus precios originales.
        $productos = $modelProductoPedido
            ->select('id, cantidad_producto, precio_original')
            ->where('numero_de_pedido', $numero_pedido)
            ->findAll();

        if (empty($productos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'response' => false,
                    'message'  => 'El pedido no tiene productos para descontar.'
                ]);
        }

        // Calcular el total original del pedido.
        $total_original = 0;

        foreach ($productos as $producto) {

            if (
                !isset($producto['precio_original']) ||
                !is_numeric($producto['precio_original']) ||
                !is_numeric($producto['cantidad_producto']) ||
                (float) $producto['precio_original'] < 0 ||
                (float) $producto['cantidad_producto'] <= 0
            ) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'response' => false,
                        'message'  => 'Uno de los productos tiene un precio o una cantidad inválidos.',
                        'id'       => $producto['id']
                    ]);
            }

            $total_original +=
                (float) $producto['precio_original'] *
                (float) $producto['cantidad_producto'];
        }

        // Validar que el descuento no supere el total original.
        if ($descuento > $total_original) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'response' => false,
                    'message'  => 'El descuento no puede ser mayor al total del pedido.',
                    'total'    => number_format($total_original, 0, ',', '.'),
                    'descuento' => number_format($descuento, 0, ',', '.')
                ]);
        }

        // Evitar descuentos sobre un pedido sin valor.
        if ($total_original <= 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'response' => false,
                    'message'  => 'El pedido no tiene un valor disponible para descontar.'
                ]);
        }

        // Convertir el descuento monetario en su porcentaje equivalente.
        $porcentaje = ($descuento / $total_original) * 100;

        // Utilizar la conexión del modelo de productos.
        $db = $modelProductoPedido->db;

        $db->transBegin();

        try {

            // Aplicar el porcentaje equivalente a cada producto.
            foreach ($productos as $producto) {

                $calcularDescuento = new CalcularDescuento();

                $resultado = $calcularDescuento->calcularDescuento(
                    $porcentaje,
                    (float) $producto['precio_original'],
                    (float) $producto['cantidad_producto'],
                    (int) $producto['id']
                );

                if (
                    !is_array($resultado) ||
                    !isset($resultado['response']) ||
                    $resultado['response'] !== true
                ) {
                    $db->transRollback();

                    return $this->response
                        ->setStatusCode(500)
                        ->setJSON([
                            'response' => false,
                            'message'  => 'No fue posible aplicar el descuento a todos los productos.',
                            'id'       => $producto['id']
                        ]);
                }
            }

            // Consultar el total actualizado de los productos.
            $consulta_total = $modelProductoPedido
                ->selectSum('valor_total', 'total')
                ->where('numero_de_pedido', $numero_pedido)
                ->first();

            $total_producto_pedido = (float) (
                $consulta_total['total'] ?? 0
            );

            // Calcular el descuento realmente aplicado.
            $valor_descuento_real = $total_original - $total_producto_pedido;

            // Consultar la propina actual.
            $pedido_actual = $modelPedido
                ->select('propina')
                ->where('id', $numero_pedido)
                ->first();

            $propina = (float) ($pedido_actual['propina'] ?? 0);

            // Actualizar el encabezado del pedido.
            $datos_pedido = [
                'valor_total'             => $total_producto_pedido,
                'porcentaje_descuento'    => $porcentaje,
                'valor_descuento'         => $valor_descuento_real,
                'valor_antes_descuento'   => $total_original,
                'tipo_descuento'          => '$',
                'tiene_descuento'         => true
            ];

            $actualizar_pedido = $modelPedido
                ->set($datos_pedido)
                ->where('id', $numero_pedido)
                ->update();

            // Verificar errores antes de confirmar.
            if (
                $actualizar_pedido === false ||
                $db->transStatus() === false
            ) {
                $db->transRollback();

                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'response' => false,
                        'message'  => 'No fue posible actualizar el total del pedido.'
                    ]);
            }

            // Confirmar todos los cambios.
            $db->transCommit();

            // Consultar descuento y productos actualizados.
            $total_descuento = model('DescuentoModel')
                ->totalDescuento($numero_pedido);

            $productos_pedido = $modelProductoPedido
                ->producto_pedido($numero_pedido);

            return $this->response->setJSON([
                'response'  => true,
                'message'   => 'Descuento aplicado de: ' .
                    number_format($valor_descuento_real, 0, ',', '.'),
                'descuento' => number_format((float) $valor_descuento_real, 0, ',', '.'),
                'porcentaje_descuento' => round($porcentaje, 2),
                'productos' => view('pedidos/productos_pedido', [
                    'productos' => $productos_pedido,
                    'pedido'    => $numero_pedido
                ]),
                'sub_total' => number_format(
                    $total_producto_pedido,
                    0,
                    ',',
                    '.'
                ),
                'total' => number_format(
                    $total_producto_pedido + $propina,
                    0,
                    ',',
                    '.'
                )
            ]);
        } catch (\Throwable $e) {

            $db->transRollback();

            log_message(
                'error',
                'Error aplicando descuento monetario: ' . $e->getMessage()
            );

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'response' => false,
                    'message'  => 'Ocurrió un error al aplicar el descuento.'
                ]);
        }
    }


    function borrar_descuento()
    {
        $datos = $this->request->getJSON(true);
        $id_mesa = $datos['id_mesa'] ?? null;
        $pedido = model('pedidoModel')
            ->select('id')
            ->where('fk_mesa', (int) $id_mesa)
            ->first();

        if (empty($pedido)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'response' => false,
                    'message'  => 'No se encontró el pedido de la mesa.'
                ]);
        }

        $numero_pedido = $pedido['id'];

        $productos = model('productoPedidoModel');

        $productos = model('productoPedidoModel');

        $productos->where('numero_de_pedido', $numero_pedido)
            ->set('valor_unitario', 'precio_original', false)
            ->set('valor_total', 'precio_original * cantidad_producto', false)
            ->update();

        $total_producto_pedido = $productos
            ->selectSum('valor_total', 'total')
            ->where('numero_de_pedido', $numero_pedido)
            ->first();

        $total_producto_pedido = (float) (
            $total_producto_pedido['total'] ?? 0
        );

        $datos_pedido = [
            'valor_total' => $total_producto_pedido,
            'porcentaje_descuento' => 0,
            'valor_descuento' => 0,
            'valor_antes_descuento' => 0,
            'tipo_descuento' => '%',
            'tiene_descuento' => false

        ];


        $actualizar_pedido = model('pedidoModel')
            ->set($datos_pedido)
            ->where('id', $numero_pedido)
            ->update();

        $productos_pedido = $productos
            ->producto_pedido($numero_pedido);

        $propina = model('pedidoModel')->select('propina')->where('id', $numero_pedido)->first()['propina'];

        return $this->response->setJSON([
            'response'  => true,
            'message'   => 'Descuento revertido',
            'descuento' => 0,
            'productos' => view('pedidos/productos_pedido', [
                'productos' => $productos_pedido,
                'pedido'    => $numero_pedido
            ]),
            'sub_total' => number_format((float) $total_producto_pedido, 0, ',', '.'),
            'total'     => number_format((float) $propina + $total_producto_pedido, 0, ',', '.')
        ]);
    }
}
