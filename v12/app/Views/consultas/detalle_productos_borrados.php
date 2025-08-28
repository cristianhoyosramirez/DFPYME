
<div class="row ">
    <div class="col">Pedido número </div>
    <div class="col"></div>
    <div class="col">Fecha pedido </div>
    <div class="col"></div>
      <div class="col">Fecha eliminación </div>
    <div class="col"></div>
</div>
  

<div class="mb-3"></div>

<table class="table">
    <thead class="table-dark">
        <td scope="col">Código </th>
        <td scope="col">Producto </th>
        <td scope="col">Cantidad </th>
        <td scope="col">Valor unitario </th>
        <td scope="col">total </th>

    </thead>
    <tbody>
        <?php foreach ($productos as $detalle) : ?>

            <tr>

                <td><?php echo $detalle['codigointernoproducto'] ?></td>

                <td><?php echo $detalle['nombreproducto'] ?></td>
                <td><?php echo $detalle['cantidad'] ?></td>
                <td><?php echo number_format($detalle['valor_unitario'], 0, ',', '.'); ?></td>
                <td><?php echo number_format($detalle['valor_unitario'] * $detalle['cantidad'], 0, ',', '.'); ?></td>



            </tr>

        <?php endforeach ?>
    </tbody>
</table>