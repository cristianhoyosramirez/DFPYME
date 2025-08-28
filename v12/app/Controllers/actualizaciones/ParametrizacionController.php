<?php

namespace App\Controllers\actualizaciones;

use App\Controllers\BaseController;

class ParametrizacionController extends BaseController
{
    public function parametrizacion()
    {

        $codigo_pantalla = model('configuracionPedidoModel')->select('codigo_pantalla')->first();
        $altura = model('configuracionPedidoModel')->select('altura')->first();
        $mostrar_boton_mitad = model('configuracionPedidoModel')->select('mostrar_boton_mitad')->first();

        return view('parametrizacion/parametrizacion', [
            'codigo_pantalla' => $codigo_pantalla['codigo_pantalla'],
            'altura' => $altura['altura'],
            'mostrar_boton_mitad' => $mostrar_boton_mitad['mostrar_boton_mitad']
        ]);
    }


    function actualizar_codigo()
    {
        $opcion = $this->request->getPost('opcion');

        $update = model('configuracionPedidoModel')->set('codigo_pantalla', $opcion)->update();

        if ($update) {
            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    }
    function actualizar_altura()
    {
        $altura = $this->request->getPost('altura');

        $update = model('configuracionPedidoModel')->set('altura', $altura)->update();

        if ($update) {
            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    }

    function encabezadoComanda()
    {

        $json = $this->request->getJSON();
        $valor = $json->valor;

        $update = model('configuracionPedidoModel')->set('espacios_comanda_encabezado', $valor)->update();


        return $this->response->setJSON([
            'response' => 'success',
        ]);
    }
    function pieComanda()
    {

        $json = $this->request->getJSON();
        $valor = $json->valor;

        $update = model('configuracionPedidoModel')->set('espacios_comanda_pie', $valor)->update();


        return $this->response->setJSON([
            'response' => 'success',
        ]);
    }

    function actualizar_boton_mitad()
    {

        $json = $this->request->getJSON();
        $valor = $json->mostrar_boton_mitad;

        $actualizar = model('configuracionPedidoModel')->set('mostrar_boton_mitad', $valor)->update();

        if ($actualizar) {
            return $this->response->setJSON([
                'response' => 'success',
            ]);
        }
    }

    function consultarPrecio()
    {

       // $json = $this->request->getJSON();
        //$id_producto = $json->id_tabla_producto;

        $id_producto = $this->request->getPost('id_tabla_producto');

         $codigo_interno = model('productoPedidoModel')->select('codigointernoproducto')->where('id', $id_producto)->first();
         $valorVenta = model('productoModel')->select('valorventaproducto')->where('codigointernoproducto', $codigo_interno['codigointernoproducto'])->first();

           $returnData = array(
            "resultado" => 1, 
            "precio"=>$valorVenta['valorventaproducto']
        );
        echo  json_encode($returnData);;
    }
}
