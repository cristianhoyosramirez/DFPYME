<?php

namespace App\Models;

use CodeIgniter\Model;

class CarteraModel extends Model
{
    //protected $table      = 'bonos';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    //protected $allowedFields = ['id_usuario','fecha_generacion','hora','obserevacion','valor'];

    public function getCartera($estado, $fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
            
    SELECT
    p.fecha,
    p.nit_cliente,
    c.nombrescliente,
    p.documento,
    p.total_documento,
    e.descripcionestado,
    p.saldo,
    (p.total_documento - p.saldo) AS abonado
FROM pagos p
INNER JOIN cliente c
    ON c.nitcliente = p.nit_cliente
INNER JOIN estado e
    ON e.idestado = p.id_estado
WHERE
    (
        $estado = 0
        OR ($estado = 1 AND p.saldo > 0)
        OR ($estado = 2 AND p.saldo = 0)
    )
AND p.fecha BETWEEN
    COALESCE(NULLIF('$fecha_inicial', '')::date,
        (SELECT MIN(fecha) FROM pagos))
AND
    COALESCE(NULLIF('$fecha_final', '')::date,
        (SELECT MAX(fecha) FROM pagos))
AND p.forma_pago = 2
ORDER BY p.fecha DESC;
            
            ");
        return $datos->getResultArray();
    }

