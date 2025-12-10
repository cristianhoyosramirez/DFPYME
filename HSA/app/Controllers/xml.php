<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function insertarXml()
    {
    

        $carpeta = "C:\\Users\\Usuario\\Desktop\\14_8_2025_(1)[1]";
 
        $model = model('XmlModel');

        $archivos = scandir($carpeta);

        foreach ($archivos as $archivo) {
            if (pathinfo($archivo, PATHINFO_EXTENSION) === 'xml') {
                $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);

                echo  $nombreArchivo."</br>";

                // Guardar en la base de datos
                $model->insert([
                    'xml' => $nombreArchivo
                ]);
            }
        }

        return "Archivos guardados correctamente.";
    }
}
