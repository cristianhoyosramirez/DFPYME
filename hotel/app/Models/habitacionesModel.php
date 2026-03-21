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
        h.piso,
        h.capacidad,
        m.nombre AS nombre_mesa,
        m.estado_mesa,
        m.id as id_mesa,
        h.id as id_habitacion
    FROM habitaciones AS h
    INNER JOIN mesas AS m
    ON m.id = h.id_mesa
    ORDER BY 
        h.piso ASC,
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
    h.piso,
    h.capacidad,
    m.nombre AS nombre_mesa,
    m.estado_mesa,
    m.id AS id_mesa,
    h.id AS id_habitacion
FROM habitaciones AS h
INNER JOIN mesas AS m
    ON m.id = h.id_mesa
ORDER BY 
    h.id DESC,       -- 🔥 último registro insertado primero
    h.piso ASC,      -- después ordenar por piso
    m.nombre ASC;    -- y luego por nombre de mesa
        ");
        return $datos->getResultArray();
    }
}
