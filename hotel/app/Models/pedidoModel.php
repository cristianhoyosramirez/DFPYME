<?php

namespace App\Models;

use CodeIgniter\Model;

class pedidoModel extends Model
{
    protected $table      = 'pedido';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'fk_mesa',
        'fk_usuario',
        'valor_total',
        'nota_pedido',
        'cantidad_de_productos',
        'fecha',
        'numero_factura',
        'base_iva',
        'impuesto_iva',
        'base_ico',
        'impuesto_ico',
        'consulta_valor_pedido',
        'propina',
        'propina_parcial'
    ];

    protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_actualizacion';
    protected $deletedField  = 'deleted_at';

       public function insertar(
        $ultimo_id_pedido,
        $valor_unitario,
        $se_imprime_en_comanda,
        $codigo_categoria,
        $codigo_interno_producto,
        $cantidad,
        $idUser,
        $fecha,
        $hora,
        $nota
    ) {

        $data = [
            'numero_de_pedido' => $ultimo_id_pedido,
            'cantidad_producto' => $cantidad,
            'nota_producto' => $nota,
            'valor_unitario' => $valor_unitario,
            'impresion_en_comanda' => false,
            'cantidad_entregada' => 0,
            'valor_total' => $valor_unitario * $cantidad,
            'se_imprime_en_comanda' => $se_imprime_en_comanda,
            'codigo_categoria' => $codigo_categoria,
            'codigointernoproducto' => $codigo_interno_producto,
            'numero_productos_impresos_en_comanda' => 0,
            'idUsuario' => $idUser,
            'fecha' => $fecha,
            'hora' => $hora
        ];

        $productos = $this->db->table('producto_pedido');
        $productos->insert($data);

        return $this->db->insertID();
    }
   
}
