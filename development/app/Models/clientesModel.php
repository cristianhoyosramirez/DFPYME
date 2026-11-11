<?php

namespace App\Models;

use CodeIgniter\Model;

class clientesModel extends Model
{
    protected $table      = 'cliente';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'nitcliente',
        'idregimen',
        'nombrescliente',
        'telefonocliente',
        'celularcliente',
        'emailcliente',
        'idciudad',
        'direccioncliente',
        'estadocliente',
        'idtipo_cliente',
        'punto',
        'id_clasificacion',
        'name',
        'last_name',
        'dv',
        'type_person',
        'type_document',
        'name_comercial',
        'is_customer'
    ];

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
    /*  public function clientes_cartera($valor)
    {
        $datos = $this->db->query("
            SELECT
                nitcliente,nombrescliente,cliente.id
            FROM pagos
            INNER JOIN 
                cliente
            ON 
                cliente.nitcliente = pagos.nit_cliente
        
            WHERE (
                cliente.nombrescliente ILIKE '%$valor%'
            OR 
                pagos.nit_cliente ILIKE '%$valor%')
            
            ORDER BY cliente.nombrescliente ASC;
         ");
        return $datos->getResultArray();
    } */


    public function clientes_cartera($valor)
    {
        $datos = $this->db->query("
                SELECT
                    c.nitcliente,
                    c.nombrescliente,
                    c.id
                FROM cliente c
                WHERE (
                        c.nombrescliente ILIKE '%$valor%'
                        OR c.nitcliente ILIKE '%$valor%'
                    )
                AND EXISTS (
                    SELECT 1
                    FROM pagos p
                    WHERE p.nit_cliente = c.nitcliente
                )
                ORDER BY c.nombrescliente;
                        ");
        return $datos->getResultArray();
    }

    public function clientesNota($valor)
    {
        $datos = $this->db->query("
        SELECT
    nitcliente,
    nombrescliente,
    telefonocliente,
    id,
direccioncliente
FROM
    cliente
WHERE
    nombrescliente ILIKE '%$valor%'
    OR nitcliente ILIKE '%$valor%'
    OR telefonocliente ILIKE '%$valor%'
ORDER BY
    id;
         ");
        return $datos->getResultArray();
    }
    public function get_tipo_persona($codigo)
    {
        $datos = $this->db->query("
            select * from tipo_persona where codigo= $codigo
         ");
        return $datos->getResultArray();
    }
    public function get_tipos_persona()
    {
        $datos = $this->db->query("
             select * from tipo_persona 

         ");
        return $datos->getResultArray();
    }
    public function get_tipos_documento()
    {
        $datos = $this->db->query("
             select * from documento_identidad

         ");
        return $datos->getResultArray();
    }
}
