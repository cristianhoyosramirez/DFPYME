<?php

namespace App\Models;

use CodeIgniter\Model;

class configuracionPedidoModel extends Model
{
    protected $table      = 'configuracion_pedido';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [
        'agregar_item',
        'propina',
        'mesero_pedido',
        'valor_defecto_propina',
        'sub_categoria',
        'borrar_remisiones',
        'partir_comanda',
        'producto_favoroitos',
        'requiere_mesa',
        'encabezado_factura',
        'pie_factura',
        'eliminar_factura_electronica',
        'impuesto',
        'comanda',
        'calculo_propina',
        'url',
        'altura',
        'codigo_pantalla',
        'notaPedido',
        'mostrarmesero',
        'recetasmodal',
        'criterio_impresion_comanda',
        'version',
        'numero_copias_comanda',
        'reimpresion_comanda',
        'mostrar_boton_imprimir_bono',
        'terminos_condiones',
        'numero_copias_comanda',
        'espacios_comanda_encabezado',
        'espacios_comanda_pie',
        'tamano_comanda',
        'mostrar_boton_mitad',
        'texto_propina',
        'permitir_impresion_texto_propina',
        'consultar_pedidos_whatsapp',
        'precios_comanda',
        'informe_fiscal',
        'beep',
        'permitir_impresion_texto_propina',
        'facturar_cero',
        'reimpresion_meseros',
        'justificacion',
        'preguntar_impresora_prefactura',
        'ip',
        'propina_sobre_base_tributaria',
        'justificacion_producto',
        'justificacion_pedido',
        'facturacion',
        'borrarFe',
        'reporte_hotel',
        'propina_auto',
        'propina_manual',
        'titulo_pedido'
    ];

    public function reporte_hotel($id_apertura)
    {
        $datos = $this->db->query("
                    SELECT SUM(total) as total
                FROM kardex
                WHERE codigo IN ('512', '429')
                AND id_apertura = $id_apertura
        ");
        return $datos->getResultArray();
    }

    public function existeCampo()
    {
        $datos = $this->db->query("
               SELECT EXISTS (
    SELECT 1
    FROM information_schema.columns
    WHERE table_name = 'configuracion_pedido'
    AND column_name = 'reporte_hotel'
);
        ");
        return $datos->getResultArray();
    }
}
