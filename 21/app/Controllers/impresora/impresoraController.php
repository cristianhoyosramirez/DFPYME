<?php

namespace App\Controllers\impresora;

use App\Controllers\BaseController;

class impresoraController extends BaseController
{
    public function index()
    {
        $impresoras = model('impresorasModel')->select('*')->orderBy('id')->findAll();
        return view('impresora/listado', [
            "impresoras" => $impresoras
        ]);
    }

    public function datos_iniciales()
    {
        return view('impresora/datos_iniciales');
    }

    public function salvar()
    {
        if (!$this->validate([
            'nombre_impresora' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $data = [
            'nombre' => $this->request->getVar('nombre_impresora'),

        ];
        $insert = model('impresorasModel')->insert($data);
        if ($insert) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('impresora/listado'))->with('mensaje', 'Creación correcta');
        } else {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'error');
            return redirect()->to(base_url('impresora/listado'))->with('mensaje', 'Hubo errores');
        }
    }

    public function editar()
    {
        $id_impresora = $_POST['id_impresora'];

        $impresora = model('impresorasModel')->select('*')->where('id', $id_impresora)->first();

        return view('impresora/editar', [
            'id_impresora' => $impresora['id'],
            'nombre_impresora' => $impresora['nombre'],

        ]);
    }
    public function actualizar()
    {
        $id_impresora = $_POST['id_impresora'];

        $data = [
            'nombre' => $this->request->getPost('nombre_impresora'),
        ];

        $model = model('impresorasModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('id', $id_impresora);
        $actualizar = $model->update();

        if ($actualizar) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('impresora/listado'))->with('mensaje', 'actualizacion correcta');
        }
    }

    /*     public function eliminar()
    {
        $id_impresora = $_POST['id_impresora'];

       
        $model = model('impresorasModel');
        
        $borrar = $model->where('id', $id_impresora);
        $borrar = $model->delete();

        if ($borrar) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('impresora/listado'))->with('mensaje', 'actualizacion correcta');
        }
    } */

    public function eliminar()
    {
        $id_impresora = $this->request->getPost('id_impresora');

        $session = session();

        // Validar si la impresora está asociada a una pre cuenta
        $existePreCuenta = model('preCuentaModel')
            ->where('id_impresora', $id_impresora)
            ->countAllResults();

            

        if ($existePreCuenta > 0) {

            $session->setFlashdata('iconoMensaje', 'error');

            return redirect()->to(base_url('impresora/listado'))
                ->with(
                    'mensaje',
                    'No es posible eliminar la impresora porque se encuentra asociada a una o más configuraciones de pre cuenta.'
                );
        }

        $model = model('impresorasModel');

        $borrar = $model->delete($id_impresora);

        if ($borrar) {

            $session->setFlashdata('iconoMensaje', 'success');

            return redirect()->to(base_url('impresora/listado'))
                ->with(
                    'mensaje',
                    'Impresora eliminada correctamente.'
                );
        }

        $session->setFlashdata('iconoMensaje', 'error');

        return redirect()->to(base_url('impresora/listado'))
            ->with(
                'mensaje',
                'No fue posible eliminar la impresora.'
            );
    }

    public function administracion()
    {
        $impresoras = model('impresorasModel')->orderBy('id', 'desc')->find();
        return view('impresora/administrar_impresoras', [
            'impresoras' => $impresoras
        ]);
    }

    public function actualizarEstadoLicencia()
    {
        $estado = $this->request->getPost('estado');
        $mensaje = $this->request->getPost('mensaje');

        $data = [

            'estado_licencia' => $estado,
            'mensaje_licencia' => $mensaje
        ];

        $update = model('licenciaModel')->set($data)->update();
    }
    public function actualizarEstadoConsumo()
    {
        $estado = $this->request->getPost('estado');
        $mensaje = $this->request->getPost('mensaje');

        $data = [

            'estado_consumo' => $estado,
            'mensaje_consumo' => $mensaje
        ];

        $update = model('estadoPagoConsumoModel')->set($data)->update();
    }
}
