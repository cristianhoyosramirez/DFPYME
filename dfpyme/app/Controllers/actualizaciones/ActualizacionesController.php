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
         * Agregar a la tabla configuracion pedido una columna para agregar la url 
         */
        $update_bd=model('ActualizacionesModel')->add_colum_url();

        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Actualización a base de datos éxitosa ');
    }
}
