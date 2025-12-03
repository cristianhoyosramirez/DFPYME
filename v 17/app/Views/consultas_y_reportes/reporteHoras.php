 <?php
    for ($hora = 0; $hora < 24; $hora++) {
        $inicio = str_pad($hora, 2, '0', STR_PAD_LEFT) . ":00";
        $fin = str_pad($hora + 1, 2, '0', STR_PAD_LEFT) . ":00";

        $productos = model('reporteProductoModel')->getProductos($inicio, $fin, $fecha);
        $total = model('reporteProductoModel')->getTotal($inicio, $fin, $fecha);

        // Mostrar solo si hay productos
        if (!empty($productos)) {
            echo "<tr><td colspan='4'><strong>HORA : {$inicio} - {$fin}</strong></td></tr>";

            foreach ($productos as $producto) {
                echo "<tr>";
                echo "<td>{$producto['codigo']}</td>";
                echo "<td>{$producto['nombreproducto']}</td>";
                echo "<td>{$producto['total_cantidad']}</td>";
                echo "<td>$" . number_format($producto['total_valor'], 0, ',', '.') . "</td>";
                echo "</tr>";
            }

            echo "<tr>";
            echo "<td colspan='4' style='text-align: left; font-weight: bold;'>TOTAL VENDIDO ENTRE {$inicio} Y LAS {$fin}: $" . number_format($total[0]['total_valor'], 0, ',', '.') . "</td>";
            echo "</tr>";
        }
    }
    ?>