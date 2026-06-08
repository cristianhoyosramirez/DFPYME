<?php

namespace App\Models;

use CodeIgniter\Model;

class regimenModel extends Model
{
    protected $table      = 'regimen';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_regimen', 'descripcion'];


    public function regimen()
    {
        $datos = $this->db->query("
       select regimen.descripcion from regimen 
inner join empresa on empresa.idregimen= regimen.idregimen 

        ");
        return $datos->getResultArray();
    }
}
