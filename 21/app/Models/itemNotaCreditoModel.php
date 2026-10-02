<?php

namespace App\Models;

use CodeIgniter\Model;

class itemNotaCreditoModel extends Model
{
    protected $table = 'item_nota_credito_electronica';

    protected $allowedFields = [
        'id_nota',
        'codigo',
        'descripcion',
        'unidad_medida',
        'cantidad',
        'iva',
        'ic',
        'icn',
        'precio_unitario',
        'neto',
        'total',
        'imp_ic'
    ];

    protected $returnType = 'array';

      public function productos($id_nota)
    {
        $datos = $this->db->query("
        SELECT nota_credito_electronica.nit_cliente,
            codigo,cantidad,neto
        FROM item_nota_credito_electronica
        INNER JOIN nota_credito_electronica
            ON nota_credito_electronica.id = item_nota_credito_electronica.id_nota
        WHERE item_nota_credito_electronica.id_nota = $id_nota;
         ");
        return $datos->getResultArray();
    }
}
