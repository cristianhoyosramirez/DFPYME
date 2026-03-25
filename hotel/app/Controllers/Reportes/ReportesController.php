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
        $estado_habitaciones = model('habitacionesModel')->estadoHabitaciones();

        return view('reportes/ventas', [
            'habitaciones' => $habitaciones,
            'estado_habitaciones'=>$estado_habitaciones
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
        $registro = model('habitacionesModel')->registroHotelero();
        //dd($facturas);
        return view('reportes/registro', [
            'registro' => $registro
        ]);
    }
}
