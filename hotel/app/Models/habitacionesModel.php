<?php

namespace App\Models;

use CodeIgniter\Model;

class habitacionesModel extends Model
{
    protected $table      = 'habitaciones';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    //protected $allowedFields = ['fecha_lanzamiento', 'notas','nombre'];
    protected $allowedFields = [
        'tipo_documento',
        'id_mesa',
        'tipo',
        'precio',
        'piso',
        'capacidad'
    ];


    public function getHabitaciones()
    {
        $datos = $this->db->query("
     
SELECT 
    h.tipo,
    h.precio,
    h.capacidad,
    m.nombre AS nombre_mesa,
    m.id AS id_mesa,
    h.id AS id_habitacion,
    eh.nombre AS estado,
m.id_estado AS id_estado
FROM habitaciones AS h
INNER JOIN mesas AS m
    ON m.id = h.id_mesa
INNER JOIN estado_habitaciones AS eh
    ON eh.id = m.id_estado
ORDER BY 
    m.nombre ASC;
        ");
        return $datos->getResultArray();
    }
    public function getHabitacionesInsert()
    {
        $datos = $this->db->query("
     
SELECT 
    h.tipo,
    h.precio,
    h.capacidad,
    m.nombre AS nombre_mesa,
    m.id_estado,
    m.id AS id_mesa,
    h.id AS id_habitacion,
    estado_habitaciones.nombre AS estado
FROM habitaciones AS h
INNER JOIN mesas AS m
    ON m.id = h.id_mesa
INNER JOIN estado_habitaciones
    ON estado_habitaciones.id = m.id_estado
ORDER BY 
    h.id DESC,       -- último registro insertado primero
    m.nombre ASC;    -- y luego por nombre de mesa
        ");
        return $datos->getResultArray();
    }

    public function clientes($valor)
    {
        $datos = $this->db->query("
         SELECT
        nitcliente,nombrescliente,id
    FROM
        cliente
    WHERE
        nombrescliente ILIKE '%$valor%' or nitcliente  ILIKE '%$valor%' order by id desc;
         ");
        return $datos->getResultArray();
    }

    public function estadoHabitaciones()
    {
        $datos = $this->db->query("
            select * from estado_habitaciones order by id asc 
         ");
        return $datos->getResultArray();
    }
    public function getMunicipios($valor)
    {
        $datos = $this->db->query("
            SELECT id, nombre
            FROM municipio
            WHERE nombre ILIKE '%$valor%';
         ");
        return $datos->getResultArray();
    }
    public function getPlacas($valor)
    {
        $datos = $this->db->query("
            SELECT *
            FROM vehiculos
            WHERE placa ILIKE '%$valor%';
         ");
        return $datos->getResultArray();
    }

    public function registroHotelero()
    {
        $datos = $this->db->query("
SELECT 
    m1.nombre AS origen,
    m2.nombre AS destino,
    r.fecha,
    r.hora_salida,            -- <-- agregado
    me.nombre AS habitacion,
    c.nombrescliente,
    c.nitcliente,
    d.descripcion AS codigo_documento,
    v.tipo AS tipo_vehiculo,
    v.placa AS placa_vehiculo,
    re.notas AS notas_reserva
FROM registro_hotelero r
INNER JOIN municipio m1 
    ON m1.id = r.id_municipio_origen
INNER JOIN municipio m2 
    ON m2.id = r.id_municipio_destino
INNER JOIN reservas re 
    ON re.id = r.id_reserva
INNER JOIN habitaciones h 
    ON h.id = re.id_habitacion
INNER JOIN mesas me 
    ON me.id = h.id_mesa
INNER JOIN cliente c 
    ON c.id = re.id_cliente
INNER JOIN documento_identidad d
    ON d.codigo = c.type_document
INNER JOIN vehiculos v
    ON v.id = r.id_vehiculo;
         ");
        return $datos->getResultArray();
    }
}
