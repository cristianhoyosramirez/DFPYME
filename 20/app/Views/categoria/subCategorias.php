<?php if (!empty($sub_categorias)) : ?>
    <div class="col-md-3" id="edicion_div_sub_categoria">
            <input type="hidden" id="requiere_categoria" value=0>
            <label for="" class="form-label">Sub categoria</label>
            <select class="form-select" aria-label="Default select example">
                <?php foreach ($sub_categorias as $detalle) { ?>

                    <option value="<?php echo $detalle['id'] ?>"><?php echo $detalle['nombre'] ?> </option>
                <?php } ?>
            </select>
        <?php endif ?>

        <?php if (empty($sub_categorias)) : ?>

            <p>No hay sub categorias creadas </p>
    </div>
<?php endif ?>