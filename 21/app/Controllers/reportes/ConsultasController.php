<?php

namespace App\Controllers\reportes;

use App\Controllers\BaseController;
use App\Libraries\Inventario;

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



        $ventas = model('pagosModel')->ventasPorMesero($fecha_inicial, $fecha_final, $id_mesero);
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


        $ventas = model('pagosModel')->ventasPorApertura($id_apertura, $id_mesero);
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

    public function notas_credito()
    {


        $notasCredito = model('notaCreditoModel')->datosNc();
        $dianAceptado = model('notaCreditoModel')
            ->where('id_status', 2)
            ->countAllResults();

        $dianNoAceptado = model('notaCreditoModel')
            ->where('id_status', 1)
            ->countAllResults();

        $dianRechazado = model('notaCreditoModel')
            ->where('id_status', 3)
            ->countAllResults();

        $dianError = model('notaCreditoModel')
            ->where('id_status', 4)
            ->countAllResults();

        $total = model('notaCreditoModel')
            ->selectSum('total')
            ->first()['total'] ?? 0;

        //d($notasCredito);
        return view('ventas/notas_credito', [
            'notas_credito' => $notasCredito,
            'dian_aceptado' => $dianAceptado,
            'dian_no_aceptado' => $dianNoAceptado,
            'dian_rechazado' => $dianRechazado ?? 0,
            'dian_error' => $dianError ?? 0,
            'total' => $total
        ]);
    }

    public function eliminarNotaCredito()
    {
        $db = \Config\Database::connect();

        $id = $this->request->getPost('id');

        $db->transStart();

        //Eliminar detalle
        $db->table('nota_credito_electronica')
            ->where('id', $id)
            ->delete();

        //Eliminar encabezado
        $db->table('item_nota_credito_electronica')
            ->where('id_nota', $id)
            ->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {

            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Ocurrió un error al eliminar la nota de crédito.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Nota de crédito eliminada correctamente.'
        ]);
    }

    function buscarNcNumero()
    {

        $request = $this->request->getJSON();

        $buscar = $request->buscar ?? '';
        //$buscar = 'aa';


        $nc = model('notaCreditoModel')->getNc($buscar);

        $dianEstados = model('notaCreditoModel')->getEstadoNc($buscar);
        //d($dianEstados);




        return $this->response->setJSON([
            'success' => true,
            'nc' => view('ventas/notasCredito', [
                'notas_credito' => $nc,
            ]),
            'dian_aceptado' => $dianEstados[0]['aceptadas'],
            'dian_no_enviado' => $dianEstados[0]['pendientes'],
            'dian_rechazadas' => $dianEstados[0]['rechazadas'],
        ]);
    }

    function buscarNcCliente()
    {

        $request = $this->request->getJSON();

        $buscar = $request->buscar ?? '';
        //$buscar = 'FINAL';

        $nc = model('notaCreditoModel')->getNcCliente($buscar);

        $dianEstados = model('notaCreditoModel')->getEstadoNcCliente($buscar);

        //dd($dianEstados);

        return $this->response->setJSON([
            'success' => true,
            'nc' => view('ventas/notasCredito', [
                'notas_credito' => $nc,
            ]),
            'dian_aceptado' => $dianEstados['aceptadas'],
            'dian_no_enviado' => $dianEstados['pendientes'],
            'dian_rechazadas' => $dianEstados['rechazadas'],
        ]);
    }
    function buscarNcFecha()
    {

        $request = $this->request->getJSON();

        $fecha_inicial = $request->fecha_inicial ?? '';
        $fecha_final = $request->fecha_final ?? '';
        //$buscar = 'FINAL';

        //$fecha_inicial=date('Y-m-d');
        //$fecha_final=date('Y-m-d');



        $nc = model('notaCreditoModel')->getNcFechas($fecha_inicial, $fecha_final);

        // dd($inc);

        $dianEstados = model('notaCreditoModel')->getEstadoNcFecha($fecha_inicial, $fecha_final);

        //dd($dianEstados);

        return $this->response->setJSON([
            'success' => true,
            'nc' => view('ventas/notasCredito', [
                'notas_credito' => $nc,
            ]),
            'dian_aceptado' => $dianEstados['aceptadas'],
            'dian_no_enviado' => $dianEstados['pendientes'],
            'dian_rechazadas' => $dianEstados['rechazadas'],
        ]);
    }

    function allNc()
    {
        $notasCredito = model('notaCreditoModel')->datosNc();
        $dianAceptado = model('notaCreditoModel')
            ->where('id_status', 2)
            ->countAllResults();

        $dianNoAceptado = model('notaCreditoModel')
            ->where('id_status', 1)
            ->countAllResults();

        $dianRechazado = model('notaCreditoModel')
            ->where('id_status', 3)
            ->countAllResults();

        $dianError = model('notaCreditoModel')
            ->where('id_status', 4)
            ->countAllResults();

        $total = model('notaCreditoModel')
            ->selectSum('total')
            ->first()['total'] ?? 0;

        return $this->response->setJSON([
            'success' => true,
            'nc' => view('ventas/notasCredito', [
                'notas_credito' => $notasCredito,
            ]),
            'dian_aceptado' => $dianAceptado,
            'dian_no_aceptado' => $dianNoAceptado,
            'dian_rechazado' => $dianRechazado ?? 0,
            'dian_error' => $dianError ?? 0,
            'total' => $total
        ]);
    }

    public function nCEstado()
    {

        $request = $this->request->getJSON();

        $id_status = $request->estado;

        /*  $dianEstado = model('notaCreditoModel')
            ->where('id_status', $id_status)
            ->countAllResults();

        $total = model('notaCreditoModel')
            ->selectSum('total')
            ->where('id_status', $id_status)
            ->first()['total'] ?? 0; */

        $model = model('notaCreditoModel');

        $resultado = $model->select('COUNT(*) AS cantidad, COALESCE(SUM(total), 0) AS total')
            ->where('id_status', $id_status)
            ->first();

        $dianEstado = $resultado['cantidad'];
        $total = $resultado['total'];

        $nc = model('notaCreditoModel')->nCEstado($id_status);

        return $this->response->setJSON([
            'success' => true,
            'nc' => view('ventas/notasCredito', [
                'notas_credito' => $nc,
            ]),
            'resultados' => $dianEstado,
            'id_status' => $id_status,
            'total' => "Total $" . number_format($total, 0, ',', '.')
        ]);
    }
    /*  public function devolucionNc()
    {
        $request = $this->request->getJSON();

        //$id_nota = $request->id_nota_credito ?? null;

        $id_nota=47;



        if (empty($id_nota)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se recibió el ID de la nota crédito.'
            ]);
        }

        $hayApertura = model('aperturaRegistroModel')
            ->select('numero')
            ->first();

        if ($hayApertura) {



            return $this->response->setJSON([
                'success' => true,
                'message' => 'Existe una apertura de caja.',
                'id_nota' => $id_nota
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'No existe una apertura de caja activa.',
            'id_nota' => $id_nota
        ]);
    } */


    public function devolucionNc()
    {

        $request = $this->request->getJSON();

        $id_nota = $request->id_nota_credito ?? null;

        // $id_nota = 48;

        /*  $usuario = $_POST['usuario'];
        //$nit_cliente = $_POST['nit_cliente'];
        $nit_cliente = 222222222222;
        $codigo_producto_devolucion = $_POST['codigo_producto_devolucion'];
        $cantidad_devolucion = $_POST['cantidad_devolucion'];
        $precio_devo = $_POST['precio_devolucion'];
        $precio_devolucion =  str_replace('.', '', $precio_devo); */




        $id_apertura = model('aperturaRegistroModel')->select('numero')->first();

        if (!empty($id_apertura)) {

            $usuario = 6;
            // $nit_cliente = 22222222;
            // $codigo_producto_devolucion = '5660';
            // $cantidad_devolucion = 1;
            // $precio_devo = 4.100;
            // $precio_devolucion =  str_replace('.', '', $precio_devo); 

            $productos = model('itemNotaCreditoModel')->productos($id_nota);


            $inventario = new Inventario();
            foreach ($productos as $producto) {
                $actualizar_inventario = $inventario->devolucion(
                    $usuario,
                    $producto['nit_cliente'],
                    $producto['codigo'],
                    $producto['cantidad'],
                    $producto['neto'],
                    $producto['neto'],
                    $id_apertura
                );
            }

              return $this->response->setJSON([
                'success' => true,
                'message' => 'Devolucion de productos realizada.',
            
            ]);
        }

        if (empty($id_apertura)) {
            $returnData = array(
                "resultado" => 2,
            );
            echo  json_encode($returnData);
        }
    }
}
