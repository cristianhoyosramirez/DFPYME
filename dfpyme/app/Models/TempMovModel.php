<?php

namespace App\Models;

use CodeIgniter\Model;

class TempMovModel extends Model
{
    protected $table      = 'tem_mov';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'movimiento',
        'producto',
        'cantidad_inicial',
        'cantidad_final',
        'usuario',
        'id_usuario',
        'cantidad_movi',
        'fecha',
        'documento',
        'nota'

    ];
}
