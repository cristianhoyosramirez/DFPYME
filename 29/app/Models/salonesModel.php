<?php

namespace App\Models;

use CodeIgniter\Model;

class salonesModel extends Model
{
    protected $table      = 'salones';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'nombre', 'tipo'];

public function mesas($id_salon, $nombre, $numeroMesas)
{
    $sql = "
        INSERT INTO public.mesas (fk_salon, nombre, estado, valor_pedido, fk_usuario, tiene_pedido)
        SELECT :id_salon:,
               :nombre: || i,
               0,
               0,
               8,
               false
        FROM generate_series(1, :numeroMesas:) AS s(i);
    ";

    // Ejecutar query con parámetros
    $this->db->query($sql, [
        'id_salon'    => $id_salon,
        'nombre'      => $nombre . ' ', // espacio para que quede "WHATSAPP 1"
        'numeroMesas' => $numeroMesas
    ]);

    // Como es un INSERT no hay getResultArray(), mejor devolver filas afectadas
    return $this->db->affectedRows();
}

}
