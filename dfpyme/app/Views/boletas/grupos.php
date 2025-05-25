<?php
$grupos = model('grupoImpresionModel')->orderBy('id', 'desc')->findAll();
$impresoras = model('impresorasModel')->findAll();

?>

<?php foreach ($grupos as $detalleGrupos):  ?>
    <tr id="grupo<?php echo $detalleGrupos['id'] ?>">
        <td><input type="text" class="form-control" value="<?php echo $detalleGrupos['nombre']; ?>" id="nombre<?php echo $detalleGrupos['id'] ?>"></td>
        <td>
            <select name="" class="form-select" id="impresora<?php echo $detalleGrupos['id'] ?>">
                <?php foreach ($impresoras as $detalleImpresoras): ?>
                    <option value="<?php echo $detalleImpresoras['id']; ?>"
                        <?php if ($detalleImpresoras['id'] == $detalleGrupos['id_impresora_asignada']) echo 'selected'; ?>>
                        <?php echo $detalleImpresoras['nombre']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-success btn-icon" data-bs-toggle="tooltip" title="Actualizar" data-bs-placement="bottom" onclick="actualizarGrupo(<?php echo $detalleGrupos['id'] ?>)"><!-- Download SVG icon from http://tabler-icons.io/i/refresh -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                    </svg></button>
                <button onclick="eliminacioGrupo(<?php echo $detalleGrupos['id'] ?>)" class="btn btn-outline-danger btn-icon" data-bs-toggle="tooltip" title="Eliminar" data-bs-placement="bottom"><!-- Download SVG icon from http://tabler-icons.io/i/trash -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="4" y1="7" x2="20" y2="7" />
                        <line x1="10" y1="11" x2="10" y2="17" />
                        <line x1="14" y1="11" x2="14" y2="17" />
                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg></button>
            </div>
        </td>
    </tr>
<?php endforeach ?>