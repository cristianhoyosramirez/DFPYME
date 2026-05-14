<?php

namespace App\Models;

use CodeIgniter\Model;

class estadoReservasModel extends Model
{
    protected $table      = 'estado_reservas';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    //protected $allowedFields = ['fecha_lanzamiento', 'notas','nombre'];
    protected $allowedFields = [
        'descripcion',
    ];

}
