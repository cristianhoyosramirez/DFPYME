<tr>
    <td scope="col">Por que esta presente en : </th>
        <!--  <td scope="col">Accion </th> -->
</tr>
<?php foreach ($productosAtributos as $detalle): ?>

    <?php   ?>

    <table class="table">
        <thead class="table-dark">

        </thead>
        <tbody>
            <tr>


                <td>

                    <?php
                    $nombreProducto = model('productoModel')->select('nombreproducto,codigointernoproducto')->where('id', $detalle['id_producto'])->first();
                    echo $nombreProducto['codigointernoproducto'] . "/" . $nombreProducto['nombreproducto'];
                    ?>

                </td>

            </tr>


        </tbody>
    </table>


<?php endforeach ?>