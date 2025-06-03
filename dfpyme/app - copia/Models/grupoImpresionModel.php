<?php

namespace App\Models;

use CodeIgniter\Model;

class grupoImpresionModel extends Model
{
    protected $table      = 'grupo_impresion';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'id_impresora_asignada'];

    public function impresoraGrupoImpresion()
    {
        $datos = $this->db->query("
        SELECT impresora.nombre as nombre_impresora,
            grupo_impresion.nombre as nombre_grupo,
            grupo_impresion.id as id_grupo
        FROM   grupo_impresion
       INNER JOIN impresora
               ON impresora.id = grupo_impresion.id_impresora_asignada 
         ");
        return $datos->getResultArray();
    }
}
