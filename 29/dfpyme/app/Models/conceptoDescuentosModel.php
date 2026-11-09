<?php

namespace App\Models;

use CodeIgniter\Model;

class conceptoDescuentosModel extends Model
{
    protected $table      = 'concepto_descuentos';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['concepto'];
   
}