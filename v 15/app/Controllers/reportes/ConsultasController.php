<?php

namespace App\Controllers\reportes;

use App\Controllers\BaseController;

class ConsultasController extends BaseController
{
    public function index()
    {
        $meseros = model('usuariosModel')->select('idusuario_sistema,nombresusuario_sistema')->findAll();

        $fechaInicial = date('Y-m-d');
        $fechaFinal = date('Y-m-d');

        $usuarios = model('pagosModel')->getUsuarioVenta($fechaInicial, $fechaFinal);


        return view('reportes/mesero', [
            'meseros' => $meseros,
            'fechaInicial' => $fechaInicial,
            'fechaFinal' => $fechaFinal,
            'usuarios' => $usuarios
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

    function ventas_hora(){

        return view('reportes/reporte_horas');
        
    }


    function validarMesaPedido(){

        $json = $this->request->getJSON();
        $idMesa = $json->id_mesa;


        $tienePedido=model('pedidoModel')->where('fk_mesa',$idMesa)->first();

        //if ()
          return $this->response->setJSON([
            'response' => 'success',
          
        ]);

    }
}
