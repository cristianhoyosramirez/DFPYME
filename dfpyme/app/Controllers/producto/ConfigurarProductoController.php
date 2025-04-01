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

    function crearComponente()
    {

        $json = $this->request->getJSON();
        $nombre = $json->nombre;
        //$nombre = 'GUANABANA EN AGUA ';
        $idAtributo = $json->idAtributo;
        // $idAtributo = 5;

        $existe = model('componentesAtributosProductoModel')->where('nombre', $nombre)->first();


        if (empty($existe)) {
            $data = [
                'nombre' => $nombre,
                'id_atributo' => $idAtributo

            ];


            $insert = model('componentesAtributosProductoModel')->insert($data);

            if ($insert) {
                $componentes = model('componentesAtributosProductoModel')->where('id_atributo', $idAtributo)->findAll();

                return $this->response->setJSON([
                    'response' => 'success',
                    'componentes' => view('producto/componentes', [
                        'componentes' => $componentes,
                        'idAtributo' => $idAtributo
                    ]),
                    'id' => $idAtributo
                ]);
            }
        }
        if (!empty($existe)) {
            return $this->response->setJSON([
                'response' => 'exists',
            ]);
        }
    }

    function deleteComponente()
    {

        $json = $this->request->getJSON();
        $id = $json->id;

        $borrar = model('componentesAtributosProductoModel')->where('id', $id)->delete();

        if ($borrar) {
            return $this->response->setJSON([
                'response' => 'success',
                'id' => $id
            ]);
        }
    }

    function deleteAtributo()
    {

        $json = $this->request->getJSON();
        $id = $json->id;

        $componentes = model('componentesAtributosProductoModel')->where('id_atributo', $id)->first();

        if (!empty($componentes)) {

            $borrarComponentes = model('componentesAtributosProductoModel')->where('id_atributo', $id)->delete();

            return $this->response->setJSON([
                'response' => 'success',
                'id' => $id
            ]);
        } else  if (empty($componentes)) {

            $borrar = model('atributosProductoModel')->where('id', $id)->delete();

            if ($borrar) {
                return $this->response->setJSON([
                    'response' => 'success',
                    'id' => $id
                ]);
            }
        }
    }

    function searchAtributo()
    {

        $json = $this->request->getJSON();
        $valor = $json->query;

        $atributos = model('atributosProductoModel')->atributos($valor);

        if (!empty($atributos)) {
            return $this->response->setJSON([
                'response' => 'success',
                'atributos' => view('producto/atributos', [
                    'atributos' => $atributos
                ])
            ]);
        } else if (empty($atributos)) {
            return $this->response->setJSON([
                'response' => 'fail',

            ]);
        }
    }

    function actualizarPrecioProducto()
    {

        $json = $this->request->getJSON();
        $valor = str_replace([",", "."], "", $json->valor);
        $id = $json->id;

        if (!empty($valor)) {
            $actualizar = model('productoModel')->set('valorventaproducto', $valor)->where('id', $id)->update();

            return $this->response->setJSON([
                'response' => 'success',

            ]);
        }
    }

    function actualizarImpresora()
    {

        $json = $this->request->getJSON();
        $idProducto = $json->idProducto;
        $idImpresora = $json->idImpresora;

        $actualizar = model('productoModel')->set('idImpresora', $idImpresora)->where('id', $idProducto)->update();

        if ($actualizar) {
            return $this->response->setJSON([
                'response' => 'success',

            ]);
        }
    }

    function productosAtributos()
    {
        $json = $this->request->getJSON();
        $idProducto = $json->idProducto;
        //$idProducto = 497;
        $idAtributo = $json->idAtributo;
        //$idAtributo = 1;

        //$existeAtributo = model('configuracionAtributosProductoModel')->where('id_atributo', $idAtributo)->first();
        $existeAtributo = model('configuracionAtributosProductoModel')->existeAtributosProducto($idProducto, $idAtributo);
        if (empty($existeAtributo)) {
            $data = [
                'id_producto' => $idProducto,
                'id_atributo' => $idAtributo,
                'numero_componentes' => 1
            ];

            $insert = model('configuracionAtributosProductoModel')->insert($data);

            if ($insert) {

                $atributos = model('configuracionAtributosProductoModel')->atributosProducto($idProducto);


                return $this->response->setJSON([
                    'response' => 'success',
                    'atributos' => view('producto_atributos/adicionComponentes', [
                        'atributos' => $atributos,
                        'idProducto' => $idProducto
                    ])
                ]);
            }
        } else if (!empty($existeAtributo)) {
            return $this->response->setJSON([
                'response' => 'exists',

            ]);
        }
    }

    function atributosProducto()
    {

        $json = $this->request->getJSON();
        $idProducto = $json->idProducto;

        //$idProducto = 497; 

        $exiteProducto = model('configuracionAtributosProductoModel')->where('id_producto', $idProducto)->first();



        if (!empty($exiteProducto)) {  // El producto tiene atributos asociados 

            $idAtributos = model('configuracionAtributosProductoModel')->getAtributos($idProducto);

            return $this->response->setJSON([
                'response' => 'true',
                'atributos' => view('producto_atributos/componentesProducto', [
                    'idAtributos' => $idAtributos,
                    'idProducto' => $idProducto
                ])
            ]);
        }
        if (empty($exiteProducto)) {  // El producto tiene atributos asociados 

            return $this->response->setJSON([
                'response' => 'false'
            ]);
        }
    }

    function updateNumeroComponentes()
    {
        $json = $this->request->getJSON();
        $valor = $json->valor;
        $idProductoAtributo = $json->idProductoAtributo;

        $update = model('configuracionAtributosProductoModel')->set('numero_componentes', $valor)->where('id', $idProductoAtributo)->update();

        if ($update) {
            return $this->response->setJSON([
                'response' => 'success'
            ]);
        }
    }

    function eliminarComponente()
    {

        $json       = $this->request->getJSON();
        $id         = $json->idAtributo;
        $idProducto = $json->idProducto;

        $borrar = model('configuracionAtributosProductoModel')->where('id', $id)->delete();


        /*  if ($borrar) { } */
        $atributos = model('configuracionAtributosProductoModel')->atributosProducto($idProducto);
        return $this->response->setJSON([
            'response' => 'success',
            'atributos' => view('producto_atributos/deleteAtributo', [
                'atributos' => $atributos,
                'idProducto' => $idProducto
            ])
        ]);
    }

    function getAtributos()
    {

        $json = $this->request->getJSON();
        $codigointernoproducto = $json->id_producto;
        //$codigointernoproducto = 183;
        $id_tabla_producto = $json->id_tabla_producto;
        //$id_tabla_producto =10931;


        //$idProducto = model('productoModel')->select('id')->where('codigointernoproducto', $codigointernoproducto)->first();

        $idProducto = model('productoModel')
            ->select('id,nombreproducto')
            ->where('codigointernoproducto', (string) $codigointernoproducto)
            ->first();




        $atributos = model('configuracionAtributosProductoModel')->atributosProducto($idProducto['id']);



        return $this->response->setJSON([
            'response' => 'success',
            'atributos' => view('atributos/atributosProducto', [
                'atributos' => $atributos,
                'idProducto' => $idProducto['id'],
                'id_tabla_producto' => $id_tabla_producto
            ]),
            'nombreProducto' => $idProducto['nombreproducto'],
            'id_tabla_producto' => $id_tabla_producto
        ]);
    }

    function getAtributosProducto()
    {

        $json = $this->request->getJSON();
        $id_componente = $json->id_componente;
        $id_producto = $json->id_producto;
        $id_atributo = $json->id_atributo;
        $id_tabla_producto = $json->id_tabla_producto;

        /*     $id_componente = 24;
        $id_producto = 485;
        $id_atributo = 1;
        $id_tabla_producto = 10935;  */

        $numeroComponentes = model('atributosDeProductoModel')->getNumeroComponentes($id_atributo, $id_producto);
        $contarComponentes = model('atributosDeProductoModel')->countNumeroComponentes($id_producto, $id_tabla_producto, $id_atributo);

        /*     echo $numeroComponentes[0]['numero_componentes'] . "<br>";
        echo $contarComponentes[0]['numero_componentes'] . "<br>";
        exit(); */


        if ($contarComponentes[0]['numero_componentes'] < $numeroComponentes[0]['numero_componentes']) {
            $data = [
                'id_componente' => $id_componente,
                'id_tabla_producto' => $id_tabla_producto,
                'id_atributo' => $id_atributo,
                'id_producto' => $id_producto,
            ];

            $insert = model('atributosDeProductoModel')->insert($data);

            if ($insert) {
                $atributos = model('atributosDeProductoModel')
                    ->where('id_tabla_producto', $id_tabla_producto)
                    ->where('id_atributo', $id_atributo)
                    ->findAll();



                return $this->response->setJSON([
                    'response' => 'success',
                    'id_atributo' => $id_atributo,
                    'componetes' => view('producto_atributos/componentesDeProducto', [
                        'atributos' => $atributos,
                        'idProducto' => $id_producto,
                        'id_tabla_producto' => $id_tabla_producto
                    ]),
                ]);
            }
        }
        if ($contarComponentes[0]['numero_componentes'] >= $numeroComponentes[0]['numero_componentes']) {

            return $this->response->setJSON([
                'response' => 'error',
                'mensaje' => 'El número de componentes seleccionados supera el límite permitido'
            ]);
        }
        // $atributos = model('atributosDeProductoModel')->where('id_tabla_producto', $id_tabla_producto)->findAll();
    }

    function deleteComponenteProducto()
    {

        $json = $this->request->getJSON();
        $id_componente = $json->id;

        $borrar = model('atributosDeProductoModel')->where('id', $id_componente)->delete();
        if ($borrar) {
            return $this->response->setJSON([
                'response' => 'success',
                'id' => $id_componente
            ]);
        }
        if (!$borrar) {
            return $this->response->setJSON([
                'response' => 'error',
                'mensaje' => 'Error al eliminar el componente'
            ]);
        }
    }

    function armarNota()
    {
        $json = $this->request->getJSON();
        $id_tabla_producto = $json->id_tabla_producto;

        $atributos = model('atributosDeProductoModel')->getAtributos($id_tabla_producto);

        return $this->response->setJSON([
            'response' => 'success',
            'nota' => view('atributos/nota', [
                'atributos' => $atributos,
                'id_tabla_producto' => $id_tabla_producto
            ]),
            'id_tabla_producto' => $id_tabla_producto
        ]);
    }
}
