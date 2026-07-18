<?php

namespace App\Models;

use CodeIgniter\Model;

class notaCreditoModel extends Model
{
    protected $table = 'nota_credito_electronica';

    protected $allowedFields = [
        'tipo_ambiente',
        'id_status',
        'caja',
        'usuario_id',
        'station',
        'id_factura',
        'nit_cliente',
        'prefijo',
        'numero',
        'fecha',
        'fecha_validacion',
        'impuesto',
        'retencion',
        'total',
        'razon',
        'nota',
        'propina',
        'saldada',
        'uuid',
        'qrcode',
        'cufe',
        'pdf_url',
        'dian_message'
    ];

    public function datosNc()
    {

        $datos = $this->db->query("
SELECT
    nce.id,
    de.numero AS numero_factura,
    nce.fecha,
    CONCAT(nce.prefijo, '-', nce.numero) AS numero_nota_credito,
    nce.nit_cliente,
    c.nombrescliente,
    nce.id_status,
    nce.razon,
    nce.nota,
    us.nombresusuario_sistema AS usuario,
    nce.pdf_url
FROM nota_credito_electronica AS nce
INNER JOIN documento_electronico AS de
    ON de.id = nce.id_factura
INNER JOIN cliente AS c
    ON c.nitcliente = nce.nit_cliente
INNER JOIN usuario_sistema AS us
    ON us.idusuario_sistema = nce.usuario_id
ORDER BY nce.fecha DESC
LIMIT 100;
        
        ");
        return $datos->getResultArray();
    }
    public function getNc($nc)
    {

        $datos = $this->db->query("
            SELECT
                nce.id,
                de.numero AS numero_factura,
                nce.fecha,
                nce.prefijo || '-' || nce.numero AS numero_nota_credito,
                nce.nit_cliente,
                c.nombrescliente,
                nce.id_status,
                nce.razon,
                nce.nota,
                us.nombresusuario_sistema AS usuario,
                nce.pdf_url
            FROM nota_credito_electronica AS nce
            INNER JOIN documento_electronico AS de
                ON de.id = nce.id_factura
            INNER JOIN cliente AS c
                ON c.nitcliente = nce.nit_cliente
            INNER JOIN usuario_sistema AS us
                ON us.idusuario_sistema = nce.usuario_id
            WHERE UPPER(nce.prefijo || nce.numero::TEXT) LIKE UPPER('%$nc%')
            ORDER BY nce.fecha DESC;
        
        ");
        return $datos->getResultArray();
    }

    public function getNcFechas($fecha_inicial, $fecha_final)
    {

        $datos = $this->db->query("
                    SELECT
            nce.id,
            de.numero AS numero_factura,
            nce.fecha,
            CONCAT(nce.prefijo, '-', nce.numero) AS numero_nota_credito,
            nce.nit_cliente,
            c.nombrescliente,
            nce.id_status,
            nce.razon,
            nce.nota,
            us.nombresusuario_sistema AS usuario,
            nce.pdf_url
        FROM nota_credito_electronica AS nce
        INNER JOIN documento_electronico AS de
            ON de.id = nce.id_factura
        INNER JOIN cliente AS c
            ON c.nitcliente = nce.nit_cliente
        INNER JOIN usuario_sistema AS us
            ON us.idusuario_sistema = nce.usuario_id
        WHERE nce.fecha >= '$fecha_inicial'::timestamp
        AND nce.fecha < ('$fecha_final'::date + INTERVAL '1 day')
        ORDER BY nce.fecha DESC;
        
        ");
        return $datos->getResultArray();
    }

    public function getEstadoNc($nc)
    {

        $datos = $this->db->query("
                    SELECT
            COUNT(*) AS total,
            COALESCE(SUM(CASE WHEN nce.id_status = 2 THEN 1 ELSE 0 END), 0) AS aceptadas,
            COALESCE(SUM(CASE WHEN nce.id_status = 3 THEN 1 ELSE 0 END), 0) AS rechazadas,
            COALESCE(SUM(CASE WHEN nce.id_status = 1 THEN 1 ELSE 0 END), 0) AS pendientes
        FROM nota_credito_electronica nce
        WHERE UPPER(nce.prefijo || nce.numero::TEXT) LIKE UPPER('%$nc%');
        ");
        return $datos->getResultArray();
    }


    public function getNcCliente($nc)
    {

        $datos = $this->db->query("
          SELECT
    nce.id,
    de.numero AS numero_factura,
    nce.fecha,
    nce.prefijo || '-' || nce.numero AS numero_nota_credito,
    nce.nit_cliente,
    c.nombrescliente,
    nce.id_status,
    nce.razon,
    nce.nota,
    us.nombresusuario_sistema AS usuario,
    nce.pdf_url
FROM nota_credito_electronica AS nce
INNER JOIN documento_electronico AS de
    ON de.id = nce.id_factura
INNER JOIN cliente AS c
    ON c.nitcliente = nce.nit_cliente
INNER JOIN usuario_sistema AS us
    ON us.idusuario_sistema = nce.usuario_id
WHERE
    c.nombrescliente ILIKE '%$nc%'
    OR c.nitcliente::TEXT ILIKE '%$nc%'
ORDER BY nce.fecha DESC;
        
        ");
        return $datos->getResultArray();
    }

    public function getEstadoNcCliente($buscar)
    {
        $sql = "
        SELECT
            COUNT(*) AS total,
            COALESCE(SUM(CASE WHEN nce.id_status = 2 THEN 1 ELSE 0 END), 0) AS aceptadas,
            COALESCE(SUM(CASE WHEN nce.id_status = 3 THEN 1 ELSE 0 END), 0) AS rechazadas,
            COALESCE(SUM(CASE WHEN nce.id_status = 1 THEN 1 ELSE 0 END), 0) AS pendientes
        FROM nota_credito_electronica nce
        INNER JOIN cliente c
            ON c.nitcliente = nce.nit_cliente
        WHERE
            UPPER(c.nombrescliente) LIKE UPPER(?)
            OR UPPER(c.nitcliente) LIKE UPPER(?)
    ";

        $datos = $this->db->query($sql, [
            "%{$buscar}%",
            "%{$buscar}%"
        ]);

        return $datos->getRowArray();
    }


    public function getEstadoNcFecha($fechaInicial, $fechaFinal)
    {
        $sql = "
        SELECT
            COUNT(*) AS total,
            COALESCE(SUM(CASE WHEN nce.id_status = 2 THEN 1 ELSE 0 END), 0) AS aceptadas,
            COALESCE(SUM(CASE WHEN nce.id_status = 3 THEN 1 ELSE 0 END), 0) AS rechazadas,
            COALESCE(SUM(CASE WHEN nce.id_status = 1 THEN 1 ELSE 0 END), 0) AS pendientes
        FROM nota_credito_electronica nce
        WHERE nce.fecha BETWEEN ? AND ?
    ";

        return $this->db->query($sql, [
            $fechaInicial,
            $fechaFinal
        ])->getRowArray();
    }

      public function nCEstado($id_status)
    {

        $datos = $this->db->query("
SELECT
    nce.id,
    de.numero AS numero_factura,
    nce.fecha,
    CONCAT(nce.prefijo, '-', nce.numero) AS numero_nota_credito,
    nce.nit_cliente,
    c.nombrescliente,
    nce.id_status,
    nce.razon,
    nce.nota,
    us.nombresusuario_sistema AS usuario,
    nce.pdf_url
FROM nota_credito_electronica AS nce
INNER JOIN documento_electronico AS de
    ON de.id = nce.id_factura
INNER JOIN cliente AS c
    ON c.nitcliente = nce.nit_cliente
INNER JOIN usuario_sistema AS us
    ON us.idusuario_sistema = nce.usuario_id
WHERE nce.id_status = $id_status
ORDER BY nce.fecha DESC;
        
        ");
        return $datos->getResultArray();
    }
}
