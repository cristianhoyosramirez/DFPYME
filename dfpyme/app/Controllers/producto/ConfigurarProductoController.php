<?php

namespace App\Controllers\producto;

use App\Controllers\BaseController;

class ConfigurarProductocontroller extends BaseController
{
    public function atributos()
    {
        $atributos = model('atributosProductoModel')->orderBy('id', 'desc')->findAll();

        return view('producto_atributos/configuracion', [
            'atributos' => $atributos
        ]);
    }

    function addAtributo()
    {

        $json = $this->request->getJSON();
        $nombre = $json->nombre;

        $data = [
            'nombre' => $nombre
        ];

        $existe = model('atributosProductoModel')
            ->select('nombre')
            ->where('LOWER(nombre)', strtolower($nombre))
            ->first();
        if (empty($existe)) {
            $insert = model('atributosProductoModel')->insert($data);

            $atributos = model('atributosProductoModel')->orderBy('id', 'desc')->findAll();

            return $this->response->setJSON([
                'response' => 'success',
                'atributos' => view('producto/atributos', [
                    'atributos' => $atributos
                ])
            ]);
        }
        if (!empty($existe)) {
            return $this->response->setJSON([
                'response' => 'exists',
            ]);
        }
    }

    function validarAtributo()
    {

        $json = $this->request->getJSON();
        $nombre = $json->valor;

        //$existe = model('atributosProductoModel')->select('nombre')->where('nombre', $nombre)->first();

        $existe = model('atributosProductoModel')
            ->select('nombre')
            ->where('LOWER(nombre)', strtolower($nombre))
            ->first();


        if (empty($existe)) {
            return $this->response->setJSON([
                'response' => 'false',
            ]);
        }
        if (!empty($existe)) {
            return $this->response->setJSON([
                'response' => 'true',
            ]);
        }
    }

    function actualizarAtributo()
    {
        $json = $this->request->getJSON();
        $nombre = $json->valor;
        $id = $json->id;

        $update = model('atributosProductoModel')->set('nombre', $nombre)->where('id', $id)->update();

        if ($update) {
            return $this->response->setJSON([
                'response' => 'false',
            ]);
        }
    }

    function crearComponente(){

        $json = $this->request->getJSON();
        $nombre = $json->nombre;
        $idAtributo = $json->idAtributo;


        $data=[

        ];

    }
}
