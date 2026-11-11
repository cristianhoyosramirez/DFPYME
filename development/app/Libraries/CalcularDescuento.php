<?php

namespace App\Libraries;

class CalcularDescuento
{

    public function calcularDescuento($porcentaje, $valor_unidad, $cantidad, $id)
    {
        // Convertir los valores recibidos a tipos numericos.
        $porcentaje  = (float) $porcentaje;
        $valor_unidad = (float) $valor_unidad;
        $cantidad    = (float) $cantidad;
        $id           = (int) $id;

        // Validar los datos.
        if ($porcentaje < 0 || $porcentaje > 100 || $valor_unidad < 0 || $cantidad <= 0 || $id <= 0) {
            return [
                'response' => false,
                'message'  => 'Los datos del descuento no son validos.'
            ];
        }

        // Calcular el valor del descuento.
        $descuento = round($valor_unidad * ($porcentaje / 100), 2);

        // Calcular el precio unitario con el descuento aplicado.
        $valor_final = round($valor_unidad - $descuento, 2);

        // Calcular el total del producto segun su cantidad.
        $valor_total = round($valor_final * $cantidad, 2);

        // Obtener el modelo y su conexion.
        $modelProductoPedido = model('productoPedidoModel');
        $db = $modelProductoPedido->db;

        // Iniciar la transaccion.
        $db->transBegin();

        try {

            // Omitir temporalmente la sincronizacion de precio_original.
            $db->query("SET LOCAL app.omitir_precio_original = '1'");

            // Actualizar el producto.
            $actualizar = $modelProductoPedido
                ->set([
                    'valor_unitario' => $valor_final,
                    'valor_total'    => $valor_total
                ])
                ->where('id', $id)
                ->update();

            // Verificar errores de actualizacion.
            if ($actualizar === false || $db->transStatus() === false) {
                $db->transRollback();

                return [
                    'response' => false,
                    'message'  => 'No fue posible actualizar el producto.'
                ];
            }

            // Confirmar que el producto exista.
            if ($db->affectedRows() == 0) {
                $db->transRollback();

                return [
                    'response' => false,
                    'message'  => 'No se actualizo ningun producto. Verifica el ID.'
                ];
            }

            // Confirmar la transaccion.
            $db->transCommit();

            // Retornar el resultado del descuento.
            return [
                'response'        => true,
                'message'         => 'Descuento aplicado correctamente.',
                'id'              => $id,
                'porcentaje'       => $porcentaje,
                'valor_descuento'  => $descuento,
                'valor_unitario'   => $valor_final,
                'cantidad'         => $cantidad,
                'valor_total'      => $valor_total
            ];
        } catch (\Throwable $e) {

            $db->transRollback();

            return [
                'response' => false,
                'message'  => 'Ocurrio un error al aplicar el descuento.'
            ];
        }
    }
}
