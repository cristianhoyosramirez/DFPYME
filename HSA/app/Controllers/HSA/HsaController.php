<?php

namespace App\Controllers\HSA;

use App\Controllers\BaseController;



class HsaController extends BaseController
{
    public function index()
    {
        //return view('hsa/seleccionar');
        //return view('hsa/dist/index');
        return view('home/index');
    }





    public function insertarArchivos()

    {

        $carpetaXml = $this->request->getPost('carpetaXml');
        $rutaTxt    = $this->request->getPost('rutaTxt');


        // Definir rutas
        //$carpetaXml = "C:\\Users\\Usuario\\Desktop\\PRUEBA";
        $carpetaXml = "C:\\Users\\Usuario\\Desktop\\" . $carpetaXml;
        $carpetaXml = 'C:\\Users\\USUARIO\Desktop\\' . $carpetaXml;


        //$rutaTxt    = "C:\\Users\\Usuario\\Desktop\\891410661.txt";
        $rutaTxt = "C:\\Users\\Usuario\\Desktop\\" . $rutaTxt;





        $model = model('XmlModel');

        // 1. Limpiar tabla antes de insertar
        $model->truncate();



        // =====================
        // 2. Insertar archivos XML
        // =====================
        if (!is_dir($carpetaXml)) {
            return "La carpeta XML no existe: " . htmlspecialchars($carpetaXml);
        }

        $archivos = glob($carpetaXml . DIRECTORY_SEPARATOR . '*.xml');



        if (empty($archivos)) {
            return "No se encontraron archivos XML en la carpeta: " . htmlspecialchars($carpetaXml);
        }

        foreach ($archivos as $archivo) {
            $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);

            // Insertar solo el nombre en columna 'xml', dejando 'txt' vacío
            $model->insert([
                'xml' => $nombreArchivo,
                'txt' => ""
            ]);
        }

        // =====================
        // 3. Leer archivo TXT y actualizar registros
        // =====================
        if (!file_exists($rutaTxt)) {
            return "El archivo TXT no existe: " . htmlspecialchars($rutaTxt);
        }

        // Obtener todas las filas en orden
        $filas = $model->orderBy('id', 'ASC')->findAll();

        $archivo = fopen($rutaTxt, "r");
        $i = 0;

        while (($linea = fgets($archivo)) !== false) {
            $linea = trim($linea);

            if (!isset($filas[$i])) {
                break;
            }

            if ($linea !== '') {
                $model->update($filas[$i]['id'], [
                    'txt' => $linea
                ]);
            }

            $i++;
        }

        fclose($archivo);

        return $this->renombrar();
    }


    public function renombrar()
    {
        // Carpeta donde están los XML
        $carpeta = "C:/Users/Usuario/Desktop/PRUEBA/";

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

    public function componente($componente)
    {
        // Sanitizar nombre
        $componente = preg_replace('/[^a-zA-Z0-9_\-]/', '', $componente);

        // Ruta real del archivo de vista
        $ruta = APPPATH . 'views/home/' . $componente . '.php';

        if (file_exists($ruta)) {
            return view('home/' . $componente);
        } else {
            return "Componente no encontrado: " . $componente;
        }
    }
}
