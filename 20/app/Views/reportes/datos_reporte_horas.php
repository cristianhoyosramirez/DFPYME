while ($inicio < $fin) {
            $fecha_actual = $inicio->format('Y-m-d');

            for ($h = 0; $h < 24; $h++) {
                $hora_inicio = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00:00';
                $hora_fin = str_pad($h + 1, 2, '0', STR_PAD_LEFT) . ':00:00';

                $desde = "$fecha_actual $hora_inicio";
                $hasta = "$fecha_actual $hora_fin";

                echo "<h4> $desde - $hasta</h4>";

                // Consulta a la base de datos
              /*   $db = \Config\Database::connect();
                $builder = $db->table('kardex');
                $builder->select('producto.nombreproducto, SUM(kardex.cantidad) AS total_cantidad, SUM(kardex.valor_total) AS total_valor');
                $builder->join('producto', 'kardex.codigo_producto = producto.id');
                $builder->where('fecha_y_hora_factura_venta >=', $desde);
                $builder->where('fecha_y_hora_factura_venta <', $hasta); // "<" para no solapar la hora siguiente
                $builder->groupBy('producto.nombreproducto'); 

                $query = $builder->get();
                $resultados = $query->getResult();*/

                /* if (count($resultados) > 0) {
                    echo "<table border='1' cellpadding='5'>";
                    echo "<tr><th>Producto</th><th>Cantidad</th><th>Total</th></tr>";
                    foreach ($resultados as $row) {
                        echo "<tr>";
                        echo "<td>{$row->nombreproducto}</td>";
                        echo "<td>{$row->total_cantidad}</td>";
                        echo "<td>{$row->total_valor}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p style='color:gray;'>Sin ventas en este bloque</p>";
                } */
            }

            $inicio->modify('+1 day'); // Avanza al siguiente día
        }