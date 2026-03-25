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

        $id_cliente = $this->request->getPost('id_cliente');
        $id_habitacion = $this->request->getPost('id_de_habitacion');
        $fecha = $this->request->getPost('fecha');
        $observaciones = $this->request->getPost('observaciones');

        $insert = model('reservasModel')->insert([
            'id_habitacion' => $id_habitacion,
            'id_cliente' => $id_cliente,
            //'id_estado' => 2,
            'notas' => $observaciones,
            'fecha_reserva' => $fecha
        ]);

        if ($insert) {
            $id_mesa = model('habitacionesModel')->select('id_mesa')->where('id', $id_habitacion)->first();
            //model('mesasModel')->update('id_estado', 2)->where('id', $id_mesa['id_mesa'])->update();

            model('mesasModel')->update($id_mesa['id_mesa'], ['id_estado' => 2]);
            $habitaciones = model('habitacionesModel')->getHabitaciones();
            return $this->response->setJSON([
                'success' => true,
                'habitaciones' => view('habitaciones/habitaciones', [
                    'habitaciones' => $habitaciones
                ])
            ]);
        }
    }

    function consultarReserva()
    {
        $data = $this->request->getJSON(true);


        $id_habitacion = $data['id_habitacion'];
        $datos_reserva = model('reservasModel')->getReservas($id_habitacion);

        return $this->response->setJSON([
            'response' => 'ok',
            'fecha' => $datos_reserva[0]['fecha_reserva'],
            'habitacion' => $datos_reserva[0]['habitacion'],
            'numero_documento' => $datos_reserva[0]['nitcliente'],
            'documento' => $datos_reserva[0]['documento_descripcion'],
            'nombres' => $datos_reserva[0]['nombrescliente'],
            'notas' => $datos_reserva[0]['notas'],
            'precio' => number_format($datos_reserva[0]['precio'], 0, ',', '.'),
            'id_reserva' => $datos_reserva[0]['id_reserva']
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


        $reservaModel = model('registroHoteleroModel'); // Asegúrate de tener este modelo


        try {
            $reservaId = $reservaModel->insert([
                'id_municipio_origen'       =>  $id_procedencia ?? null,
                'id_municipio_destino'      => $id_destino,
                'id_reserva'                => $id_reserva,
                'id_vehiculo'               => $id_placa
            ]);

            $id_habitacion = model('reservasModel')->select('id_habitacion')->where('id', $id_reserva)->first();
            $id_mesa = model('habitacionesModel')->select('id_mesa')
                ->where('id', $id_habitacion['id_habitacion'])
                ->first();

            $actualizarMesa = model('mesasModel')->set('id_estado', 3)->where('id', $id_mesa['id_mesa'])->update();
            $habitaciones = model('habitacionesModel')->getHabitaciones();


            $data = [
                'fk_mesa' => $id_mesa['id_mesa'],
                'fk_usuario' => 6,
                'valor_total' => $valor_hospedaje,
                'cantidad_de_productos' => 1,
                'tiene_pedido' => true

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
                $nota = ""
            );


            return $this->response->setJSON([
                'success' => true,
                'habitaciones' => view('habitaciones/habitaciones', [
                    'habitaciones' => $habitaciones
                ])
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al guardar la reserva: ' . $e->getMessage()
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
