<?php

namespace App\Models;

use CodeIgniter\Model;

class registroHoteleroModel extends Model
{
    protected $table      = 'registro_hotelero';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha',
        'hora',
        'id_municipio_origen',
        'id_municipio_destino',
        'fecha_salida',
        'hora_salida',
        'id_reserva',
        'fecha_hospedaje',
        'id_vehiculo'
    ];
}
