<?php

namespace App\Models;

use CodeIgniter\Model;

class historicoModel extends Model
{
    protected $table      = 'factura_historico';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    //protected $allowedFields = ['fecha_lanzamiento', 'notas','nombre'];
    protected $allowedFields = [
        'tipo_documento',
        'numero_documento',
        'sub_total',
        'propina',
        'id_apertura',
        'fecha_documento',
        'hora_documento',
        'id_usuario_creacion',
        'id_usuario_eliminacion',
        'fecha_eliminacion',
        'hora_eliminacion'
    ];
}
