<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimientoInsumosModel extends Model
{
    protected $table      = 'movimiento_insumos';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha',
        'hora',
        'id_producto',
        'inventario_anterior',
        'inventario_actual',
        'id_doc',
        'tipo_doc'
    ];
}
