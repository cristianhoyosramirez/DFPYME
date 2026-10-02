<?php

namespace App\Models;

use CodeIgniter\Model;

class formaPagoModel extends Model
{
    protected $table      = 'forma_pago';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['nombreforma_pago','aplica','ruta'];

    public function getFacturas($fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
SELECT
    pagos.efectivo,
    pagos.transferencia,
    pagos.id,
    pagos.id_factura,
    pagos.id_estado,
    pagos.id_clase_pago,
    pagos.fecha,
    pagos.hora,
    pagos.total_documento,
    pagos.id_pedido,
    pagos.nit_cliente,
    cliente.nombrescliente,
    estado.descripcionestado,

    CASE 
        WHEN pagos.forma_pago = 1 THEN 'CONTADO'
        WHEN pagos.forma_pago = 2 THEN 'CRÉDITO'
        ELSE 'OTRO'
    END AS forma_pago_texto,

    CASE 
        WHEN pagos.id_estado = 8 THEN (
            SELECT numero
            FROM documento_electronico
            WHERE documento_electronico.id = pagos.id_factura
            LIMIT 1
        )

        ELSE (
            SELECT numerofactura_venta 
            FROM factura_venta 
            WHERE factura_venta.id = pagos.id_factura 
            LIMIT 1
        )

    END AS numero

FROM pagos

INNER JOIN estado 
    ON estado.idestado = pagos.id_estado

INNER JOIN cliente
    ON cliente.nitcliente = pagos.nit_cliente

WHERE pagos.fecha BETWEEN '$fecha_inicial' AND '$fecha_final'

ORDER BY pagos.fecha ASC;


         ");
        return $datos->getResultArray();
    }

        public function totalVentas($fecha_inicial , $fecha_final)
    {
        $datos = $this->db->query("
            SELECT
                SUM(total_documento) AS total
            FROM
                pagos
            WHERE
                fecha BETWEEN '$fecha_inicial' AND '$fecha_final'
         ");
        return $datos->getResultArray();
    }
}