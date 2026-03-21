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
        $requiredFields = ['numero', 'tipo', 'capacidad', 'precio', 'estado'];
        foreach ($requiredFields as $field) {
            if (empty($input[$field])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "El campo {$field} es obligatorio"
                ]);
            }
        }

        try {
            // Crear mesa
            $idNuevaMesa = model('MesasModel')->insert([
                'fk_salon'     => 2,
                'nombre'       => $input['numero'],
                'estado'       => 0,
                'valor_pedido' => 0,
                'estado_mesa'  => $input['estado']
            ]);

            // Crear habitación asociada
            $habitacionesModel = model('HabitacionesModel');
            $habitacionesModel->insert([
                'id_mesa'   => $idNuevaMesa,
                'tipo'      => $input['tipo'],
                'capacidad' => (int)$input['capacidad'],
                'precio'    => $input['precio']
            ]);

            // Obtener todas las habitaciones para actualizar la tabla
            $habitaciones = $habitacionesModel->getHabitacionesInsert();

            // Retornar JSON con éxito y datos
            return $this->response->setJSON([
                'success'      => true,
                'message'      => 'Habitación creada correctamente',
                'habitaciones' => $habitaciones
                /* 'habitaciones' => view('reportes/ventas',[
                    'habitaciones' => $habitaciones
                ])  */ // <-- Array de habitaciones listo para JS
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
    
    }
}
