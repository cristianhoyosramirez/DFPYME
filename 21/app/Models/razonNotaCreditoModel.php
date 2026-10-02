<?php

namespace App\Models;

use CodeIgniter\Model;

class razonNotaCreditoModel extends Model
{
    protected $table      = 'razon_nota_credito_electronica';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['descripcion','reason'];
}
