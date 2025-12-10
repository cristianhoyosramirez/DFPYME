<?php

namespace App\Controllers;

use App\Models\XmlModel; // Asegúrate de que exista este modelo

class Home extends BaseController
{
    public function index()
    {
        // Carpeta donde están los XML
        $carpeta = "C:/Users/Usuario/Desktop/14_8_2025_(1)[1]/";

        $model = model('XmlModel');
        $registros = $model->findAll();

        foreach ($registros as $row) {
            // Si en la BD ya está sin extensión, agregarla; si no, usar tal cual
            $nombreOriginal = pathinfo($row['xml'], PATHINFO_EXTENSION) ? $row['xml'] : $row['xml'] . '.xml';
            $nombreNuevo    = pathinfo($row['txt'], PATHINFO_EXTENSION) ? $row['txt'] : $row['txt'] . '.xml';

            $rutaOriginal = $carpeta . $nombreOriginal;
            $rutaNueva    = $carpeta . $nombreNuevo;

            if (file_exists($rutaOriginal)) {
                if (rename($rutaOriginal, $rutaNueva)) {
                    echo "✅ Renombrado: {$nombreOriginal} → {$nombreNuevo} <br>";
                } else {
                    echo "⚠️ Error al renombrar: {$nombreOriginal} <br>";
                }
            } else {
                echo "❌ No encontrado: {$nombreOriginal} <br>";
            }
        }
    }
}
