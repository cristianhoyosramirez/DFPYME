<?php

namespace App\Models;

use CodeIgniter\Model;

class reservasModel extends Model
{
    protected $table      = 'reservas';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_habitacion',
        'id_cliente',
        'id_estado',
        'notas',
        'fecha_reserva'
    ];

       public function getReservas($id_habitacion)
    {
        $datos = $this->db->query("
     
SELECT 
    r.id AS id_reserva,
    r.fecha_reserva, 
    r.notas, 
    m.nombre AS habitacion,
    c.nombrescliente,
    c.nitcliente,
    d.descripcion AS documento_descripcion,
    h.precio
FROM reservas r
INNER JOIN habitaciones h ON r.id_habitacion = h.id
INNER JOIN mesas m ON h.id_mesa = m.id
INNER JOIN cliente c ON c.id = r.id_cliente
INNER JOIN documento_identidad d ON c.type_document = d.codigo
WHERE r.id_habitacion = $id_habitacion;
        ");
        return $datos->getResultArray();
    }
}
