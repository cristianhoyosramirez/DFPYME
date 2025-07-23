<?php

namespace App\Models;

use CodeIgniter\Model;

class licenciaModel extends Model
{
    protected $table      = 'estado_licencia';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['id_cliente', 'estado_licencia','mensaje_licencia','id_instalacion'];
}
