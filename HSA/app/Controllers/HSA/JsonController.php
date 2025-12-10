<?php

namespace App\Controllers\HSA;

use App\Controllers\BaseController;



class JsonController extends BaseController
{
public function getFiles()
{
    // Normalizar la ruta recibida
    $ruta = $this->request->getPost('ruta');


    // Convertir \ a /
    $ruta = str_replace('\\', '/', $ruta);

    // Asegurarse de que termine en /
    $ruta = rtrim($ruta, '/');
    $ruta .= '/';

    // Validar que exista
    if (!is_dir($ruta)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'La ruta no existe: ' . $ruta
        ]);
    }

    // Filtrar solo JSON
    $archivos = array_values(array_filter(scandir($ruta), function ($file) use ($ruta) {
        return is_file($ruta . $file) &&
            pathinfo($file, PATHINFO_EXTENSION) === 'json';
    }));

    $data = [];

    foreach ($archivos as $index => $file) {

        // RUTA CORREGIDA 100% SEGURA
        $fullPath = $ruta . $file;

        

        $contenido = file_get_contents($fullPath);
        $json = json_decode($contenido, true);

        $data[] = [
            'id_html'   => 'json_' . $index,
            'nombre'    => $file,
            'ruta'      => $fullPath,
            'contenido' => json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        ];
    }

    $html = view('json/editor_archivos', [
        'archivos' => $data
    ]);

    return $this->response->setJSON([
        'status' => 'success',
        'html'   => $html
    ]);
}




    /*     public function guardarJson()
{
    // Recuperar la ruta del archivo
    //$ruta = $this->request->getPost('ruta'); 
    $ruta = "C:\Users\Usuario\Desktop\json_files"; 
    $contenido = $this->request->getPost('contenido');

    // Validar que la ruta exista
    if (!$ruta || !is_file($ruta)) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'La ruta del archivo no existe'
        ]);
    }

    // Validar que el contenido sea JSON válido
    json_decode($contenido);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'El contenido no es un JSON válido'
        ]);
    }

    // Intentar guardar
    if (file_put_contents($ruta, $contenido) === false) {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'No se pudo guardar el archivo (permisos o ruta protegida)'
        ]);
    }

    return $this->response->setJSON([
        'status'  => 'success',
        'message' => 'Archivo guardado correctamente'
    ]);
} */

    /* public function guardarJson()
    {
        //$ruta = $this->request->getPost('ruta');     // <= RECIBE RUTA COMPLETA
        $ruta = "C:\Users\Usuario\Desktop\json_files"; 
        $contenido = $this->request->getPost('contenido');

     

        // Validar JSON
        json_decode($contenido);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'El contenido no es JSON válido'
            ]);
        }

        // Guardar
        if (file_put_contents($ruta, $contenido) === false) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'No se pudo guardar el archivo'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Archivo guardado correctamente'
        ]);
    } */

    /*  public function save()
    {
        $ruta = $this->request->getPost('ruta');
        $contenido = $this->request->getPost('contenido');

        // Validaciones básicas
        if (!$ruta || !file_exists($ruta)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'mensaje' => 'La ruta del archivo no existe.'
            ]);
        }

        if ($contenido === null) {
            return $this->response->setJSON([
                'status'  => 'error',
                'mensaje' => 'No se recibió contenido para guardar.'
            ]);
        }

        // Validar JSON antes de guardar
        $decoded = json_decode($contenido, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->response->setJSON([
                'status'  => 'error',
                'mensaje' => 'JSON inválido: ' . json_last_error_msg()
            ]);
        }

        // Guardar manteniendo formato bonito
        $guardado = file_put_contents(
            $ruta,
            json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        if ($guardado === false) {
            return $this->response->setJSON([
                'status'  => 'error',
                'mensaje' => 'No se pudo guardar el archivo.'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'ok',
            'mensaje' => 'Archivo guardado correctamente.'
        ]);
    } */

    public function save()
    {
        $request = \Config\Services::request();
        $response = \Config\Services::response();

        $rutaCompleta = $request->getPost('ruta'); // Ahora recibimos la ruta completa
        // $rutaCompleta = 'F:\json_files/DIAN_FEST143.json'; // Ahora recibimos la ruta completa
        $contenido = $request->getPost('contenido');

        

        // Validar que sea JSON
        if (substr($rutaCompleta, -5) !== '.json') {
            return $response->setJSON([
                'status' => 'error',
                'mensaje' => 'Archivo no válido.'
            ]);
        }

        // Validar que la carpeta exista
        $carpeta = dirname($rutaCompleta);
        if (!is_dir($carpeta)) {
            return $response->setJSON([
                'status' => 'error',
                'mensaje' => 'La carpeta no existe: ' . $carpeta
            ]);
        }

        // Intentar guardar el archivo
        if (file_put_contents($rutaCompleta, $contenido) !== false) {
            return $response->setJSON([
                'status' => 'ok',
                'mensaje' => 'Archivo guardado correctamente.'
            ]);
        } else {
            return $response->setJSON([
                'status' => 'error',
                'mensaje' => 'No se pudo guardar el archivo.'
            ]);
        }
    }
}
