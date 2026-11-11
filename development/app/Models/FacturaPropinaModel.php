<?php

namespace App\Models;

use CodeIgniter\Model;

class FacturaPropinaModel extends Model
{
    protected $table      = 'factura_propina';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['estado', 'valor_propina', 'id_factura', 'id_apertura', 'fecha_y_hora_factura_venta', 'fecha', 'hora', 'id_mesero', 'id_mesa'];


    function get_meseros($id_apertura)
    {
        $datos = $this->db->query("
        SELECT DISTINCT
            (id_mesero)
        FROM
            pagos
        WHERE
            id_apertura = $id_apertura
         ");
        return $datos->getResultArray();
    }

    function get_propinas($id_apertura, $id_mesero)
    {
        $datos = $this->db->query("
        SELECT
            *
        FROM
            factura_propina
        WHERE
            id_apertura = $id_apertura AND id_mesero = $id_mesero and valor_propina > 0 ");
        return $datos->getResultArray();
    }

    function get_total_propinas($id_apertura, $id_mesero)
    {
        $datos = $this->db->query("
        SELECT
            SUM(propina) AS total_propina
        FROM
        pagos
        WHERE
        id_apertura = $id_apertura AND id_mesero = $id_mesero ");
        return $datos->getResultArray();
    }

    function getPropinas($id_apertura, $id_mesero)
    {
        $datos = $this->db->query("
                SELECT 
                COALESCE(usuario_sistema.nombresusuario_sistema, 'SIN MESERO') AS mesero,
                COALESCE(mesas.nombre, 'SIN MESA') AS mesa,
                pagos.documento,
                pagos.total_documento,
                pagos.propina
                FROM pagos
                LEFT JOIN mesas 
                ON mesas.id = pagos.id_mesa
                LEFT JOIN usuario_sistema 
                    ON usuario_sistema.idusuario_sistema = pagos.id_mesero
                WHERE pagos.id_apertura = $id_apertura
                AND pagos.id_mesero = $id_mesero
                AND propina > 0
                 ");
        return $datos->getResultArray();
    }
}
