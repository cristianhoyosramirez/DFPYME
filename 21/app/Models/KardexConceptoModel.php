<?php

namespace App\Models;

use CodeIgniter\Model;

class KardexConceptoModel extends Model
{
    protected $table      = 'concepto_kardex';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['idoperacion', 'nombre', 'estado'];


    public function getHoras($inicio, $fin)
    {
        $datos = $this->db->query("
       
            SELECT distinct(hora)
            FROM kardex
            WHERE fecha_y_hora_factura_venta BETWEEN '$inicio' AND '$fin';
         ");
        return $datos->getResultArray();
    }

    public function sqlReporteVentas($where)
    {
        $sql = ("
       
            SELECT
                pagos.fecha,
                pagos.hora,
                pagos.nit_cliente,
                cliente.nombrescliente,
                pagos.documento,
                pagos.total_documento,
                pagos.id_factura

            FROM pagos

            INNER JOIN 
                cliente
            ON 
                cliente.nitcliente = pagos.nit_cliente
            WHERE
                pagos.id_estado = 6
            AND {$where}
            ORDER BY
                pagos.fecha DESC,
                pagos.hora DESC

         ");
        return $sql;
    }

    public function totalRegistros($where)
    {
        $datos = $this->db->query("
       
        SELECT count(id) as total_cortesias FROM pagos 
        WHERE {$where}
        and 
            id_estado = 6 

         ");
        return $datos->getResultArray();
    }

    public function totalRegistrosCortesia($where)
    {
        $datos = $this->db->query("
       
       SELECT 
        COUNT(id) AS total_cortesias
        FROM pagos
        WHERE {$where}
        AND id_estado = 6;

         ");
        return $datos->getResultArray();
    }
    public function fechasApertura($id_apertura)
    {
        $datos = $this->db->query("
       
            SELECT 
            apertura.fecha AS fecha_apertura,
            apertura.hora AS hora_apertura,
            CASE 
                WHEN cierre.idapertura IS NULL THEN 'Caja sin cierre registrado'
                ELSE cierre.fecha::text
            END AS fecha_cierre,
            CASE 
                WHEN cierre.idapertura IS NULL THEN 'Pendiente'
                ELSE cierre.hora::text
            END AS hora_cierre
        FROM apertura
        LEFT JOIN cierre 
            ON apertura.id = cierre.idapertura
        WHERE apertura.id = $id_apertura;

         ");
        return $datos->getResultArray();
    }

    public function impuestos($id_factura)
    {
        $datos = $this->db->query("
       
           select sum(ico + iva) as impuestos from kardex where id_factura= $id_factura and id_estado = 6

         ");
        return $datos->getResultArray();
    }
}
