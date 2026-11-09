<?php

namespace App\Models;

use CodeIgniter\Model;

class ProveedorModel extends Model
{
    protected $table      = 'proveedor';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'codigointernoproveedor',
        'nitproveedor',
        'idregimen',
        'razonsocialproveedor',
        'nombrecomercialproveedor',
        'descripcionproveedor',
        'direccionproveedor',
        'idciudad',
        'telefonoproveedor',
        'celularproveedor',
        'faxproveedor',
        'emailproveedor',
        'webproveedor',
        'estadoproveedor',
    ];

    public function autoComplete($valor)
    {

        $datos = $this->db->query("
                SELECT codigointernoproveedor, nombrecomercialproveedor, nitproveedor
FROM proveedor
WHERE (nombrecomercialproveedor ILIKE '%$valor%' 
       OR nitproveedor ILIKE '%$valor%')
  AND estadoproveedor = 'true';

        ");
        return $datos->getResultArray();
    }
}
