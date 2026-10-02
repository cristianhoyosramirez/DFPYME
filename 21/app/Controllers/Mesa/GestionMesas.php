<?php

namespace App\Controllers\Mesa;

use App\Controllers\BaseController;

class GestionMesas extends BaseController
{
    public function index()
    {
        $mesas = model('mesasModel')->mesas();


        //return view('home/home');
        return $this->response->setJSON([
            'response' => 'success',

            'mesas' =>  view('mesa/mesas', [
                'mesas' => $mesas
            ]),

        ]);
    }

    public function eliminar()
    {
        $json = $this->request->getJSON();

        if (!$json || !isset($json->id)) {
            return $this->response->setJSON([
                'response' => 'error',
                'message'  => 'ID inválido'
            ])->setStatusCode(400);
        }

        $id = (int) $json->id;

        $delete = model('mesasModel')->delete($id);

        if ($delete) {
            $salon = model('salonesModel')->select('id')->where('tipo', 1)->first();
            $mesasWhatsApp = model('mesasModel')->where('fk_salon', $salon['id'])->findAll();

            return $this->response->setJSON([
                'response' => 'success',
                'message'  => 'Mesa eliminada correctamente',
                'mesas' => view('mesa/mesasWhatsapp', [
                    'mesas' => $mesasWhatsApp
                ])

            ]);
        } else {
            return $this->response->setJSON([
                'response' => 'error',
                'message'  => 'No se pudo eliminar la mesa'
            ])->setStatusCode(500);
        }
    }

    public function eliminarMesa()
    {
        $json = $this->request->getJSON();

        $idMesa = (int) $json->id;
        //$idMesa = (int) 1;

        $hayPedidos = model('PedidoModel')
            ->where('fk_mesa', $idMesa)
            ->countAllResults();

        $hayPagos = model('PagosModel')
            ->where('id_mesa', $idMesa)
            ->countAllResults();

        // Hay pedidos y pagos
        if ($hayPedidos > 0 && $hayPagos > 0) {

            return $this->response->setJSON([
                'status' => false,
                'mensaje' => 'La mesa tiene pedidos y pagos asociados.'
            ]);
        }

        // Solo pedidos
        if ($hayPedidos > 0) {

            return $this->response->setJSON([
                'status' => false,
                'mensaje' => 'La mesa tiene un pedido activo.'
            ]);
        }

        // Solo pagos
        if ($hayPagos > 0) {

            return $this->response->setJSON([
                'status' => false,
                'mensaje' => 'La mesa tiene pagos asociados.'
            ]);
        }

        // No hay pedidos ni pagos
        $delete = model('MesasModel')->delete($idMesa);

        if ($delete) {

            return $this->response->setJSON([
                'status'  => true,
                'mensaje' => 'Mesa eliminada correctamente.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => false,
            'mensaje' => 'No fue posible eliminar la mesa.'
        ]);
    }

    function edicionMesa()
    {

        $json = $this->request->getJSON();

        $id = (int) $json->id;
        $nombre =  $json->nombre;


        $update = model('mesasModel')->set('nombre', $nombre)->where('id', $id)->update();

        if ($update) {

            return $this->response->setJSON([
                'response' => 'success',
                'message'  => 'Mesa actualizada correctamente',


            ]);
        }
    }

    function mesaPedido()
    {
        $json = $this->request->getJSON();
        $id_mesa = $json->id_mesa;

        $tienePedido = model('pedidoModel')
            ->where('fk_mesa', $id_mesa)
            ->first();

        if ($tienePedido !== null) {
            // Sí tiene pedido
            return $this->response->setJSON([
                'response' => 'success',
                'pedido'   => $tienePedido
            ]);
        } else {
            // No tiene pedido
            return $this->response->setJSON([
                'response' => 'error',
                'message'  => 'La mesa no tiene pedidos'
            ]);
        }
    }
}
