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
            'estado_habitaciones' => $estado_habitaciones
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

    public function buscarRegistroHotelero()
    {
        $data = $this->request->getJSON();

        $valor = $data->valor;

        $registro = model('habitacionesModel')
            ->buscarRegistroHotelero($valor);

        return $this->response->setJSON([

            'response' => 'ok',

            'registro' => view('registro/tabla_registro', [
                'registro' => $registro
            ])

        ]);
    }
    public function fechasRegistroHotelero()
    {
        $data = $this->request->getJSON();

        $fecha_inicial = $data->fecha_inicio;
        $fecha_final = $data->fecha_final;

        $registro = model('habitacionesModel')
            ->fechasRegistroHotelero($fecha_inicial, $fecha_final);

        return $this->response->setJSON([

            'response' => 'ok',

            'registro' => view('registro/tabla_registro', [
                'registro' => $registro
            ])

        ]);
    }

    public function actualizarVehiculo()
    {

        $data = $this->request->getJSON();

        $tipo = $data->tipo;
        $id_vehiculo = $data->id;


        $vehiculos = model('vehiculosModel')->set('tipo', $tipo)->where('id', $id_vehiculo)->update();


          return $this->response->setJSON([

            'response' => 'ok',

        ]);



    }
    public function actualizarPlaca()
    {

        $data = $this->request->getJSON();

        $placa = $data->placa;
        $id_vehiculo = $data->id;


        $vehiculos = model('vehiculosModel')->set('placa', $placa)->where('id', $id_vehiculo)->update();


          return $this->response->setJSON([

            'response' => 'ok',

        ]);



    }
}
