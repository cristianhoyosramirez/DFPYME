<?php

namespace App\Models;

use CodeIgniter\Model;

class vehiculosModel extends Model
{
    protected $table      = 'vehiculos';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    //protected $allowedFields = ['fecha_lanzamiento', 'notas','nombre'];
    protected $allowedFields = [
        'tipo',
        'placa'
    ];

}
