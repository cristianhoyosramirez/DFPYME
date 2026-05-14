<?php

namespace App\Controllers\habitaciones;

use App\Controllers\BaseController;

class HabitacionesController extends BaseController
{
    public function index()
    {
        return view('home');
    }
    public function crear()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405)
                ->setJSON(['success' => false, 'message' => 'Método no permitido']);
        }

        $input = $this->request->getJSON(true);

        // Validación básica
        $requiredFields = ['numero', 'tipo', 'precio', 'estado'];
        foreach ($requiredFields as $field) {
            if (empty($input[$field])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "El campo {$field} es obligatorio"
                ]);
            }
        }

        $nombre_habitacion = $input['numero'];
        $estado = $input['estado'];
        $tipo = $input['tipo'];
        $precio = $input['precio'];
        // 1. Quitar separador de miles (puntos)
        $precio = str_replace('.', '', $precio);
        // 2. Reemplazar coma decimal por punto
        $precio = str_replace(',', '.', $precio);

        try {
            // Crear mesa
            $idNuevaMesa = model('MesasModel')->insert([
                'fk_salon'     => 2,
                'nombre'       => $nombre_habitacion,
                'estado'       => 0,
                'valor_pedido' => 0,
                'id_estado'  => $estado
            ]);

            // Crear habitación asociada
            $habitacionesModel = model('HabitacionesModel');
            $habitacionesModel->insert([
                'id_mesa'   => $idNuevaMesa,
                'tipo'      => $tipo,
                'capacidad' => 1,
                'precio'    => $precio
            ]);

            // Obtener todas las habitaciones para actualizar la tabla
            $habitaciones = $habitacionesModel->getHabitacionesInsert();

            // Retornar JSON con éxito y datos
            return $this->response->setJSON([
                'success'      => true,
                'message'      => 'Habitación creada correctamente',
                //'habitaciones' => $habitaciones
                'habitaciones' => view('habitaciones/habitaciones', [
                    'habitaciones' => $habitaciones
                ])   // <-- Array de habitaciones listo para JS
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al crear la habitación: ' . $e->getMessage()
            ]);
        }
    }

    public function crearVehiculo()
    {
        $input = $this->request->getJSON(true);

        $tipo  = $input['tipo'] ?? null;
        $placa = $input['placa'] ?? null;

        // Validación básica
        if (!$tipo || !$placa) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Todos los campos son obligatorios'
            ]);
        }

        try {
            $vehiculosModel = model('VehiculosModel');

            $id = $vehiculosModel->insert([
                'tipo'  => $tipo,
                'placa' => $placa
            ]);

            // Obtener lista actualizada
            $vehiculos = $vehiculosModel->orderBy('id', 'desc')->findAll();

            return $this->response->setJSON([
                'success'   => true,
                'message'   => 'Vehículo creado correctamente',
                'vehiculos' => view('reportes/vehiculosRegistro', [
                    'vehiculos' => $vehiculos
                ])
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function buscarCliente()
    {
        $request = service('request');
        $data = $request->getJSON(true);
        $valor = trim($data['valor'] ?? '');

        // 🚫 Si está vacío, no hacer nada
        if ($valor === '') {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        $clientes = model('habitacionesModel')->clientes($valor);

        // 🚫 Si no hay resultados
        if (empty($clientes)) {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        // ✅ Si hay resultados
        return $this->response->setJSON([
            'success' => true,
            'clientes' => view('habitaciones/clientes', [
                'clientes' => $clientes
            ])
        ]);
    }

    function reservar()
    {

        $data = $this->request->getJSON();
        $id_habitacion = $data->id_habitacion;
        $fecha = $data->fecha;
        $observaciones = $data->observaciones;


        $insert = model('reservasModel')->insert([
            'id_habitacion' => $id_habitacion,
            //'id_estado' => 2,
            'notas' => $observaciones,
            'fecha_reserva' => $fecha,
            'id_estado_reservas' => 1
        ]);

        if ($insert) {
            $id_mesa = model('habitacionesModel')->select('id_mesa')->where('id', $id_habitacion)->first();
            //model('mesasModel')->update('id_estado', 2)->where('id', $id_mesa['id_mesa'])->update();

            //model('mesasModel')->update($id_mesa['id_mesa'], ['id_estado' => 2]);
            model('mesasModel')->set('id_estado', 2)->where('id', $id_mesa['id_mesa'])->update();
            $habitaciones = model('habitacionesModel')->getHabitaciones();
            return $this->response->setJSON([
                'success' => true,
                'habitaciones' => view('habitaciones/habitaciones', [
                    'habitaciones' => $habitaciones
                ])
            ]);
        }
    }

    function nuevaReserva()
    {

        $data = $this->request->getJSON();
        $id_habitacion = $data->id_habitacion;
        //$id_habitacion = 1;
        $fecha = $data->fecha;
        //$fecha = '2026-05-13';
        $observaciones = $data->observaciones;
        //$observaciones = "";
        $vehiculo = $data->vehiculo;
        //$vehiculo = "Tracto mula";

    

        $numero_apertura = model('aperturaRegistroModel')->select('numero')->first();

        if (!empty($numero_apertura)) {

            $insert = model('reservasModel')->insert([
                'id_habitacion' => $id_habitacion,
                //'id_estado' => 2,
                'notas' => $observaciones,
                'fecha_reserva' => $fecha,
                'id_estado_reservas' => 1,
                'vehiculo' => $vehiculo,
                'id_apertura' => $numero_apertura['numero']

            ]);

            if ($insert) {
                $id_mesa = model('habitacionesModel')->select('id_mesa')->where('id', $id_habitacion)->first();
                //model('mesasModel')->update('id_estado', 2)->where('id', $id_mesa['id_mesa'])->update();

                //model('mesasModel')->update($id_mesa['id_mesa'], ['id_estado' => 2]);
                model('mesasModel')->set('id_estado', 2)->where('id', $id_mesa['id_mesa'])->update();
                // $habitaciones = model('habitacionesModel')->getHabitaciones();
                $reservas = model('reservasModel')->getResrvasHabitaicones(date('Y-m-d'),date('Y-m-d'));
                return $this->response->setJSON([
                    'success' => true,
                    'reservas' => view('reservas/tablaReservas', [
                        'reservas' => $reservas
                    ])
                ]);
            }
        } else if (empty($numero_apertura)) {

            return $this->response->setJSON([
                'success' => false,

            ]);
        }
    }

    function consultarReserva()
    {
        $data = $this->request->getJSON(true);


        $id_reserva = $data['id_reserva'];
        //$id_habitacion = 2;
        $datos_reserva = model('reservasModel')->getReservas($id_reserva);

        return $this->response->setJSON([
            'response' => 'ok',
            'fecha' => $datos_reserva[0]['fecha_reserva'],
            'habitacion' => $datos_reserva[0]['nombre_habitacion'],
            //'numero_documento' => $datos_reserva[0]['numero_identificacion'],
            // 'documento' => $datos_reserva[0]['identificacion'],
            //'nombres' => $datos_reserva[0]['nombre_completo'],
            'notas' => $datos_reserva[0]['notas'],
            'precio' => number_format($datos_reserva[0]['valor_hospedaje'], 0, ',', '.'),
            'id_reserva' => $id_reserva
        ]);
    }

    function datosReserva()
    {
        $data = $this->request->getJSON(true);


        $id_reserva = $data['id_reserva'];

        //$id_habitacion = 2;
        $datos_reserva = model('reservasModel')->datosReservas($id_reserva);


        return $this->response->setJSON([
            'response' => 'ok',
            'fecha' => $datos_reserva[0]['fecha_reserva'],
            'habitacion' => $datos_reserva[0]['nombre_habitacion'],
            'numero_documento' => $datos_reserva[0]['numero_identificacion'],
            'documento' => $datos_reserva[0]['identificacion'],
            'nombres' => $datos_reserva[0]['nombre_completo'],
            'notas' => $datos_reserva[0]['notas'],
            'precio' => number_format($datos_reserva[0]['valor_hospedaje'], 0, ',', '.'),
            'id_reserva' => $id_reserva,
            'vehiculo' => $datos_reserva[0]['tipo'] . "/" . $datos_reserva[0]['placa'],
            'origen' => $datos_reserva[0]['origen'],
            'destino' => $datos_reserva[0]['destino'],
            'telefono' => $datos_reserva[0]['telefono'],
            'hora_salida' => date('h:i A', strtotime($datos_reserva[0]['hora_salida'])),

            'numero_reserva' => "Reserva N° " . $id_reserva
        ]);
    }

    public function checkIn()
    {
        $json = $this->request->getBody();
        // Convertir JSON a array asociativo
        $datos = json_decode($json, true);

        // Ejemplo: acceder a los campos
        $valor_hospedaje = $datos['valor_hospedaje'] ?? null;

        // Quitar puntos y comas

        $valor_hospedaje = str_replace(['.', ','], '', $valor_hospedaje);

        $id_placa = $datos['id_placa'] ?? null;
        $id_procedencia = $datos['id_procedencia'] ?? null;
        $id_destino = $datos['id_destino'] ?? null;
        $notas = $datos['notas'] ?? null;
        $id_reserva = $datos['id_reserva'] ?? null;
        $id_cliente = $datos['id_cliente'] ?? null;
        $hora_salida = $datos['hora_salida'] ?? null;
        $telefono = $datos['telefono'] ?? null;

        $id_mesa = model('pedidoModel')->mesaId($id_reserva);

        $tienePedido = model('pedidoModel')->select('id')->where('fk_mesa', $id_mesa[0]['id_mesa'])->first();

        $actualizarReserva = model('reservasModel')->set('id_cliente', $id_cliente)->where('id', $id_reserva)->update();
        $actualizarNotasReserva = model('reservasModel')->set('notas', $notas)->where('id', $id_reserva)->update();
        $valroReserva = model('reservasModel')->set('notas', $notas)->where('id', $id_reserva)->update();



        if (empty($tienePedido)) {


            $reservaModel = model('registroHoteleroModel'); // Asegúrate de tener este modelo

            $actualizarCliente = model('habitacionesModel')->updateCliente($telefono, $id_cliente);


            try {
                $reservaId = $reservaModel->insert([
                    'id_municipio_origen'       =>  $id_procedencia ?? null,
                    'id_municipio_destino'      => $id_destino,
                    'hora_salida'               => $hora_salida,
                    'id_reserva'                => $id_reserva,
                    'id_vehiculo'               => $id_placa
                ]);




                $id_habitacion = model('reservasModel')->select('id_habitacion')->where('id', $id_reserva)->first();
                $id_mesa = model('habitacionesModel')->select('id_mesa')
                    ->where('id', $id_habitacion['id_habitacion'])
                    ->first();

                $actualizarMesa = model('mesasModel')->set('id_estado', 3)->where('id', $id_mesa['id_mesa'])->update();
                //$habitaciones = model('habitacionesModel')->getHabitaciones();

                $actualizarReserva = model('reservasModel')
                    ->where('id', $id_reserva)
                    ->set(['id_estado_reservas' => 6])
                    ->update();


                $data = [
                    'fk_mesa' => $id_mesa['id_mesa'],
                    'fk_usuario' => 6,
                    'valor_total' => $valor_hospedaje,
                    'cantidad_de_productos' => 1,
                    'tiene_pedido' => true,
                    'id_reserva' => $id_reserva

                ];
                $insertPedido = model('pedidoModel')->insert($data);

                $insertar = model('pedidoModel')->insertar(
                    $insertPedido,
                    $valor_hospedaje,
                    false,
                    8,
                    512,
                    1,
                    6,
                    date('Y-m-d'),
                    date('H:i:s'),
                    $nota = "",
                    $id_reserva
                );

                $reservas = model('reservasModel')->getResrvasHabitaicones(date('Y-m-d'),date('Y-m-d'));
                return $this->response->setJSON([
                    'success' => true,
                    'id_reserva' => $id_reserva,
                    'reservas' => view('reservas/tablaReservas', [
                        'reservas' => $reservas
                    ])
                ]);
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al guardar la reserva: ' . $e->getMessage()
                ]);
            }
        } else if (!empty($tienePedido)) {

            return $this->response->setJSON([
                'success' => false,
                'message' => 'La habitacion tiene una reserva confirnmada y esta pendiente por facturar '
            ]);
        }
    }

    function buscarCiudadProcedencia()
    {
        $request = service('request');
        $data = $request->getJSON(true);
        $valor = trim($data['valor'] ?? '');

        // 🚫 Si está vacío, no hacer nada
        if ($valor === '') {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        $ciudades = model('habitacionesModel')->getMunicipios($valor);



        // 🚫 Si no hay resultados
        if (empty($ciudades)) {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        // ✅ Si hay resultados
        return $this->response->setJSON([
            'success' => true,
            'ciudades' => view('habitaciones/ciudades', [
                'ciudades' => $ciudades
            ])
        ]);
    }

    function buscarCiudadDestino()
    {
        $request = service('request');
        $data = $request->getJSON(true);
        $valor = trim($data['valor'] ?? '');

        // 🚫 Si está vacío, no hacer nada
        if ($valor === '') {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        $ciudades = model('habitacionesModel')->getMunicipios($valor);
        // 🚫 Si no hay resultados
        if (empty($ciudades)) {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        // ✅ Si hay resultados
        return $this->response->setJSON([
            'success' => true,
            'ciudades' => view('habitaciones/ciudadesDestino', [
                'ciudades' => $ciudades
            ])
        ]);
    }

    function buscarPlaca()
    {
        $request = service('request');
        $data = $request->getJSON(true);
        $valor = trim($data['valor'] ?? '');

        // 🚫 Si está vacío, no hacer nada
        if ($valor === '') {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        $placas = model('habitacionesModel')->getPlacas($valor);
        // 🚫 Si no hay resultados
        if (empty($placas)) {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        // ✅ Si hay resultados
        return $this->response->setJSON([
            'success' => true,
            'placas' => view('habitaciones/placas', [
                'placas' => $placas
            ])
        ]);
    }
}
