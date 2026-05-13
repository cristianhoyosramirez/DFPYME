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
        'id_cliente',
        'vehiculo',
        'id_apertura'


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

     public function datosReservas($id_reserva)
    {
        $datos = $this->db->query("
     
              
SELECT 
    municipio_origen.nombre AS origen,
    municipio_destino.nombre AS destino,
    
    vehiculos.tipo,
    vehiculos.placa,

    m.nombre AS nombre_habitacion,
    
    r.fecha_reserva,
    r.notas,

    registro_hotelero.hora_salida,

    h.precio AS valor_hospedaje,

    d.descripcion AS identificacion,
    
    c.nitcliente AS numero_identificacion,
    c.nombrescliente AS nombre_completo,
    c.telefonocliente AS telefono

FROM reservas r

LEFT JOIN habitaciones h 
    ON r.id_habitacion = h.id

LEFT JOIN mesas m 
    ON h.id_mesa = m.id

LEFT JOIN estado_reservas er 
    ON er.id = r.id_estado_reservas

LEFT JOIN cliente c 
    ON c.id = r.id_cliente

LEFT JOIN documento_identidad d
    ON d.codigo = c.type_document

LEFT JOIN registro_hotelero
    ON registro_hotelero.id_reserva = r.id

LEFT JOIN municipio AS municipio_origen 
    ON municipio_origen.id = registro_hotelero.id_municipio_origen

LEFT JOIN municipio AS municipio_destino 
    ON municipio_destino.id = registro_hotelero.id_municipio_destino

LEFT JOIN vehiculos 
    ON vehiculos.id = registro_hotelero.id_vehiculo

WHERE r.id = $id_reserva;

        ");
        return $datos->getResultArray();
    } 

    public function getReservas($id_reserva)
    {
        $datos = $this->db->query("
     
                   SELECT 
                m.nombre AS nombre_habitacion,
                r.fecha_reserva,
                r.notas,
                h.precio AS valor_hospedaje
                
            FROM reservas r
            INNER JOIN habitaciones h 
                ON r.id_habitacion = h.id
            INNER JOIN mesas m 
                ON h.id_mesa = m.id
            INNER JOIN estado_reservas er 
                ON er.id = r.id_estado_reservas
     
            WHERE r.id = $id_reserva;

        ");
        return $datos->getResultArray();
    } 

    public function getResrvasHabitaicones($fecha_inicial,$fecha_final)
    {
        $datos = $this->db->query("
          SELECT 
    r.id AS id_reserva,
    h.id AS id_habitacion,
    m.nombre,
    r.id_estado_reservas,
    r.fecha_reserva,
    r.notas,
    r.vehiculo,
    er.descripcion AS estado_reserva

FROM reservas r

INNER JOIN habitaciones h 
    ON r.id_habitacion = h.id

INNER JOIN mesas m 
    ON h.id_mesa = m.id

INNER JOIN estado_reservas er 
    ON er.id = r.id_estado_reservas

WHERE r.id_estado_reservas IN (1, 6)

AND r.fecha_reserva BETWEEN '$fecha_inicial' AND '$fecha_final'

ORDER BY 
    h.id,
    r.id_estado_reservas ASC;;
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
            AND r.id_estado_reservas IN (1, 6)
            ORDER BY h.id, r.id_estado_reservas ASC;
                    ");
        return $datos->getResultArray();
    }
    public function buscarHabitaiconesFecha($fechaInicial, $fechaFinal)
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

INNER JOIN habitaciones h 
    ON r.id_habitacion = h.id

INNER JOIN mesas m 
    ON h.id_mesa = m.id

INNER JOIN estado_reservas er 
    ON er.id = r.id_estado_reservas

WHERE r.fecha_reserva BETWEEN '$fechaInicial' AND '$fechaFinal'
AND r.id_estado_reservas IN (1, 6)

ORDER BY 
    h.id,
    r.id_estado_reservas ASC;
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
