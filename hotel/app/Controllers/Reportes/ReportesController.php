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
        $habitaciones = model('habitacionesModel')->getHabitaciones();



        return $this->render('reportes/ventas', [
            'habitaciones' => $habitaciones
        ]);
    }
    public function vehiculos()
    {
        $vehiculos = model('vehiculosModel')->findAll();
        //dd($facturas);
        return view('reportes/vehiculos', [
            'vehiculos' => $vehiculos
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
