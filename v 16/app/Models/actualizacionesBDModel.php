<?php

namespace App\Models;

use CodeIgniter\Model;

class actualizacionesBDModel extends Model
{
    protected $table      = 'actualizaciones';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['fecha_lanzamiento', 'notas','nombre'];
}
