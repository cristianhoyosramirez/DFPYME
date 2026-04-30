<?php foreach ($productos as $detalleRecetas): ?>


    <tr id="rowInsumo<?php echo $detalleRecetas['codigointernoproducto']; ?>" style="cursor: pointer;">
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

            <div class="input-group">
                <input type="text"
                    value="<?= $detalleRecetas['nombreproducto']; ?>"
                    class="form-control"
                    id="nombreProducto_<?= $detalleRecetas['codigointernoproducto']; ?>"
                    placeholder="Nombre del producto">

                <button class="btn btn-outline-success btn-icon" title="Actualizar el nombre"
                    type="button"
                    onclick="actualizarNombreProducto(<?= $detalleRecetas['codigointernoproducto']; ?>)">
                    <!-- Download SVG icon from http://tabler-icons.io/i/refresh -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                    </svg>
                </button>
            </div>

        </td>



        <td><?php echo number_format($detalleRecetas['precio_costo'], 0, ',', '.'); ?></td>
        <td><?php echo number_format($detalleRecetas['valorventaproducto'], 0, ',', '.'); ?></td>
        <td class="d-flex justify-content-center gap-2">
            <button class="btn btn-outline-primary btn-icon"
                onclick="detalleReceta(<?php echo $detalleRecetas['codigointernoproducto']; ?>)">
                <!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="12" cy="12" r="2" />
                    <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                </svg>
            </button>


            <button class="btn btn-outline-danger  btn-icon"
                onclick="eliminacionReceta(<?php echo $detalleRecetas['codigointernoproducto']; ?>,'<?php echo $detalleRecetas['nombreproducto']; ?>')">
                <!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="4" y1="7" x2="20" y2="7" />
                    <line x1="10" y1="11" x2="10" y2="17" />
                    <line x1="14" y1="11" x2="14" y2="17" />
                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </svg>
            </button>

        </td>


    </tr>

<?php endforeach ?>