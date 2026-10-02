<?php

namespace App\Models;

use CodeIgniter\Model;

class tipoVehiculoModel extends Model
{
    protected $table      = 'tipo_vehiculo';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['tipo'];
}
