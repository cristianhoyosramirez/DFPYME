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

    function add_colum_altura(){

        $datos = $this->db->query("
           DO $$
            BEGIN
            IF NOT EXISTS (
            SELECT 1 
            FROM information_schema.columns 
            WHERE table_name = 'configuracion_pedido' 
            AND column_name = 'altura'
            ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN altura INT;
    END IF;
END $$;


         ");
        return $datos->getResultArray();
        
    }
    function add_colum_codigo(){

        $datos = $this->db->query("
       DO $$
        BEGIN
        IF NOT EXISTS (
        SELECT 1 
        FROM information_schema.columns 
        WHERE table_name = 'configuracion_pedido' 
        AND column_name = 'codigo_pantalla'
        ) THEN
        ALTER TABLE configuracion_pedido ADD COLUMN codigo_pantalla boolean;
        ALTER TABLE configuracion_pedido ALTER COLUMN codigo_pantalla SET DEFAULT false;
        COMMENT ON COLUMN configuracion_pedido.codigo_pantalla IS 'Este campo me permite ver en pantalla al momento de llamar los productos si concateno el codigo con el nombre del producto';

    END IF;
    END $$;



         ");
        return $datos->getResultArray();
        
    }
   
}