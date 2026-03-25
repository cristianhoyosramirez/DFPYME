<?php

namespace App\Controllers\Categorias;
use App\Controllers\BaseController;

class CategoriasController extends BaseController

{
    public function index(): string
    {
        return view('categorias/index');
    }
}