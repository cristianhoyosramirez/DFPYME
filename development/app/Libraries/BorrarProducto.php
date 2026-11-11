<?php

namespace App\Libraries;

class BorrarProducto
{
    public function eliminar($id, $numeroPedido)
    {
        $productoPedidoModel = model('productoPedidoModel');

        // Eliminar el producto del pedido.
        $productoPedidoModel
            ->where('id', $id)
            ->delete();

        // Actualizar los totales del pedido y obtener el nuevo valor total.
        return $this->actualizarValorPedido($numeroPedido);
    }


    public function actualizarValorPedido($numeroPedido)
    {
        $productoPedidoModel = model('productoPedidoModel');
        $pedidoModel = model('pedidoModel');

        // Recalcular los totales del pedido.
        $totales = $productoPedidoModel
            ->selectSum('valor_total')
            ->selectSum('cantidad_producto')
            ->where('numero_de_pedido', $numeroPedido)
            ->first();

        // Actualizar el total y la cantidad de productos del pedido.
        $pedidoModel
            ->where('id', $numeroPedido)
            ->set([
                'valor_total' => $totales['valor_total'] ?? 0,
                'cantidad_de_productos' => $totales['cantidad_producto'] ?? 0
            ])
            ->update();

        // Retornar el nuevo valor total del pedido.
        return $totales['valor_total'] ?? 0;
    }

  

    function insertarProductosPedido($data)
    {

        $insert = model('productosBorradosModel')->insert($data);
    }
}
