<?php

namespace App\Models;

use CodeIgniter\Model;

class clasePagoModel extends Model
{
    protected $table      = 'clase_pago';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre','estado'];
   
}