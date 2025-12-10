<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $origen  = "C:/Users/Usuario/Desktop/14_8_2025_(1)[1]/";
        $destino = "C:/Users/Usuario/Desktop/carpeta_destino/";

        // Crear carpeta destino si no existe
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }

        // Escapar corchetes en ruta para evitar errores en glob
        $pattern = str_replace(["[", "]"], ["[[]", "[]]"], $origen . "*.json");

        // Buscar todos los JSON
        $archivos = glob($pattern);

        

        if (!$archivos) {
            echo "No se encontraron archivos JSON.";
            return;
        }

        foreach ($archivos as $archivo) {
            $nombre = basename($archivo);

            // Verificar si el nombre contiene _CUV (sin importar mayúsculas/minúsculas)
            if (stripos($nombre, '_CUV') !== false) {
                $nuevoPath = $destino . $nombre;
                if (copy($archivo, $nuevoPath)) {
                    echo "✅ Copiado: {$nombre}<br>";
                } else {
                    echo "⚠️ Error al copiar: {$nombre}<br>";
                }
            }
        }
    }
}
