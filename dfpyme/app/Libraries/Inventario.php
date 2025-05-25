<?php

namespace App\Libraries;

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
            ->find();



        foreach ($producto_fabricado as $detall) {
            $id_producto = model('productoModel')
                ->select('id')
                ->where('codigointernoproducto', $detall['prod_proceso'])
                ->first();

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
