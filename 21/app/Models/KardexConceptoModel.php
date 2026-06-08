<?php

namespace App\Models;

use CodeIgniter\Model;

class KardexConceptoModel extends Model
{
    protected $table      = 'concepto_kardex';
    // Uncomment below if you want add primary key
   // protected $primaryKey = 'id';
    protected $allowedFields = ['idoperacion','nombre','estado'];


      public function getHoras($inicio , $fin )
    {
        $datos = $this->db->query("
       
            SELECT distinct(hora)
            FROM kardex
            WHERE fecha_y_hora_factura_venta BETWEEN '$inicio' AND '$fin';
         ");
        return $datos->getResultArray();
    }

   
}