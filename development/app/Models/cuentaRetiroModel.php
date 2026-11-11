<?php

namespace App\Models;

use CodeIgniter\Model;

class cuentaRetiroModel extends Model
{
    protected $table      = 'cuenta_retiro';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_cuenta'];

    public function get_cuentas_rubros($id)
    {
        $datos = $this->db->query("
                select nombre_rubro,id from rubro_cuenta_retiro  where id_cuenta_retiro= $id;
        ");
        return $datos->getResultArray();
    }
}
