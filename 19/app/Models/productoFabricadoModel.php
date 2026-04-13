<?php

namespace App\Models;

use CodeIgniter\Model;

class productoFabricadoModel extends Model
{
    protected $table      = 'producto_fabricado';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['prod_fabricado','prod_proceso', 'cantidad'];

    function GetInsumos($receta)
    {

        $datos = $this->db->query("
               SELECT
    producto_fabricado.id,
    producto.nombreproducto,
    producto.codigointernoproducto,
    producto_fabricado.cantidad,
    producto.precio_costo,
    (precio_costo * producto_fabricado.cantidad) AS costo_total
FROM
    producto_fabricado
INNER JOIN producto ON
    producto.codigointernoproducto = producto_fabricado.prod_proceso
WHERE
    producto_fabricado.prod_fabricado = '$receta' order by id desc;

        ");
        return $datos->getResultArray();
    }
    function GetMedida($codigo)
    {

        $datos = $this->db->query("
               SELECT
                descripcionvalor_unidad_medida as medida,
                idunidad_medida
                FROM
                    producto_medida
                INNER JOIN valor_unidad_medida ON valor_unidad_medida.idvalor_unidad_medida = producto_medida.idvalor_unidad_medida
                WHERE
                    codigointernoproducto = '$codigo'

        ");
        return $datos->getResultArray();
    }

    function GetCosto($codigo)
    {

        $datos = $this->db->query("
             SELECT
                SUM(precio_costo *producto_fabricado.cantidad ) as costo
            FROM
                producto_fabricado
            INNER JOIN producto ON producto.codigointernoproducto = producto_fabricado.prod_proceso
            WHERE
                prod_fabricado = '$codigo'
        ");
        return $datos->getResultArray();
    }
    function existeInsumo($codigoReceta,$codigoInsumo)
    {

        $datos = $this->db->query("
        SELECT
            id
        FROM
            producto_fabricado
        WHERE
            prod_fabricado = '$codigoReceta' AND prod_proceso = '$codigoInsumo'
        ");
        return $datos->getResultArray();
    }
    function actualizarCosto($codigo,$costo)
    {

        $datos = $this->db->query("
       UPDATE producto
SET precio_costo = $costo
WHERE codigointernoproducto = '$codigo';
        ");
        //return $datos->getResultArray();
    }

    function actualizarCostoReceta($codigo)
    {

        $datos = $this->db->query("
UPDATE producto p
SET precio_costo = sub.costo
FROM (
    SELECT 
        pf.prod_fabricado,
        SUM(p2.precio_costo * pf.cantidad) AS costo
    FROM 
        producto_fabricado pf
    INNER JOIN 
        producto p2 ON p2.codigointernoproducto = pf.prod_proceso
    WHERE 
        pf.prod_proceso = '$codigo'
    GROUP BY 
        pf.prod_fabricado
) AS sub
WHERE 
    p.codigointernoproducto = sub.prod_fabricado;
        ");
        //return $datos->getResultArray();
    }
   
}