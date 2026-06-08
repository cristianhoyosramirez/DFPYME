<?php

namespace App\Models;

use CodeIgniter\Model;

class reporteProductoModel extends Model
{
    protected $table      = 'reporte_ventas_producto_categorias';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['cantidad', 'nombre_producto', 'precio_venta', 'valor_total', 'id_categoria', 'codigo_interno_producto', 'valor_unitario'];

    public function categorias()
    {
        $datos = $this->db->query("
            select distinct(id_categoria)  from reporte_ventas_producto_categorias 
         ");
        return $datos->getResultArray();
    }

    public function getProductos($inicial, $final, $fecha)
    {
        $datos = $this->db->query("
           SELECT
    codigo,
    producto.nombreproducto,
    SUM(cantidad) AS total_cantidad,
    SUM(total) AS total_valor
FROM
    kardex
INNER JOIN producto ON producto.codigointernoproducto = kardex.codigo
WHERE
    hora BETWEEN '$inicial' AND '$final' AND fecha = '$fecha'
GROUP BY
    codigo,
    producto.nombreproducto;
         ");
        return $datos->getResultArray();
    }
    public function getTotal($inicial, $final, $fecha)
    {
        $datos = $this->db->query("
   SELECT
    SUM(kardex.total) AS total_valor
FROM
    kardex
INNER JOIN producto ON producto.codigointernoproducto = kardex.codigo
WHERE
    kardex.hora BETWEEN '$inicial' AND '$final'
    AND kardex.fecha = '$fecha';

         ");
        return $datos->getResultArray();
    }
    public function getCategorias($inicial, $final)
    {
        $datos = $this->db->query("
            SELECT DISTINCT
                (id_categoria),
                categoria.nombrecategoria 
            FROM
                kardex
            INNER JOIN categoria ON categoria.codigocategoria = kardex.id_categoria
            WHERE
                fecha_y_hora_factura_venta BETWEEN '$inicial' AND '$final'
         ");
        return $datos->getResultArray();
    }
    public function getExcelCategorias($inicial, $final)
    {
        $datos = $this->db->query("
            SELECT DISTINCT
                (id_categoria),
                categoria.nombrecategoria 
            FROM
                kardex
            INNER JOIN categoria ON categoria.codigocategoria = kardex.id_categoria
            WHERE
                fecha_y_hora_factura_venta BETWEEN '$inicial' AND '$final'
         ");
        return $datos->getResultArray();
    }
    public function getProductosCategorias($inicial, $final, $codigoCategoria)
    {
        $datos = $this->db->query("
            SELECT
                fecha,
                hora,
                cantidad,
                valor,
                total,
                nombreproducto,
                codigo,
                valor_unitario
            FROM
                kardex
            INNER JOIN producto ON producto.codigointernoproducto = kardex.codigo
            WHERE
                fecha_y_hora_factura_venta BETWEEN '$inicial' AND '$final' AND id_categoria = '$codigoCategoria'
         ");
        return $datos->getResultArray();
    }
}
