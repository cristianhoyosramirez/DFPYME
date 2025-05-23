<?php foreach ($productos as $detalleRecetas): ?>

    <tr id="rowInsumo<?php echo $detalleRecetas['codigointernoproducto']; ?>" onclick="detalleReceta(<?php echo $detalleRecetas['codigointernoproducto']; ?>)" style="cursor: pointer;">
        <td><?php echo $detalleRecetas['codigointernoproducto']; ?></td>
        <?php
        // Determinar el título según el tipo de inventario
        $titulo = ($detalleRecetas['id_tipo_inventario'] == 6) ? 'Subreceta' : 'Receta';
        ?>
        <td title="<?= $titulo ?>">
            <?php if ($detalleRecetas['id_tipo_inventario'] == 6): ?>
                <!-- Ícono solo para tipo 6 (subreceta) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 21.5c-3.04 0 -5.952 -.714 -8.5 -1.983l8.5 -16.517l8.5 16.517a19.09 19.09 0 0 1 -8.5 1.983z" />
                    <path d="M5.38 15.866a14.94 14.94 0 0 0 6.815 1.634a14.944 14.944 0 0 0 6.502 -1.479" />
                    <path d="M13 11.01v-.01" />
                    <path d="M11 14v-.01" />
                </svg>
            <?php endif; ?>
            <?= $detalleRecetas['nombreproducto']; ?>
        </td>


        <td><?php echo $detalleRecetas['precio_costo']; ?></td>
        <td><?php echo $detalleRecetas['valorventaproducto']; ?></td>
        <td>
            <button class="btn btn-outline-primary btn-sm" onclick="detalleReceta(<?php echo $detalleRecetas['codigointernoproducto']; ?>)">Ver</button>
        </td>

    </tr>

<?php endforeach ?>