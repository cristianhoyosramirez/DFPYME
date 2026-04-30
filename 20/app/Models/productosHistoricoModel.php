<?php

namespace App\Models;

use CodeIgniter\Model;

class productosHistoricoModel extends Model
{
    protected $table      = 'productos_historico';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'codigo',
        'cantidad',
        'valor_unitario',
        'total',
        'id_factura',
        'uuid_factura',
        'ico',
        'iva',
        'valor_ico',
        'valor_iva',
        'sync'
    ];

    public function productos($idFactura)
    {
        $datos = $this->db->query("
INSERT INTO productos_historico
(
    codigo,
    cantidad,
    valor_unitario,
    total,
    id_factura,
    ico,
    iva,
    valor_ico,
    valor_iva,
    id_estado
)
SELECT
    codigo,
    cantidad,
    valor_unitario,
    total,
    id_factura,
    ico,
    iva,
    valor_ico,
    valor_iva,
    id_estado
FROM kardex
WHERE id_factura = $idFactura
AND id_estado = 8;
        ");
    }

    public function getProductos()
    {

        $this->db->query("
        INSERT INTO productos_historico
        (
            codigo,
            cantidad,
            valor_unitario,
            total,
            id_factura,
            ico,
            iva,
            valor_ico,
            valor_iva,
            id_estado
        )
        SELECT
            k.codigo,
            k.cantidad,
            k.valor_unitario,
            k.total,
            k.id_factura,
            k.ico,
            k.iva,
            k.valor_ico,
            k.valor_iva,
            k.id_estado
        FROM kardex k
        LEFT JOIN productos_historico ph
               ON ph.id_factura = k.id_factura
               AND ph.codigo = k.codigo
               AND ph.id_estado = k.id_estado
        WHERE ph.id IS NULL
    ");

        $this->db->query("
        UPDATE productos_historico ph
        SET uuid_factura = fh.uuid
        FROM factura_historico fh
        WHERE ph.id_factura = fh.id_factura
        AND ph.id_estado = fh.id_estado
        AND ph.uuid_factura IS NULL
    ");
    }
}
