<?php

namespace App\Controllers\Reportes;

use App\Controllers\BaseController;

class ReservasController extends BaseController
{
    public function index()
    {
        $reservas = model('reservasModel')->getResrvasHabitaicones();
        //dd($reservas);
        return view('reservas/reservas', [
            'reservas' => $reservas
        ]);
    }
    public function busquedaPorHabitacion()
    {
        $request = service('request');
        $data = $request->getJSON();

        $valor = $data->busqueda ?? '';
        $reservas = model('reservasModel')->buscarHabitaicones($valor);
        //dd($reservas);
        return $this->response->setJSON([
            'success' => true,
            'reservas' => view('reservas/tablaReservas', [
                'reservas' => $reservas
            ])
        ]);
    }
    public function busquedaPorEstado()
    {
        $request = service('request');
        $data = $request->getJSON();

        $id_estado = $data->id_estado ?? '';
        $reservas = model('reservasModel')->buscarEstado($id_estado);
        //dd($reservas);
        return $this->response->setJSON([
            'success' => true,
            'reservas' => view('reservas/tablaReservas', [
                'reservas' => $reservas
            ])
        ]);
    }

    public function confirmar()
    {
        $data = $this->request->getJSON(true); // true = array

        $id_cliente     = $data['id_cliente']     ?? null;
        $id_reserva  = $data['id_reserva']  ?? null;
        $notas  = $data['notas']  ?? null;

        $data = [
            'id_cliente' => $id_cliente,
            'id_estado_reservas' => 2,
            'notas' => $notas
        ];

        $actualizar = model('reservasModel')->set($data)->where('id', $id_reserva)->update();

        $reservas = model('reservasModel')->getResrvasHabitaicones();

        return $this->response->setJSON([
            'success' => 'ok',
            'id_reserva' => $id_reserva,
            'reservas' => view('reservas/tablaReservas', [
                'reservas' => $reservas
            ])

        ]);
    }

    public function actualizarNota()
    {
        // 🔥 Recibir JSON desde fetch
        $data = $this->request->getJSON(true);

        $idReserva = $data['id_reserva'] ?? null;
        $nota      = $data['notas'] ?? '';

        // ⚠️ Validación básica
        if (!$idReserva) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID de reserva no válido'
            ]);
        }

        // 🧠 Modelo
        $reservasModel = model('reservasModel');

        try {

            $reservasModel->update($idReserva, [
                'notas' => $nota
            ]);

            return $this->response->setJSON([
                'status' => 'ok',
                'message' => 'Nota actualizada correctamente'
            ]);
        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al actualizar',
                'error' => $e->getMessage()
            ]);
        }
    }
    public function eliminar()
    {
        $data = $this->request->getJSON(true);

        $id = $data['id_reserva'] ?? null;

        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID inválido'
            ]);
        }

        $model = model('ReservasModel');

        try {

            $model->delete($id);

            return $this->response->setJSON([
                'status' => 'ok'
            ]);
        } catch (\Exception $e) {

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
