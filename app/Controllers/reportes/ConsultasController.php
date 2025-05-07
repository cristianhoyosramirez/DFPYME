<?php

namespace App\Controllers\reportes;
use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        return view('reportes/mesero');
    }
}
