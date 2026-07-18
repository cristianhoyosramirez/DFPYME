<?php

namespace App\Controllers\Cartera;

use App\Controllers\BaseController;

class carteraContoller extends BaseController
{
    public function index()
    {
        $carteraVigente = model('pagosModel')->getCartera();
        $totalCartera = model('pagosModel')->getTotalCartera();
        return view('cartera/cartera', [
            'cartera' => $carteraVigente,
            'total' => $totalCartera[0]['total_documentos'],
            'cantidad_facturas' => $totalCartera[0]['cantidad_facturas']
        ]);
    }

    public function buscarDocumento()
    {

        //$documento = $this->request->getPost('documento');

        //$carteraVigente = model('pagosModel')->getCartera();
        //$totalCartera = model('pagosModel')->getTotalCartera();

        $json = $this->request->getJSON(true); // true = arreglo asociativo

        $documento = $json['documento'];  
        //$documento = '89049';

        $cartera = model('CarteraModel')->getDatosCartera($documento);

        //dd($cartera);
        $total_cartera = model('CarteraModel')->getSumaCartera($documento);
        $cantidad_cartera = model('CarteraModel')->getCantidadCartera($documento);
        $total_pagado = model('CarteraModel')->totalPagado($documento);



        if (empty($cartera)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se encontraron registros.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => view('cartera/datosCartera', [
                'cartera' => $cartera
            ]),
            'total' => number_format($total_cartera[0]['total'], 0, ',', '.'),
            'cantidad' => $cantidad_cartera['cantidad'],
            'valor_pagado' => number_format($total_pagado['total'], 0, ',', '.')
        ]);
    }

    public function buscarCliente()
    {

        //$documento = $this->request->getPost('documento');

        //$carteraVigente = model('pagosModel')->getCartera();
        //$totalCartera = model('pagosModel')->getTotalCartera();

        $json = $this->request->getJSON(true); // true = arreglo asociativo

        $cliente = $json['cliente'];
        //$cliente = 'juan';


        $cartera = model('CarteraModel')->getDatosCarteraCliente($cliente);
        $total_cartera = model('CarteraModel')->getSumaCarteraCliente($cliente);

        $cantidad_cartera = model('CarteraModel')->getCantidadCarteraCliente($cliente);
        $total_pagado = model('CarteraModel')->totalPagadoCliente($cliente);



        if (empty($cartera)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se encontraron registros.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => view('cartera/datosCartera', [
                'cartera' => $cartera
            ]),
            'total' => number_format($total_cartera[0]['total'], 0, ',', '.'),
            'cantidad' => $cantidad_cartera['cantidad'],
            'valor_pagado' => number_format($total_pagado['total'], 0, ',', '.')
        ]);
    }
    public function getCartera()
    {



        $json = $this->request->getJSON(true); // true = arreglo asociativo

        $estado = $json['estado'];
        $fecha_inicial = $json['fecha_inicial'];
        $fecha_final = $json['fecha_final'];

        /*   $estado = 0;
        $fecha_inicial = "";
        $fecha_final = ""; */

        $cartera = model('CarteraModel')->getCartera($estado, $fecha_inicial, $fecha_final);


        //dd($cartera);

        //$cartera = model('CarteraModel')->getCarteraFechas($estado, $fecha_inicial, $fecha_final);
        $total_cartera = model('CarteraModel')->getSumaCarteraFechas($estado, $fecha_inicial, $fecha_final);

        $cantidad_cartera = model('CarteraModel')->getCantidadCarteraFechas($estado, $fecha_inicial, $fecha_final);
        $total_pagado = model('CarteraModel')->totalPagadoFechas($estado, $fecha_inicial, $fecha_final);



        if (empty($cartera)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se encontraron registros.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => view('cartera/datosCartera', [
                'cartera' => $cartera
            ]),
            'total' => number_format($total_cartera[0]['total'], 0, ',', '.'),
            'cantidad' => $cantidad_cartera['cantidad'],
            'valor_pagado' => number_format($total_pagado['total'], 0, ',', '.')
        ]);
    }
}
