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
        'notas',
        'fecha_reserva',
        'id_estado_reservas',
        'id_cliente'


    ];

    /* public function getReservas($id_reserva)
    {
        $datos = $this->db->query("
     
        SELECT 
            r.id AS id_reserva,
            h.id AS id_habitacion,
            m.nombre,
            r.id_estado_reservas,
            r.fecha_reserva,
            r.notas,
            er.descripcion AS estado_reserva
        FROM reservas r
        INNER JOIN habitaciones h ON r.id_habitacion = h.id
        INNER JOIN mesas m ON h.id_mesa = m.id
        INNER JOIN estado_reservas er ON er.id = r.id_estado_reservas
        WHERE r.fecha_reserva = CURRENT_DATE
        ORDER BY h.id, r.id_estado_reservas;

        ");
        return $datos->getResultArray();
    } */

    public function getReservas($id_reserva)
    {
        $datos = $this->db->query("
     
                        SELECT 
                m.nombre AS nombre_habitacion,
                r.fecha_reserva,
                r.notas,
                h.precio AS valor_hospedaje,

                d.descripcion AS identificacion, -- 👈 aquí está el cambio
                c.nitcliente AS numero_identificacion,
                c.nombrescliente AS nombre_completo

            FROM reservas r
            INNER JOIN habitaciones h 
                ON r.id_habitacion = h.id
            INNER JOIN mesas m 
                ON h.id_mesa = m.id
            INNER JOIN estado_reservas er 
                ON er.id = r.id_estado_reservas
            INNER JOIN cliente c 
                ON c.id = r.id_cliente
            INNER JOIN documento_identidad d
                ON d.codigo = c.type_document  -- 👈 relación correcta

            WHERE r.id = $id_reserva;

        ");
        return $datos->getResultArray();
    }

    public function getResrvasHabitaicones()
    {
        $datos = $this->db->query("
     
SELECT 
    r.id AS id_reserva,
    h.id AS id_habitacion,
    m.nombre,
    r.id_estado_reservas,
    r.fecha_reserva,
    r.notas,
    er.descripcion AS estado_reserva
FROM reservas r
INNER JOIN habitaciones h ON r.id_habitacion = h.id
INNER JOIN mesas m ON h.id_mesa = m.id
INNER JOIN estado_reservas er ON er.id = r.id_estado_reservas
WHERE r.fecha_reserva = CURRENT_DATE
ORDER BY h.id, r.id_estado_reservas ASC;
        ");
        return $datos->getResultArray();
    }
    public function buscarHabitaicones($valor)
    {
        $datos = $this->db->query("
            SELECT 
                r.id AS id_reserva,
                h.id AS id_habitacion,
                m.nombre,
                r.id_estado_reservas,
                r.fecha_reserva,
                r.notas,
                er.descripcion AS estado_reserva
            FROM reservas r
            INNER JOIN habitaciones h ON r.id_habitacion = h.id
            INNER JOIN mesas m ON h.id_mesa = m.id
            INNER JOIN estado_reservas er ON er.id = r.id_estado_reservas
            where m.nombre ILIKE '%$valor%'
            ORDER BY h.id, r.id_estado_reservas ASC;
                    ");
        return $datos->getResultArray();
    }
    public function buscarEstado($valor)
    {
        $datos = $this->db->query("
            SELECT 
    r.id AS id_reserva,
    h.id AS id_habitacion,
    m.nombre,
    r.id_estado_reservas,
    r.fecha_reserva,
    r.notas,
    er.descripcion AS estado_reserva
FROM reservas r
INNER JOIN habitaciones h ON r.id_habitacion = h.id
INNER JOIN mesas m ON h.id_mesa = m.id
INNER JOIN estado_reservas er ON er.id = r.id_estado_reservas

where r.id_estado_reservas = $valor
ORDER BY h.id, r.id_estado_reservas ASC;
                    ");
        return $datos->getResultArray();
    }
}
