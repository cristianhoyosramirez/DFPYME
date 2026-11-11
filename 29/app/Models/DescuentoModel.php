<?php

namespace App\Models;

use CodeIgniter\Model;

class DescuentoModel extends Model
{
    protected $table      = 'descuento_pedido';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['id_producto_pedido', 'tipo_descuento', 'valor_unitario_decuento', 'porcentaje'];

    public function totalDescuento($numero_pedido)
    {
        $datos = $this->db->query("
       SELECT
        SUM((precio_original * cantidad_producto) - valor_total) AS descuento_total
        FROM producto_pedido
        WHERE numero_de_pedido = $numero_pedido;
        ");
        return $datos->getResultArray();
    }


    public function documento_electronico_descuento($id_factura, $concepto, $valor)
    {
        if ($valor <= 0) {
            return false;
        }

        $this->db->query("
        INSERT INTO documento_electronico_descuento (id_de, concepto, valor)
        VALUES (?, ?, ?)
    ", [$id_factura, $concepto, $valor]);

        return $this->db->insertID();
    }



    public function getDescuento($id_factura)
    {
        $datos = $this->db->query("
            select valor as descuento from documento_electronico_descuento where id_de = $id_factura
        ");
        return $datos->getResultArray();
    }
}
