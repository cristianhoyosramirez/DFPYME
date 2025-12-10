<?php

namespace App\Controllers;

use App\Models\XmlModel; // Ajusta al nombre de tu modelo real

class Home extends BaseController
{
    public function insertarTxt()
    {
        // Ruta del archivo TXT
        $ruta = "C:\\Users\\Usuario\\Desktop\\891410661.txt";

        // Verificar que el archivo exista
        if (!file_exists($ruta)) {
            return "El archivo no existe.";
        }

        // Cargar el modelo
        $model = model('XmlModel');

        // Obtener todas las filas con txt vacío, ordenadas por id
        $filas = $model->where('txt', null)
                       ->orWhere('txt', '')
                       ->orderBy('id', 'ASC')
                       ->findAll();

        if (empty($filas)) {
            return "No hay filas vacías para actualizar.";
        }

        // Abrir y leer el archivo línea por línea
        $archivo = fopen($ruta, "r");
        $i = 0;

        while (($linea = fgets($archivo)) !== false) {
            $linea = trim($linea);

            // Si no hay más filas vacías, detener
            if (!isset($filas[$i])) {
                break;
            }

            // Solo actualizar si la línea no está vacía
            if ($linea !== '') {
                $model->update($filas[$i]['id'], [
                    'txt' => $linea
                ]);
            }

            $i++;
        }

        fclose($archivo);

        return "Datos actualizados correctamente.";
    }
}
