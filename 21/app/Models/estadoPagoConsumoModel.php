<?php

namespace App\Models;

use CodeIgniter\Model;

class estadoPagoConsumoModel extends Model
{
    protected $table      = 'estado_pago_consumo';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['id_cliente','estado_consumo','mensaje_consumo','id_licencia'];
   
}