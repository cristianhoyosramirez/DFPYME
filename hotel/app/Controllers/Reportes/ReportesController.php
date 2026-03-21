<?php

namespace App\Controllers\Reportes;

use App\Controllers\BaseController;

class ReportesController extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function ventas()
    {
        $facturas = model('historicoModel')->findAll();

        return $this->render('reportes/ventas', [
            'facturas' => $facturas
        ]);
    }
    public function vehiculos()
    {
        $facturas = model('historicoModel')->findAll();
        //dd($facturas);
        return view('reportes/vehiculos', [
            'facturas' => $facturas
        ]);
    }
    public function registro()
    {
        $facturas = model('historicoModel')->findAll();
        //dd($facturas);
        return view('reportes/registro', [
            'facturas' => $facturas
        ]);
    }
}
