<?php

namespace App\Models;

use CodeIgniter\Model;

class ReporteImpuestosModel extends Model
{
    protected $table      = 'reporte_impuestos';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [

        'base_inc_0',
        'inc_0',
        'base_iva_0',
        'iva_0',
        'base_iva_5',
        'iva_5',
        'base_iva_19',
        'iva_19',
        'fecha',
        'total_inc',
        'total_iva',
        'total_venta',
        'dia_proceso',
        'base_inc_8',
        'inc_8',

    ];

    public function actualizar_tabla($data, $fecha, $dia)
    {
        // Accede directamente a la tabla usando el método update()
        return $this->db->table('reporte_impuestos')
            ->where('fecha', $fecha)
            ->where('dia_proceso', $dia)
            ->update($data); // Actualiza los datos
    }

    public function getValores()
    {
        $datos = $this->db->query("
        SELECT *  
        FROM reporte_impuestos  
        ORDER BY dia_proceso ASC ;
        ");
        return $datos->getResultArray();
    }
    public function getTotal($fechaInicial, $fechaFinal)
    {
        $datos = $this->db->query("
            SELECT
            SUM(total) AS total,
            SUM(ico) AS inc,
            SUM(iva) AS iva
            FROM
                kardex
            WHERE
            fecha BETWEEN '$fechaInicial' AND '$fechaFinal'
        ");
        return $datos->getResultArray();
    }

    public function getProductos($fechaInicial, $fechaFinal)
    {
        $datos = $this->db->query("
        SELECT
    kardex.numerodocumento AS numero,
    kardex.fecha,
    kardex.codigo,
    producto.nombreproducto,
    kardex.cantidad,
    kardex.cantidad,
    kardex.valor_unitario as precio_unitario,
    kardex.total as total,

    -- Campos originales necesarios para PHP
    kardex.iva,
    kardex.ico,
    kardex.valor_iva,
    kardex.valor_ico,
    kardex.aplica_ico,

    -- Costo base sin IVA
    kardex.valor AS costo_base_sin_iva_unidad,

    kardex.valor * kardex.cantidad
        AS costo_base_sin_iva_total,

    -- Costo más impuestos
    kardex.valor
        + CASE
            WHEN kardex.cantidad > 0
            THEN COALESCE(kardex.valor_iva, 0) / kardex.cantidad
            ELSE 0
          END
        + CASE
            WHEN kardex.cantidad > 0
            THEN COALESCE(kardex.valor_ico, 0) / kardex.cantidad
            ELSE 0
          END
        AS costo_mas_iva_unidad,

    kardex.valor * kardex.cantidad
        + COALESCE(kardex.valor_iva, 0)
        + COALESCE(kardex.valor_ico, 0)
        AS costo_mas_iva_total,

    -- Valor de venta base
    kardex.valor_unitario AS valor_venta_base_unidad,

    kardex.valor_unitario * kardex.cantidad
        AS valor_venta_base_total,

    -- ICO 8%
    CASE
        WHEN kardex.aplica_ico = TRUE
             AND kardex.ico = 8
        THEN COALESCE(kardex.valor_ico, 0)
        ELSE 0
    END AS ico_8_total,

    -- IVA 5%
    CASE
        WHEN kardex.iva = 5
        THEN COALESCE(kardex.valor_iva, 0)
        ELSE 0
    END AS iva_5_total,

    -- IVA 19%
    CASE
        WHEN kardex.iva = 19
        THEN COALESCE(kardex.valor_iva, 0)
        ELSE 0
    END AS iva_19_total,

    -- Total venta
    kardex.total AS total_venta,

    kardex.id_factura,
    kardex.id_estado,
    kardex.costo,
    estado.descripcionestado

FROM kardex

INNER JOIN estado
    ON kardex.id_estado = estado.idestado

INNER JOIN producto
    ON producto.codigointernoproducto = kardex.codigo

WHERE kardex.fecha BETWEEN '2026-11-01' AND '2026-11-23'

ORDER BY kardex.fecha ASC;
        ");
        return $datos->getResultArray();
    }
    public function ventas($fechaInicial, $fechaFinal)
    {
        $datos = $this->db->query("
            SELECT
                fecha::date AS fecha,
                SUM(total) AS total_ventas,
                SUM(iva) AS total_iva,
                SUM(ico) AS total_ico,
                SUM(total - iva - ico) AS base
            FROM kardex
            WHERE fecha >= '$fechaInicial'
            AND fecha < '$fechaFinal'
            GROUP BY fecha::date
            ORDER BY fecha::date;
        ");
        return $datos->getResultArray();
    }
}
