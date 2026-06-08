<?php

namespace App\Controllers\reportes;

use App\Controllers\BaseController;

class ConsultasController extends BaseController
{
    public function index()
    {
        $meseros = model('usuariosModel')->select('idusuario_sistema,nombresusuario_sistema')->findAll();
        $aperturas = model('aperturaModel')->getAperturas();


        $fechaInicial = date('Y-m-d');
        $fechaFinal = date('Y-m-d');

        $ventas = model('pagosModel')->getUsuarioVenta($fechaInicial, $fechaFinal);

        return view('reportes/mesero', [
            'meseros' => $meseros,
            'ventas' => $ventas,
            'aperturas' => $aperturas
        ]);
    }
    public function ventasPorMesero()
    {
        $request = service('request');

        // Recibir datos del POST
        $id_mesero     = $request->getPost('id_mesero');
        $fecha_inicial = $request->getPost('fecha_inicial');
        $fecha_final   = $request->getPost('fecha_final');

        

         $ventas = model('pagosModel')->ventasPorMesero($fecha_inicial, $fecha_final,$id_mesero);
        //view('reportes/ventas_mesero');


        return $this->response->setJSON([
            'response' => 'success',
            'ventas' => view('reportes/ventas_mesero', [
                'ventas' => $ventas,

            ])
        ]);
    }
    public function ventasPorApertura()
    {
        $request = service('request');

        // Recibir datos del POST
        $id_mesero     = $request->getPost('id_mesero');
        $id_apertura = $request->getPost('id_apertura');
      

         $ventas = model('pagosModel')->ventasPorApertura($id_apertura,$id_mesero);
        //view('reportes/ventas_mesero');


        return $this->response->setJSON([
            'response' => 'success',
            'ventas' => view('reportes/ventas_mesero', [
                'ventas' => $ventas,

            ])
        ]);
    }

    function reporteVentasUsuario()
    {
        $json = $this->request->getJSON();

        $fechaInicial = $json->fechaInicial;
        $fechaFinal = $json->fechaFinal;
        $idMesero = $json->idMesero;

        //$usuarios = model('pagosModel')->getUsuarioVenta($fechaInicial, $fechaFinal);

        return $this->response->setJSON([
            'response' => 'success',
            'ventas' => view('reportes/ventasMesero', [
                'usuario' => $idMesero,
                'fechaInicial' => $fechaInicial,
                'fechaFinal' => $fechaFinal
            ])
        ]);
    }

    function ventas_hora()
    {

        return view('reportes/reporte_horas');
    }
    function ventas_fecha()
    {

        return view('reportes/reporte_entre_fechas');
    }


    function validarMesaPedido()
    {

        $json = $this->request->getJSON();
        $idMesa = $json->id_mesa;


        $tienePedido = model('pedidoModel')->where('fk_mesa', $idMesa)->first();

        //if ()
        return $this->response->setJSON([
            'response' => 'success',

        ]);
    }


    function consultasCategoria()
    {

        $json = $this->request->getJSON();
        $codigoCategoria = $json->categoria;

        $subCategorias = model('categoriasModel')->select('subcategoria')->where('codigocategoria', $codigoCategoria)->first();



        if ($subCategorias['subcategoria'] == 't') {



            $sub_categorias = model('subCategoriaModel')->select('id,nombre')->where('id_categoria', $codigoCategoria)->findAll();



            return $this->response->setJSON([
                'response' => 'success',
                'sub_categorias' => view('categoria/subCategorias', [
                    'sub_categorias' => $sub_categorias
                ])

            ]);
        }
        if ($subCategorias['subcategoria'] == 'f') {

            return $this->response->setJSON([
                'response' => 'false',


            ]);
        }
    }

    function eliminarRetiros()
    {

        $json = $this->request->getJSON();
        $id_retiro = $this->request->getPost('id_retiro');


        $deleteFormaRetiro = model('retiroFormaPagoModel')->where('idretiro', $id_retiro)->delete();

        if ($deleteFormaRetiro) {

            return $this->response->setJSON([
                'response' => 'true',
            ]);
        }
    }
}
