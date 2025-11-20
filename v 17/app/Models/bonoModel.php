<?php

namespace App\Models;

use CodeIgniter\Model;

class bonoModel extends Model
{
    protected $table      = 'bonos';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['id_usuario','fecha_generacion','hora','obserevacion','valor'];
   
}