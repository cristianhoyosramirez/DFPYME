<?php

namespace App\Libraries;

class convertirAdecimal
{
    public function convertirAdecimal($numero)
    {

        $numero = trim($numero);

        // Si viene con coma, reemplázala por punto
        $numero = str_replace(',', '.', $numero);

        // Convertir a float
        return floatval($numero);
    }
}
