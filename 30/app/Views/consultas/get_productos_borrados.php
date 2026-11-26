<?php if (!empty($productos)) {  ?>
    <?php foreach ($productos as $detalle) : ?>

        <tr>

            <td><?php echo $detalle['fecha_eliminacion'] ?></td>
            <td><?php echo $detalle['codigointernoproducto'] ?></td>
            <td><?php echo $detalle['nombreproducto'] ?></td>
            <td> <?= number_format($detalle['valor_unitario'], 0, ',', '.') ?></td>
            <td> <?= number_format($detalle['valor_unitario'] * $detalle['cantidad'], 0, ',', '.') ?></td>
            <td><?php echo $detalle['cantidad_eliminada'] ?></td>
            <td><?php echo $detalle['cantidad_pedido'] ?></td>
            <td><?php echo $detalle['comandados'] ?></td>
            <td><?php echo $detalle['pedido'] ?></td>
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