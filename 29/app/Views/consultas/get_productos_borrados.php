<?php if (!empty($productos)) {  ?>
    <?php foreach ($productos as $detalle) : ?>

        <tr>

            <td><?php echo $detalle['pedido'] ?></td>
            <td><?php echo $detalle['codigointernoproducto'] ?></td>
            <td><?php echo $detalle['nombreproducto'] ?></td>
            <td><?php echo $detalle['cantidad'] ?></td>
            <td><?php

                $fecha = $detalle['fecha_eliminacion']; // ejemplo "2025-09-26"
                $fmt = new IntlDateFormatter(
                    'es_ES',
                    IntlDateFormatter::FULL,
                    IntlDateFormatter::NONE
                );

                echo $fmt->format(new DateTime($fecha));

                ?></td>
            <td><?php
                $hora = $detalle['hora_eliminacion'];
                $hora_formateada = date("h:i A", strtotime($hora));
                echo $hora_formateada; ?></td>

            <td><?php echo $detalle['nombresusuario_sistema'] ?></td>
            <td><?php echo $detalle['mesero_nombre'] ?></td>
            <td><?php echo $detalle['justificacion'] ?></td>

        </tr>

    <?php endforeach ?>
<?php } ?>