<?php

namespace App\Controllers;

class Login extends BaseController
{
    public function index()
    {
        return view('login/login');
    }

    public function crearActualizaciones(){
        
        return view('actualizaciones/generarActualizaciones');
    }


    function crearVersion()
    {

        $json = $this->request->getJSON();
        $actualizacion = $json->actualizacion; 
        //$actualizacion = 11;


        switch ($actualizacion) {
            case 1:
                $actualizar = model('ActualizacionesModel')->uno();

                return $this->response->setJSON([
                    'response' => 'success',

                ]);

                break;
            case 11:
                $actualizar = model('ActualizacionesModel')->once();

                return $this->response->setJSON([
                    'response' => 'success',

                ]);

                break;
        }
    }


}
