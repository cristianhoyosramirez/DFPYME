<?php

namespace App\Controllers\actualizaciones;
use App\Controllers\BaseController;

class ActualizacionesController extends BaseController
{
    public function index()
    {

        // return view('home/home');
    }

    function Bd()
    {

        /**
         * Actualiazacion del campo 
         */

         $impuestos=model('configuracionPedidoModel')->set('impuesto','true')->update();

        /**
         * Agregar a la tabla configuracion pedido una columna para agregar la url 
         */
        $update_bd=model('ActualizacionesModel')->add_colum_url();

        /**
         * Agregar a la tabla configuracion pedido una columna para la altura de las mesas 
         * 11 de Octubre de 2024  
         */
        $update_bd=model('ActualizacionesModel')->add_colum_altura();
        $update_altura =model('configuracionPedidoModel')->set('altura',30)->update();

        /**
         * Agregar a la tabla configuracion pedido una columna para visualizar el codigo en pantalla o no 
         * Este campo me permite ver en pantalla al momento de llamar los productos si concateno el codigo con el nombre del producto 
         * 12 de Octubre de 2024 
         */
        $alter_codigo=model('ActualizacionesModel')->add_colum_codigo();
        
        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Actualización a base de datos éxitosa ');
    }
}
