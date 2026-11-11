<?php

namespace App\Models;

use CodeIgniter\Model;

class notaCreditoDescuentoModel extends Model
{
    protected $table      = 'nota_credito_electronica_descuento';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_nota', 'concepto', 'valor', 'descuento' ];
}
