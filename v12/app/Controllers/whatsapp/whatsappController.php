<?php

namespace App\Controllers\whatsapp;

use App\Controllers\BaseController;



class whatsappController extends BaseController
{
    public function index()
    {
       

        $db = \Config\Database::connect('remoteDB');


        $builder = $db->table('PEDIDO p');
        $builder->select("
    p.id AS id_pedido,
    p.fecha,
    p.hora,
    p.nota AS nota_pedido,
    p.estado AS estado_pedido,
    p.valor_propina,
    COALESCE(
        JSON_ARRAYAGG(
            JSON_OBJECT(
                'id_producto_pedido', pp.id,
                'codigo_producto', pp.codigo_producto,
                'cantidad', pp.cantidad,
                'valor', pp.valor,
                'nota_producto', pp.nota,
                'atributo', pp.atributo
            )
        ), JSON_ARRAY()
    ) AS productos
", false);

        $builder->join('PRODUCTO_PEDIDO pp', 'p.id = pp.id_pedido', 'inner');
        $builder->where('p.estado', 0);
        $builder->groupBy([
            'p.id',
            'p.fecha',
            'p.hora',
            'p.nota',
            'p.estado',
            'p.valor_propina'
        ]);
        $builder->orderBy('p.id');

        $query = $builder->get();
        $pedidos = $query->getResultArray();



        $idSalon = model('salonesModel')->select('id')->where('tipo', 1)->first();

        if (empty($pedidos)) {
            return;
        }

    
        $pedidoModel = model('pedidoModel');
        $productoPedidoModel = model('productoPedidoModel');
        $mesasModel = model('mesasModel');
        $productoModel = model('productoModel');

        foreach ($pedidos as $pedido) {

            $productos = json_decode($pedido['productos'], true);

            // Obtener mesa libre
            $idMesa = $mesasModel->where('fk_salon', $idSalon['id'])
                ->where('tiene_pedido', false)
                ->first();

            if (!$idMesa) {
                echo "No hay mesas libres para el pedido {$pedido['id_pedido']}\n";
                continue;
            }

            // Insertar pedido
            $pedidoData = [
                'fk_mesa' => $idMesa['id'],
                'fk_usuario' => 6,
                'valor_total' => 0,
                'cantidad_de_productos' => 0
            ];

            $pedidoModel->insert($pedidoData);
            $idInsertado = $pedidoModel->getInsertID();

            $valorTotalPedido = 0;
            $cantidadProductosPedido = 0;

            // Insertar productos
            foreach ($productos as $producto) {
                $codigoProducto = (string) $producto['codigo_producto'];
                $codigoCategoria = $productoModel
                    ->select('codigocategoria,se_imprime')
                    ->where('codigointernoproducto', $codigoProducto)
                    ->first();

                if (!$codigoCategoria) {
                    echo "Producto {$codigoProducto} no encontrado en DB.\n";
                    continue;
                }

                $producto_pedido = [
                    'numero_de_pedido' => $idInsertado,
                    'cantidad_producto' => $producto['cantidad'],
                    'nota_producto' => $producto['nota_producto'],
                    'valor_unitario' => $producto['valor'] / $producto['cantidad'],
                    'valor_total' => $producto['valor'],
                    'se_imprime_en_comanda' => $codigoCategoria['se_imprime'],
                    'codigo_categoria' => $codigoCategoria['codigocategoria'],
                    'codigointernoproducto' => $codigoProducto,
                    'numero_productos_impresos_en_comanda' => 0,
                    'idUsuario' => 6,
                    'fecha' => date('Y-m-d'),
                    'hora' => date('H:i:s')
                ];

                $productoPedidoModel->insert($producto_pedido);

                $valorTotalPedido += $producto['valor'];
                $cantidadProductosPedido += $producto['cantidad'];
            }

            // Actualizar valor total y cantidad de productos del pedido
            $pedidoModel->set('valor_total', $valorTotalPedido)
                ->set('cantidad_de_productos', $cantidadProductosPedido)
                ->where('id', $idInsertado)
                ->update();

            // Marcar mesa como ocupada
            $mesasModel->set('tiene_pedido', true)
                ->where('id', $idMesa['id'])
                ->update();

            // Actualizar estado del pedido remoto
            $dbRemote = \Config\Database::connect('remoteDB');
            $builder = $dbRemote->table('PEDIDO');
            $builder->where('id', $pedido['id_pedido'])
                ->update(['estado' => 1]);

            echo "Pedido {$pedido['id_pedido']} procesado correctamente.\n";
        }
    }
}
