<?php

namespace App\Models;

use CodeIgniter\Model;

class mesasModel extends Model
{
    protected $table      = 'mesas';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['fk_salon', 
    'nombre',
     'estado',
      'valor_pedido', 
      'fk_usuario', 
      'id_mesero',
      'tiene_pedido',
      'estado_mesa'
      ];

  
}
