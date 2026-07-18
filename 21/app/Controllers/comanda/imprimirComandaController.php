<?php

namespace App\Controllers\comanda;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class imprimirComandaController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function imprimir_comanda_desde_pedido()
    {

        $numero_pedido = $_POST['numero_pedido'];

        /**
         * Comprobacion de que en la columna se_imprime de la tabla producto_pedido hayan valores en false 
         */
        $imprimir_comanda = model('productoPedidoModel')->select('impresion_en_comanda')->where('numero_de_pedido', $numero_pedido)->find();


        $codigo_categoria = model('productoPedidoModel')->id_categoria($numero_pedido);

        foreach ($codigo_categoria as $valor) {
            $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $valor['codigo_categoria'])->first();
            $impresora = model('categoriasModel')->select('impresora')->where('codigocategoria', $valor['codigo_categoria'])->first();
            $this->generar_comanda_desde_pedido($numero_pedido, $nombre_categoria['nombrecategoria'], $impresora['impresora'], $valor['codigo_categoria']);
        }
    }

    public function imprimir_comanda()
    {


        $numero_pedido = $_POST['numero_pedido_imprimir_comanda'];
        //$numero_pedido = 31883;


        if ($numero_pedido == "") {
            $returnData = array(
                "resultado" => 0,

            );
            echo  json_encode($returnData);
        } else {
            /**
             * Comprobacion de que en la columna se_imprime de la tabla producto_pedido hayan valores en false 
             */
            //$imprimir_comanda = model('productoPedidoModel')->imprimir_productos_pedido($numero_pedido);

            //if (!empty($imprimir_comanda)) {

            $codigo_categoria = model('productoPedidoModel')->id_categoria($numero_pedido);

            if ($codigo_categoria) {

                foreach ($codigo_categoria as $valor) {

                    $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $valor['codigo_categoria'])->first();
                    $impresora = model('categoriasModel')->select('impresora')->where('codigocategoria', $valor['codigo_categoria'])->first();

                    $this->generar_comanda($numero_pedido, $nombre_categoria['nombrecategoria'], $impresora['impresora'], $valor['codigo_categoria']);
                }




                $returnData = array(
                    "resultado" => 1,

                );
                echo  json_encode($returnData);
            } else {
                $returnData = array(
                    "resultado" => 0,

                );
                echo  json_encode($returnData);
            }
        }
    }


    public function generar_comanda($numero_pedido, $nombre_categoria, $id_impresora, $id_categoria)
    {

        //$no_se_imprime_en_comanda = model('productoPedidoModel')->no_se_imprime_en_comanda($numero_pedido);

        /*  foreach ($no_se_imprime_en_comanda as $valor) {
            $impresion_en_comanda = [
                'impresion_en_comanda' => true,
            ];

            $actualizar = model('productoPedidoModel')->set($impresion_en_comanda);
            $actualizar = model('productoPedidoModel')->where('id', $valor['id']);
            $actualizar = model('productoPedidoModel')->update();
        }
 */
        $items = model('productoPedidoModel')->productos_pedido($numero_pedido, $id_categoria);


        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora)->first();


        $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
        $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
        $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa['fk_mesa'])->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();



        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);


        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text("**" . $nombre_categoria . "**" . "\n");



        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);
        $printer->text("Pedido N°" . $numero_pedido . "Mesa N°" . $nombre_mesa['nombre'] . "\n");

        $printer->text("Mesero: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

        $printer->text("Fecha :" . "   " . date('d/m/Y ') . "Hora  :" . "   " . date('h:i:s a', time()) . "\n\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);


        foreach ($items as $productos) {


            $cantidad_productos_impresos = model('productoPedidoModel')->select('numero_productos_impresos_en_comanda')->where('id', $productos['id'])->first();
            $cantidad_productos = model('productoPedidoModel')->select('cantidad_producto')->where('id', $productos['id'])->first();



            $data = [
                'numero_productos_impresos_en_comanda' => $cantidad_productos_impresos['numero_productos_impresos_en_comanda'] + ($cantidad_productos['cantidad_producto'] - $cantidad_productos_impresos['numero_productos_impresos_en_comanda']),
            ];

            $actualizar = model('productoPedidoModel')->set($data);
            $actualizar = model('productoPedidoModel')->where('id', $productos['id']);
            $actualizar = model('productoPedidoModel')->update();
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(2, 1);
            $printer->text($productos['nombreproducto'] . "\n");
            $printer->text("Cant. " . $cantidad_productos['cantidad_producto'] - $cantidad_productos_impresos['numero_productos_impresos_en_comanda'] . "\n");
            if (!empty($productos['nota_producto'])) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("NOTAS:\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text($productos['nota_producto'] . "\n");
            }
            $printer->text("-----------------------\n");
            $printer->text("\n");
        }
        $observaciones_genereles = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
        if (!empty($observaciones_genereles['nota_pedido'])) {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 1);
            $printer->text("OBSERVACIONES GENERALES \n");
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(2, 1);
            $printer->text($observaciones_genereles['nota_pedido'] . "\n");
            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
            */
            //$printer->cut();

            /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
            */
            //$printer->pulse();
            //$printer->close();
            # $printer = new Printer($connector);

            //$milibreria = new Ejemplolibreria();
            //$data = $milibreria->getRegistros();
        }
        $printer->cut();

        $printer->close();
    }
    public function generar_comanda_desde_pedido($numero_pedido, $nombre_categoria, $id_impresora, $id_categoria)
    {
        $no_se_imprime_en_comanda = model('productoPedidoModel')->no_se_imprime_en_comanda($numero_pedido);

        foreach ($no_se_imprime_en_comanda as $valor) {
            $impresion_en_comanda = [
                'impresion_en_comanda' => true,
            ];

            $actualizar = model('productoPedidoModel')->set($impresion_en_comanda);
            $actualizar = model('productoPedidoModel')->where('id', $valor['id']);
            $actualizar = model('productoPedidoModel')->update();
        }
        $items = model('productoPedidoModel')->productos_pedido($numero_pedido, $id_categoria);

        if (!empty($items)) {

            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora)->first();

            $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa['fk_mesa'])->first();
            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();


            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
            $printer->text("**" . $nombre_categoria . "**" . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text("Pedido N°" . $numero_pedido . "\n");
            $printer->text("Mesa N°" . $nombre_mesa['nombre'] . "\n");
            $printer->text("Mesero: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("Fecha :" . "   " . date('d/m/Y ') . "\n");
            $printer->text("Hora  :" . "   " . date('h:i:s a', time()) . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("----------------------------------------------- \n");
            $printer->text("CÓDIGO   PRODUCTO      CANTIDAD     NOTAS  \n");
            $printer->text("----------------------------------------------- \n");


            $items = model('productoPedidoModel')->productos_pedido($numero_pedido, $id_categoria);

            foreach ($items as $productos) {

                $data = [
                    'impresion_en_comanda' => true,
                ];

                $actualizar = model('productoPedidoModel')->set($data);
                $actualizar = model('productoPedidoModel')->where('id', $productos['id']);
                $actualizar = model('productoPedidoModel')->update();
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(2, 1);
                $printer->text($productos['nombreproducto'] . "\n");
                $printer->text("Cant. " . $productos['cantidad_producto'] . "\n");
                if (!empty($productos['nota_producto'])) {
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text("NOTAS:\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text($productos['nota_producto'] . "\n");
                }
                $printer->text("-----------------------\n");
                $printer->text("\n");
            }

            $observaciones_genereles = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
            if (!empty($observaciones_genereles['nota_pedido'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 2);
                $printer->text("** OBSERVACIONES GENERALES **\n");
                $printer->text("\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(2, 1);
                $printer->text($observaciones_genereles['nota_pedido'] . "\n");
            }
            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
            */
            $printer->cut();

            /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
            */
            //$printer->pulse();
            $printer->close();
            # $printer = new Printer($connector);

            //$milibreria = new Ejemplolibreria();
            //$data = $milibreria->getRegistros();
            $returnData = array(
                "resultado" => 1
            );
            echo  json_encode($returnData);
        }

        if (empty($items)) {
            $returnData = array(
                "resultado" => 0
            );
            echo  json_encode($returnData);
        }
    }

    public function re_imprimir_comanda()
    {

        //$numero_pedido = 32425;
        $numero_pedido = $_POST['numero_de_pedido_reimprimir_comanda'];

        //$imprimir_comanda = model('productoPedidoModel')->select('impresion_en_comanda')->where('numero_de_pedido', $numero_pedido)->find();
        $codigo_categoria = model('productoPedidoModel')->id_categoria_2($numero_pedido);

        foreach ($codigo_categoria as $valor) {
            $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $valor['codigo_categoria'])->first();
            $impresora = model('categoriasModel')->select('impresora')->where('codigocategoria', $valor['codigo_categoria'])->first();
            $this->generar_reimpresion_comanda($numero_pedido, $nombre_categoria['nombrecategoria'], $impresora['impresora'], $valor['codigo_categoria']);
        }
        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('pedido/pedidos_para_facturar'))->with('mensaje', 'Impresión de comanda exitoso');
    }

    public function generar_reimpresion_comanda($numero_pedido, $nombre_categoria, $id_impresora, $id_categoria)
    {

        $items = model('productoPedidoModel')->reimprimir_productos_pedido($numero_pedido, $id_categoria);

        if (!empty($items)) {
            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora)->first();


            $id_usuario = model('pedidoModel')->select('fk_usuario')->where('id', $numero_pedido)->first();
            $id_mesa = model('pedidoModel')->select('fk_mesa')->where('id', $numero_pedido)->first();
            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $id_mesa['fk_mesa'])->first();
            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();


            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("REIMPRESION" . "\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
            $printer->text("**" . $nombre_categoria . "**" . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text("Pedido N°" . $numero_pedido . "\n");
            $printer->text("Mesa N°" . $nombre_mesa['nombre'] . "\n");
            $printer->text("Mesero: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("Fecha :" . "   " . date('d/m/Y ') . "\n");
            $printer->text("Hora  :" . "   " . date('h:i:s a', time()) . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("----------------------------------------------- \n");
            $printer->text("CÓDIGO   PRODUCTO      CANTIDAD     NOTAS  \n");
            $printer->text("----------------------------------------------- \n");


            // $items = model('productoPedidoModel')->productos_pedido($numero_pedido, $id_categoria);
            $items = model('productoPedidoModel')->reimprimir_comanda($numero_pedido, $id_categoria);

            foreach ($items as $productos) {

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(2, 1);
                $printer->text($productos['nombreproducto'] . "\n");
                $printer->text("Cant. " . $productos['cantidad_producto'] . "\n");
                if (!empty($productos['nota_producto'])) {
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text("NOTAS:\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text($productos['nota_producto'] . "\n");
                }
                $printer->text("------------------------\n");
                $printer->text("\n");
            }

            $observaciones_genereles = model('pedidoModel')->select('nota_pedido')->where('id', $numero_pedido)->first();
            if (!empty($observaciones_genereles['nota_pedido'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(2, 1);
                $printer->text("OBSERVACIONES GENERALES\n");
                $printer->text("\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(2, 1);
                $printer->text($observaciones_genereles['nota_pedido'] . "\n");
            }
            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
            */
            $printer->cut();

            /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
            */
            //$printer->pulse();
            $printer->close();
            # $printer = new Printer($connector);

            //$milibreria = new Ejemplolibreria();
            //$data = $milibreria->getRegistros();

        } else {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'warning');
            return redirect()->to(base_url('salones/salones'))->with('mensaje', 'No hay productos para imprimir');
        }
    }

    function directa()
    {
        //$numero_pedido = 225;
        $numero_pedido = model('pedidoPosModel')->select('id')->where('fk_usuario', $this->request->getPost('usuario'))->first();



        if (empty($numero_pedido)) {
            $returnData = array(
                "resultado" => 0,

            );
            echo  json_encode($returnData);
        }

        if (!empty($numero_pedido)) {
            $id_impresora = model('precuentaModel')->select('id_impresora')->first();
            $nombre_de_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
            $connector = new WindowsPrintConnector($nombre_de_impresora['nombre']);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("PREFACTURA" . "\n");



            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);

            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $this->request->getPost('usuario'))->first();
            $printer->text("Atendido por : " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("Fecha :" . "   " . date('d/m/Y ') . "\n");
            $printer->text("Hora  :" . "   " . date('h:i:s a', time()) . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("----------------------------------------------- \n");
            $printer->text("CÓDIGO   PRODUCTO      CANTIDAD     NOTAS  \n");
            $printer->text("----------------------------------------------- \n");


            $items = model('productoPedidoPosModel')->pre_factura($numero_pedido['id']);


            foreach ($items as $productos) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 1);
                $printer->text("Cod." . $productos['codigointernoproducto'] . "      " . $productos['nombreproducto'] . "\n");
                $printer->text("Cant. " . $productos['cantidad_producto'] . "      " . "$" . number_format($productos['valor_unitario'], 0, ',', '.') . "                   " . "$" . number_format($productos['valor_total'], 0, ',', '.') . "\n");
                if (!empty($productos['nota_producto'])) {
                    $printer->setJustification(Printer::JUSTIFY_CENTER);
                    $printer->text("NOTAS:\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->text($productos['nota_producto'] . "\n");
                }
                $printer->text("_______________________________________________ \n");
                $printer->text("\n");
            }

            $observacion_general = model('pedidoPosModel')->select('nota_general')->where('id', $numero_pedido['id'])->first();
            if (!empty($observacion_general['nota_general'])) {
                $printer->setTextSize(2, 1);
                $printer->text("OBSERVACION GENERAL\n");
                $printer->text($observacion_general['nota_general'] . "\n");
            }

            $total = model('pedidoPosModel')->select('valor_total')->where('id', $numero_pedido['id'])->first();
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->setTextSize(1, 1);
            $printer->text("TOTAL :" . "$" . number_format($total['valor_total'], 0, ",", ".") . "\n");
            $printer->text("---------------------------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("GRACIAS POR SU VISITA " . "\n");

            /*
            Cortamos el papel. Si nuestra impresora
            no tiene soporte para ello, no generará
            ningún error
            */
            $printer->cut();

            /*
            Por medio de la impresora mandamos un pulso.
            Esto es útil cuando la tenemos conectada
            por ejemplo a un cajón
            */
            //$printer->pulse();
            $printer->close();
            # $printer = new Printer($connector);
            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    }

    function imprimir_compra()
    {

        $id = $this->request->getPost('id');

        /**
         * Datos del proveedor y productos de la compra con el total 
         */
        $codigo_proveedor = model('ComprasModel')->codigo_proveedor($id);
        $fecha_compra = model('ComprasModel')->fecha_compra($id);
        $nombre_proveedor = model('ProveedorModel')->select('nombrecomercialproveedor')->where('codigointernoproveedor', $codigo_proveedor[0]['codigointernoproveedor'])->first();
        $productos = model('ComprasModel')->productos_compra($id);

        $total = model('ComprasModel')->total_productos_compra($id);

        /**
         * Datos de la empresa 
         */
        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("**IMPRESIÓN DE COMPRA ** \n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("PROVEEDOR: " . $nombre_proveedor['nombrecomercialproveedor'] . "\n");
        $printer->text("FECHA    : " . $fecha_compra[0]['fecha_factura'] . "\n\n");



        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CODIGO    DESCRIPCION   VALOR UNITARIO    TOTAL" . "\n");
        $printer->text("---------------------------------------------" . "\n\n");

        foreach ($productos as $detalle) {

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Cod." . $detalle['codigointernoproducto'] . "      " . $detalle['nombreproducto'] . "\n");
            $printer->text("Cant. " . $detalle['cantidad'] . "      " . "$" . number_format($detalle['valor_unitario'], 0, ',', '.') . "                   " . "$" . number_format($detalle['cantidad'] * $detalle['valor_unitario'], 0, ',', '.') . "\n");
            $printer->text("---------------------------------------------" . "\n");
        }

        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->setTextSize(1, 2);
        $printer->text("TOTAL  $" . number_format($total[0]['total_factura'], 0, ',', '.') . "\n");


        $nota = model('FacturaCompraModel')->select('nota')->where('numeroconsecutivofactura_proveedor', $id)->first();

        if (!empty($nota['nota'])) {
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text("\n");
            $printer->text("NOTA: " . $nota['nota'] . "\n");
        }

        $printer->text("\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();
        $json_data = [
            'resultado' => 1

        ];

        echo  json_encode($json_data);
    }

    function impresion_compra()
    {

        $id_usuario = $this->request->getPost('id_usuario');
        //$id_usuario = 6;

        /**
         * Datos del proveedor y productos de la compra con el total 
         */


        $productos = model('ProductoCompraModel')->productos_compra($id_usuario);
        $total = model('ProductoCompraModel')->total_compra($id_usuario);

        /**
         * Datos de la empresa 
         */
        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("**IMPRESIÓN DE COMPRA ** \n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);




        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CODIGO    DESCRIPCION   VALOR UNITARIO    TOTAL" . "\n");
        $printer->text("---------------------------------------------" . "\n\n");

        foreach ($productos as $detalle) {

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Cod." . $detalle['codigointernoproducto'] . "      " . $detalle['nombreproducto'] . "\n");
            $printer->text("Cant. " . $detalle['cantidad'] . "      " . "$" . number_format($detalle['valor_unitario'], 0, ',', '.') . "                   " . "$" . number_format($detalle['cantidad'] * $detalle['valor_unitario'], 0, ',', '.') . "\n");
            $printer->text("---------------------------------------------" . "\n");
        }

        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->setTextSize(1, 2);
        $printer->text("TOTAL  $" . number_format($total[0]['total_factura'], 0, ',', '.') . "\n");

        $printer->text("\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();
        $json_data = [
            'resultado' => 1

        ];

        echo  json_encode($json_data);
    }

    function imprimir_movimiento()
    {

        $id_usuario = $this->request->getPost('id_usuario');
        //$id_usuario = 6;

        /**
         * Datos del proveedor y productos de la compra con el total 
         */


        $productos = model('TempMovModel')->where('id_usuario', $id_usuario)->findAll();


        /**
         * Datos de la empresa 
         */
        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("**MOVIMIENTO DE PRODUCTO  ** \n\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);


        $printer->setTextSize(1, 1);
        foreach ($productos as $detalle) {


            //$printer->text("Producto:        " . str_pad($detalle['producto'], 20) . "\n");
            //$printer->text("Fecha:           " . str_pad($detalle['fecha'], 20) . "\n");
            $printer->text("Movimiento:      " . str_pad($detalle['movimiento'], 20) . "\n");
            $printer->text("Cantidad inicial:" . str_pad($detalle['cantidad_inicial'], 20, " ") . "\n");
            $printer->text("Cantidad movi:   " . str_pad($detalle['cantidad_movi'], 20, " ") . "\n");
            $printer->text("Cantidad final:  " . str_pad($detalle['cantidad_final'], 20, " ") . "\n");
            $printer->text("Documento:       " . str_pad($detalle['documento'], 20) . "\n");
            $printer->text("Usuario:         " . str_pad($detalle['usuario'], 20) . "\n");
            $printer->text("Nota:            " . str_pad($detalle['nota'], 20) . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("---------------------------------\n");
        }


        $printer->text("\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();
        $json_data = [
            'resultado' => 1

        ];

        echo  json_encode($json_data);
    }


    public function imprimir_nc()
    {
        try {

            // 📥 Recibir JSON
            $data = $this->request->getJSON(true);
            $id = $data['id'] ?? null;
            $datosNc = model('notaCreditoModel')->select('id_factura,prefijo,numero,qrcode,cufe')
                ->where('id', $id)
                ->first();

            //$datosNc= ;

            //$datosFe = model('facturaElectronicaModel')->select('numero')->where('id',  $datosNc['id_factura'])->first();

            $datosFe = model('facturaElectronicaModel')
                ->select('metodo_pago,medio_pago,fecha,fecha_limite,hora,qrcode')
                ->where('id', $datosNc['id_factura'])->first();

            if ($datosFe['metodo_pago'] == 1) {
                $venta = "CONTADO";
            }

            if ($datosFe['metodo_pago'] == 2) {
                $venta = "CRÉDITO";
            }

            if (!$id) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'ID no recibido'
                ]);
            }

            /**
             * 🏢 Datos empresa
             */
            $id_impresora = model('impresionFacturaModel')
                ->select('id_impresora')
                ->first();

            $datos_empresa = model('empresaModel')->datosEmpresa();

            $nombre_impresora = model('impresorasModel')
                ->select('nombre')
                ->where('id', $id_impresora['id_impresora'])
                ->first();

            // 🖨️ Conexión impresora
            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
            $printer = new Printer($connector);

            /**
             * 🧾 ENCABEZADO
             */
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);

            $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
            $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
            $printer->text("NIT: " . $datos_empresa[0]['nitempresa'] . "\n");
            $printer->text($datos_empresa[0]['direccionempresa'] . "\n");
            $printer->text($datos_empresa[0]['telefonoempresa'] . "\n");
            $printer->text($datos_empresa[0]['nombreregimen'] . "\n\n");

            /**
             * 🧾 TITULO
             */
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("NOTA CRÉDITO NÚMERO: " . $datosNc['prefijo'] . $datosNc['numero'] . "\n");
            $printer->text("FORMA DE PAGO: " . $venta . "\n");
            $printer->text("FECHA DE GENERACIÓN: " . $datosFe['fecha'] . "\n");
            $printer->text("FECHA LIMITE: " . $datosFe['fecha_limite'] . "\n");
            $printer->text("CAJA: 1" . "\n");
            $printer->text("CAJERO:  Usuario administrador" . "\n");

            $nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $datosNc['id_factura'])->first();
            $nombres_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
            $direccion = model('clientesModel')->select('direccioncliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
            $telefono = model('clientesModel')->select('telefonocliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
            $email = model('clientesModel')->select('emailcliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();



            $printer->text("---------------------------------------------" . "\n");
            $printer->text("CLIENTE:     " . $nombres_cliente['nombrescliente'] . "\n");
            $printer->text("NIT/CC:      " . $nit_cliente['nit_cliente']  . "\n");
            $printer->text("DIRECCIÓN:   " . $direccion['direccioncliente']  . "\n");
            $printer->text("TELEFÓNO    " . $telefono['telefonocliente'] . "\n");
            $printer->text("EMAIL:       " . $email['emailcliente'] . "\n");

            $printer->text("\n");

            /**
             * 📦 DETALLE HEADER
             */
            $printer->text("Cant   Descripción        Unitario     Total\n");
            $printer->text("------------------------------------------\n");

            $items = model('itemNotaCreditoModel')
                ->select('codigo,descripcion,cantidad,neto,total')
                ->where('id_nota', $id)
                ->findAll();

            foreach ($items as $item) {

                $cantidad = str_pad($item['cantidad'], 6, ' ', STR_PAD_RIGHT);

                $descripcion = str_pad(substr($item['descripcion'], 0, 18), 18, ' ', STR_PAD_RIGHT);

                $unitario = str_pad(number_format($item['neto'], 0, ',', '.'), 12, ' ', STR_PAD_LEFT);

                $total = str_pad(number_format($item['total'], 0, ',', '.'), 10, ' ', STR_PAD_LEFT);

                $printer->text(
                    $cantidad .
                        $descripcion .
                        $unitario .
                        $total . "\n"
                );
            }
            $printer->text("------------------------------------------\n");
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text(str_pad("SUB TOTAL", 15) . ": " . str_pad("$ " . number_format(0, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("PROPINA", 15) . ": " . str_pad("$ " . number_format(0, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("INC", 15) . ": " . str_pad("$ " . number_format(0, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("IVA", 15) . ": " . str_pad("$ " . number_format(0, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("TOTAL", 15) . ": " . str_pad("$ " . number_format(0, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text("\n");

            $printer->qrCode($datosNc['qrcode'], Printer::QR_ECLEVEL_L, 4);


            $printer->text("\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("\n");
            $printer->text("CUFE: \n" . $datosNc['cufe'] . "\n");

            /**
             * ✂️ CIERRE
             */
            $printer->feed(2);
            $printer->cut();
            $printer->close();

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Impresión realizada correctamente'
            ]);
        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
