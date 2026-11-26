<tr>


    <?php foreach ($categorias as $detalle) { ?>

        <input type="hidden" name="codigo_categoria" value="<?php echo $detalle['codigocategoria'] ?>">
<tr>

    <td> <input type="text" value="<?php echo $detalle['nombrecategoria'] ?>" class="form-control" onkeyup="actualizar_categoria(event, this.value,<?php echo $detalle['codigocategoria'] ?>)" placeholder="Cambiar el nombre de la categoria "> </td>
    <td>
        <select class="form-select" id="estado_categoria" name="estado_categoria" onchange="estado_categoria(this.options[this.selectedIndex].value,<?php echo $detalle['codigocategoria'] ?>)">
            <option value="true" <?php if ($detalle['permitir_categoria'] == 't') : ?>selected <?php endif; ?>>
                <p class="text-success">ACTIVA</p>
            </option>
            <option value="false" <?php if ($detalle['permitir_categoria'] == 'f') : ?>selected <?php endif; ?>>
                <p class="text-success">INACTIVA</p>
            </option>
        </select>
    </td>
    <td>

        <select class="form-select" aria-label="Default select example" onchange="sub_categoria(this.options[this.selectedIndex].value,<?php echo $detalle['codigocategoria'] ?>)">

            <?php if ($detalle['subcategoria'] == 't') : ?>
                <option value="true" selected>Si </option>
                <option value="false">No </option>

            <?php endif ?>
            <?php if ($detalle['subcategoria'] == 'f') : ?>
                <option value="true">Si </option>
                <option value="false" selected>No </option>

            <?php endif ?>
        </select>
    </td>
    <td>
        <input type="text" class="form-control" value="<?php echo $detalle['orden']; ?>" onkeyup="actualizarOrden(this.value,<?php echo $detalle['id'];  ?>)">
    </td>
    <td class="text-end">
        <button type="button"
            class="btn btn-outline-danger "
            onclick="eliminar_categoria(<?php echo $detalle['id']; ?>)">
            <i class="fas fa-trash-alt"></i> Eliminar
        </button>
    </td>
</tr>
<tr>

</tr>

<?php } ?>
</tr>