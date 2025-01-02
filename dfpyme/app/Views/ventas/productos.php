<?php foreach ($productos as $keyProducto): ?>
    <tr>
        <td><?php echo $keyProducto['codigointernoproducto']; ?></td>
        <td class="nombre-producto"><?php echo $keyProducto['nombreproducto']; ?></td>
        <td>
            <input type="text" class="form-control input-inventario"
                value="<?php echo $keyProducto['cantidad_inventario']; ?>">
        </td>
        <?php $registro = model('inventarioFisicoModel')->select('cantidad_inventario_fisico')->where('codigointernoproducto', $keyProducto['codigointernoproducto'])->first();  ?>
        <?php if (empty($registro)): ?>
            <td>
                <input type="text" class="form-control input-inventario" id="<?php echo $keyProducto['id']; ?>" onkeyup="ingresarInv(this.value,<?php echo $keyProducto['id']; ?> )">
            </td>
        <?php endif ?>
        <?php if (!empty($registro)): ?>
            <td>
                <input type="text" value="<?php echo $registro['cantidad_inventario_fisico'];  ?>" class="form-control input-inventario" id="<?php echo $keyProducto['id']; ?>" onkeyup="ingresarInv(this.value,<?php echo $keyProducto['id']; ?> )">
            </td>
        <?php endif ?>
    </tr>
<?php endforeach; ?>