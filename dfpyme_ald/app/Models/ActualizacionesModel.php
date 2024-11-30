<?php

namespace App\Models;

use CodeIgniter\Model;

class ActualizacionesModel extends Model
{
    public function add_colum_url()
    {
        $datos = $this->db->query("
            DO $$
            BEGIN
            IF NOT EXISTS (
            SELECT 1 
            FROM information_schema.columns 
            WHERE table_name = 'configuracion_pedido' 
            AND column_name = 'url'
        ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN url VARCHAR(50);
        END IF;
        END $$;

         ");
        return $datos->getResultArray();
    }

    function Qr(){
        
    }
   
}