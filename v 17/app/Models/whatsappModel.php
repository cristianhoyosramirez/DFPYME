<?php

namespace App\Models;

use CodeIgniter\Model;

class whatsappModel extends Model
{
    protected $DBGroup      = 'remoteDB'; // conexión remota
    protected $table        = 'PEDIDO';
    protected $primaryKey   = 'id';
    protected $useAutoIncrement = true;
    protected $returnType   = 'array';
    protected $allowedFields = [
        'fecha',
        'hora',
        'nota',
        'estado',
        'valor_propina'
    ];
}
