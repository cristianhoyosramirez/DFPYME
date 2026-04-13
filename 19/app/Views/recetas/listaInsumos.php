<?php $unidadesMedida = model('unidadMedidaModel')->findAll(); ?>

<?php foreach ($productos as $detalleInsumo):

    $unidadMedida = model('productoFabricadoModel')->GetMedida($detalleInsumo['codigointernoproducto']);
?>

    <tr>
        <td><?php echo $detalleInsumo['codigointernoproducto'] ?></td>


        <td>



            <div class="input-group">
                <input type="text"
                    value="<?= $detalleInsumo['nombreproducto']; ?>"
                    class="form-control"
                    id="nombreProducto_<?= $detalleInsumo['codigointernoproducto']; ?>"
                    placeholder="Nombre del producto" title="<?= $detalleInsumo['nombreproducto']; ?>">

                <button class="btn btn-outline-success btn-icon" title="Actualizar el nombre"
                    type="button"
                    onclick="actualizarNombreProducto(<?= $detalleInsumo['codigointernoproducto']; ?>)">
                    <!-- Download SVG icon from http://tabler-icons.io/i/refresh -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                    </svg>
                </button>
            </div>


        </td>
        <td>
            <select name="" class="form-select" onchange="cambiarUnidadMedida(this, '<?php echo $detalleInsumo['codigointernoproducto']; ?>')">
                <!-- Opción seleccionada por defecto -->
                <option value="<?php echo $unidadMedida[0]['idunidad_medida']; ?>" selected>
                    <?php echo $unidadMedida[0]['medida']; ?>
                </option>

                <!-- Filtramos el array para eliminar la opción ya seleccionada -->
                <?php foreach ($unidadesMedida as $detalleUnidad): ?>
                    <option value="<?php echo $detalleUnidad['idvalor_unidad_medida']; ?>">
                        <?php echo $detalleUnidad['descripcionvalor_unidad_medida']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><?php echo number_format($detalleInsumo['precio_costo'], 0, ',', '.') ?></td>
        <td class="text-center">
            <div class="d-flex justify-content-center gap-2">
                <!-- Botón Ver -->
                <button class="btn btn-outline-success btn-icon"
                    id="insu<?= $detalleInsumo['codigointernoproducto']; ?>"
                    onclick="selectInsumo(<?= $detalleInsumo['codigointernoproducto']; ?>, '<?= htmlspecialchars($detalleInsumo['nombreproducto'], ENT_QUOTES, 'UTF-8'); ?>')">
                    <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                </button>

                <!-- Botón Eliminar -->
                <button class="btn btn-outline-danger btn-icon"
                    id="del<?= $detalleInsumo['codigointernoproducto']; ?>"
                    onclick="eliminacionReceta(<?= $detalleInsumo['codigointernoproducto']; ?>, '<?= addslashes($detalleInsumo['nombreproducto']); ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="4" y1="7" x2="20" y2="7" />
                        <line x1="10" y1="11" x2="10" y2="17" />
                        <line x1="14" y1="11" x2="14" y2="17" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </button>
            </div>
        </td>

    </tr>

<?php endforeach ?>