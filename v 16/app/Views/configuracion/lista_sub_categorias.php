<table class="table">
    <input type="hidden" value="<?php echo base_url() ?>" id="url">
    <thead class="table-dark">
        <tr>
            <td scope="col">Nombre</th>
            <td scope="col">Acción</th>

        </tr>
    </thead>
    <tbody>


        <?php foreach ($id_categorias as $detalle) {
            $sub_categorias = model('subCategoriaModel')->where('id_categoria', $detalle['id_categoria'])->findAll();
            $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('id', $detalle['id_categoria'])->first();
        ?>

            <tr class="table-primary">

                <td><?php echo " Categoria: " . $nombre_categoria['nombrecategoria']  ?></td>
                <td></td>
            </tr>

            <?php foreach ($sub_categorias as $valor_sub) : ?>
                <tr>

                    <td><?php echo $valor_sub['nombre'] ?></td>
                    <td><button type="button" class="btn btn-outline-success" onclick="editar_categoria(<?php echo $valor_sub['id'] ?>)">
                            Editar
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="eliminar_categoria(<?php echo $valor_sub['id'] ?>)">
                            Eliminar
                        </button>
                    </td>

                </tr>
            <?php endforeach ?>
        <?php } ?>
    </tbody>
</table>