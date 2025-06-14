<?php foreach ($productosAtributos as $detalle): ?>

    <?php   ?>

    <table class="table">
        <thead class="table-dark">
            <tr>
                <td scope="col">Producto </th>
                    <!--  <td scope="col">Accion </th> -->
            </tr>
        </thead>
        <tbody>
            <tr>


                <td>

                    <?php
                    $nombreProducto = model('productoModel')->select('nombreproducto,codigointernoproducto')->where('id', $detalle['id_producto'])->first();
                    echo $nombreProducto['codigointernoproducto']."/".$nombreProducto['nombreproducto'];
                    ?>

                </td>

            </tr>


        </tbody>
    </table>


<?php endforeach ?>