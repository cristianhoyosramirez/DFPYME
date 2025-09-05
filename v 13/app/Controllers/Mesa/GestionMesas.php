<?php

namespace App\Controllers\Mesa;

use App\Controllers\BaseController;

class GestionMesas extends BaseController
{
    public function index()
    {
        $mesas = model('mesasModel')->mesas();


        //return view('home/home');
        return $this->response->setJSON([
            'response' => 'success',

            'mesas' =>  view('mesa/mesas', [
                'mesas' => $mesas
            ]),

        ]);
    }

    public function eliminar()
    {
        $json = $this->request->getJSON();

        if (!$json || !isset($json->id)) {
            return $this->response->setJSON([
                'response' => 'error',
                'message'  => 'ID inválido'
            ])->setStatusCode(400);
        }

        $id = (int) $json->id;

        $delete = model('mesasModel')->delete($id);

        if ($delete) {
            $salon = model('salonesModel')->select('id')->where('tipo', 1)->first();
            $mesasWhatsApp = model('mesasModel')->where('fk_salon', $salon['id'])->findAll();

            return $this->response->setJSON([
                'response' => 'success',
                'message'  => 'Mesa eliminada correctamente',
                'mesas' => view('mesa/mesasWhatsapp', [
                    'mesas' => $mesasWhatsApp
                ])

            ]);
        } else {
            return $this->response->setJSON([
                'response' => 'error',
                'message'  => 'No se pudo eliminar la mesa'
            ])->setStatusCode(500);
        }
    }

    function edicionMesa()
    {

        $json = $this->request->getJSON();

        $id = (int) $json->id;
        $nombre =  $json->nombre;


        $update = model('mesasModel')->set('nombre', $nombre)->where('id', $id)->update();

        if ($update) {

            return $this->response->setJSON([
                'response' => 'success',
                'message'  => 'Mesa actualizada correctamente',


            ]);
        }
    }
}
