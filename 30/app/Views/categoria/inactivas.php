<table class="table">
    <thead class="table-dark">
        <tr>
            <td scope="col">Categoria</th>
            <td scope="col">Acción</th>

        </tr>
    </thead>
    <tbody>

        <?php foreach ($categorias as $detalle): ?>

            <tr id="categoriaActivada">
                <td><?= $detalle['nombrecategoria']  ?></th>
                <td class="text-center">
                    <button
                        type="button"
                        class="btn  btn-outline-success"
                        onclick="activarCategoria(<?= $detalle['id'] ?>)">
                        <i class="mdi mdi-trash-can-outline me-1"></i>
                        Activar 
                    </button>
                </td>

            </tr>

        <?php endforeach ?>

    </tbody>
</table>