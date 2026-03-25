<?php

namespace App\Controllers\Productos;
use App\Controllers\BaseController;

class ProductosController extends BaseController

{
    public function index(): string
    {
        return view('Productos/index');
    }

   public function bloqueCompuesto()
{
    // Seguridad básica: solo AJAX
    if (!$this->request->isAJAX()) {
        return redirect()->back();
    }

    // Obtener datos enviados por POST (JSON)
    $data = $this->request->getJSON(true);

    // Validar tipo de producto
    if (!isset($data['tipo']) || $data['tipo'] !== 'compuesto') {
        return $this->response->setStatusCode(400);
    }

    // Aquí podrías traer componentes desde BD (opcional)
    // $componentes = $this->productoModel->getComponentesBase();

    //return view('productos/partials/bloque_compuesto'/*, [
    return view('productos/index'/*, [
        'componentes' => $componentes
    ]*/);
}

}