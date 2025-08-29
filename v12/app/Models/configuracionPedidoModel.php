<?php

namespace App\Models;

use CodeIgniter\Model;

class configuracionPedidoModel extends Model
{
    protected $table      = 'configuracion_pedido';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'agregar_item', 'propina', 'mesero_pedido',
        'valor_defecto_propina', 'sub_categoria', 'borrar_remisiones', 'partir_comanda', 'producto_favoroitos', 'requiere_mesa',
        'encabezado_factura', 'pie_factura','eliminar_factura_electronica','impuesto','comanda','calculo_propina','url','altura',
        'codigo_pantalla',
        'notaPedido','mostrarmesero','recetasmodal','criterio_impresion_comanda','version',
        'numero_copias_comanda','reimpresion_comanda','mostrar_boton_imprimir_bono','terminos_condiones',
        'numero_copias_comanda','espacios_comanda_encabezado','espacios_comanda_pie','tamano_comanda','mostrar_boton_mitad',
        'texto_propina','permitir_impresion_texto_propina','consultar_pedidos_whatsapp'
    ];
}
