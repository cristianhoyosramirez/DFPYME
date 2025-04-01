<?php 
$textoAtributos = [];

foreach ($atributos as $detalle): 
    $nombreAtributo = model('atributosProductoModel')->select('nombre')->where('id', $detalle['id_atributo'])->first();
    $componentes = model('atributosDeProductoModel')->getComponentes($id_tabla_producto, $detalle['id_atributo']);

    // Construir texto con atributos en verde y componentes en negro
    $texto = "<span style='color: orange; font-weight: bold;'>" . $nombreAtributo['nombre'] . "</span>";

    if (!empty($componentes)) {
        $texto .= " (<span style='color: green;'>" . implode(', ', array_column($componentes, 'nombre')) . "</span>)";
    } else {
        $texto .= " (<span style='color: red;'>No hay componentes disponibles</span>)";
    }

    $textoAtributos[] = $texto;
endforeach;

// Mostrar todos los atributos en una línea separados por coma
echo implode(', ', $textoAtributos);
?>