    public function getDatosCartera($documento)
    {
        $datos = $this->db->query("
            
                SELECT
                    fecha,
                    nit_cliente,
                    cliente.nombrescliente,
                    documento,
                    total_documento,
                    estado.descripcionestado,
                    saldo,
                    (total_documento - saldo) AS abonado,
                    id_factura,
                    id_estado 
                FROM pagos
                INNER JOIN cliente
                    ON cliente.nitcliente = pagos.nit_cliente
                INNER JOIN estado
                    ON estado.idestado = pagos.id_estado
                WHERE pagos.documento ILIKE '%$documento%'
                and saldo > 0
            
            ");
        return $datos->getResultArray();
    }

    public function getDatosCarteraCliente($cliente)
    {
        $datos = $this->db->query("
            
               SELECT
            pagos.fecha,
            pagos.nit_cliente,
            cliente.nombrescliente,
            pagos.documento,
            pagos.total_documento,
            estado.descripcionestado,
            pagos.saldo,
            (pagos.total_documento - pagos.saldo) AS abonado,
            id_factura,
            id_estado
        FROM pagos
        INNER JOIN cliente
            ON cliente.nitcliente = pagos.nit_cliente
        INNER JOIN estado
            ON estado.idestado = pagos.id_estado
       WHERE (
    cliente.nombrescliente ILIKE '%$cliente%'
    OR pagos.nit_cliente ILIKE '%$cliente%'
)
AND pagos.saldo > 0
ORDER BY cliente.nombrescliente ASC;
            
            ");
        return $datos->getResultArray();
    }
    public function getSumaCartera($documento)
    {
        $datos = $this->db->query("
            
                  SELECT
                   sum(total_documento) as total 
                FROM pagos 
                WHERE pagos.documento ILIKE '%$documento%' and saldo > 0
            
            ");
        return $datos->getResultArray();
    }
    public function getSumaCarteraCliente($documento)
    {
        $datos = $this->db->query("
            
        SELECT
            SUM(p.total_documento) AS total
        FROM pagos p
        INNER JOIN cliente c
            ON c.nitcliente = p.nit_cliente
        WHERE (
                p.documento::TEXT ILIKE '%$documento%'
                OR c.nombrescliente ILIKE '%$documento%'
                OR c.nitcliente::TEXT ILIKE '%$documento%'
            )
        AND p.saldo > 0;
            
            ");
        return $datos->getResultArray();
    }

    public function getSumaCarteraFechas($estado, $fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT
            COALESCE(SUM(p.total_documento), 0) AS total
        FROM pagos p
        INNER JOIN cliente c
            ON c.nitcliente = p.nit_cliente
        WHERE
            (
                $estado = 0
                OR ($estado = 1 AND p.saldo > 0)
                OR ($estado = 2 AND p.saldo = 0)
            )
        AND p.fecha BETWEEN
            COALESCE(NULLIF('$fecha_inicial', '')::date,
                (SELECT MIN(fecha) FROM pagos))
        AND
            COALESCE(NULLIF('$fecha_final', '')::date,
                (SELECT MAX(fecha) FROM pagos))
        AND p.forma_pago = 2
    ");

        return $datos->getResultArray();
    }

    public function getCantidadCartera($documento)
    {
        $datos = $this->db->query("
        SELECT
            COUNT(*) AS cantidad
        FROM pagos
        WHERE documento ILIKE '%$documento%'  and saldo > 0
    ");

        return $datos->getRowArray();
    }
    public function getCantidadCarteraCliente($cliente)
    {
        $datos = $this->db->query("
       SELECT
            COUNT(*) AS cantidad
        FROM pagos p
        INNER JOIN cliente c
            ON c.nitcliente = p.nit_cliente
        WHERE (
                p.documento::TEXT ILIKE '%$cliente%'
                OR c.nombrescliente ILIKE '%$cliente%'
                OR c.nitcliente::TEXT ILIKE '%$cliente%'
            )
        AND p.saldo > 0;
    ");

        return $datos->getRowArray();
    }

    public function getCantidadCarteraFechas($estado, $fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT
            COUNT(*) AS cantidad
        FROM pagos p
        INNER JOIN cliente c
            ON c.nitcliente = p.nit_cliente
        WHERE
            (
                $estado = 0
                OR ($estado = 1 AND p.saldo > 0)
                OR ($estado = 2 AND p.saldo = 0)
            )
        AND p.fecha BETWEEN
            COALESCE(NULLIF('$fecha_inicial', '')::date,
                (SELECT MIN(fecha) FROM pagos))
        AND
            COALESCE(NULLIF('$fecha_final', '')::date,
                (SELECT MAX(fecha) FROM pagos))
        AND p.forma_pago = 2
    ");

        return $datos->getRowArray();
    }

    public function totalPagado($documento)
    {
        $datos = $this->db->query("
         SELECT
                   total_documento-saldo as total 
                FROM pagos
                
                WHERE pagos.documento ILIKE '%$documento%' and saldo > 0
    ");

        return $datos->getRowArray();
    }
    public function totalPagadoCliente($cliente)
    {
        $datos = $this->db->query("
        SELECT
            COALESCE(SUM(p.total_documento - p.saldo), 0) AS total
        FROM pagos p
        INNER JOIN cliente c
            ON c.nitcliente = p.nit_cliente
        WHERE (
                c.nombrescliente ILIKE '%$cliente%'
                OR c.nitcliente::TEXT ILIKE '%$cliente%'
            )
        AND p.saldo > 0;
            ");

        return $datos->getRowArray();
    }

    public function totalPagadoFechas($estado, $fecha_inicial, $fecha_final)
{
    $datos = $this->db->query("
        SELECT
            COALESCE(SUM(p.total_documento - p.saldo), 0) AS total
        FROM pagos p
        INNER JOIN cliente c
            ON c.nitcliente = p.nit_cliente
        WHERE
            (
                $estado = 0
                OR ($estado = 1 AND p.saldo > 0)
                OR ($estado = 2 AND p.saldo = 0)
            )
        AND p.fecha BETWEEN
            COALESCE(NULLIF('$fecha_inicial', '')::date,
                (SELECT MIN(fecha) FROM pagos))
        AND
            COALESCE(NULLIF('$fecha_final', '')::date,
                (SELECT MAX(fecha) FROM pagos))
        AND p.forma_pago = 2
    ");

    return $datos->getRowArray();
}


    public function getCarteraFechas($estado, $fecha_inicial, $fecha_final)
    {
        $datos = $this->db->query("
        SELECT
            COALESCE(SUM(p.total_documento), 0) AS total
        FROM pagos p
        INNER JOIN cliente c
            ON c.nitcliente = p.nit_cliente
        WHERE
            (
                $estado = 0
                OR ($estado = 1 AND p.saldo > 0)
                OR ($estado = 2 AND p.saldo = 0)
            )
        AND p.fecha BETWEEN
            COALESCE(NULLIF('$fecha_inicial', '')::date,
                (SELECT MIN(fecha) FROM pagos))
        AND
            COALESCE(NULLIF('$fecha_final', '')::date,
                (SELECT MAX(fecha) FROM pagos))
        AND p.forma_pago = 2
    ");

        return $datos->getResultArray();
    }
}
