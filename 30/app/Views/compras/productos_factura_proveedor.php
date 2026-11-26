<div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="row mb-2">
            <div class="col-12 col-md-6">
                <small class="text-muted">Fecha</small>
                <div class="fw-semibold"><?php echo $fecha ?></div>
            </div>

            <div class="col-12 col-md-6">
                <small class="text-muted">Documento</small>
                <div class="fw-semibold"><?php echo $numero_factura ?></div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12 col-md-6">
                <small class="text-muted">Proveedor</small>
                <div class="fw-semibold"><?php echo $proveedor ?></div>
            </div>

            <div class="col-12 col-md-6">
                <small class="text-muted">Total</small>
                <div class="fw-bold text-primary">
                    $ <?php echo number_format($total ?? 0, 0, ',', '.') ?>
                </div>
            </div>
          
        </div>

    </div>
</div>
<div class="mb-3"></div>
<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <td scope="col">Código</td>
                    <td scope="col">Producto </td>
                    <td scope="col">Cantidad</td>
                    <td scope="col">Valor unitario </td>
                    <td scope="col">Sub total </td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $detalle): ?>
                    <tr>

                        <td><?php echo $detalle['codigointernoproducto'] ?></td>
                        <td><?php echo $detalle['nombreproducto'] ?></td>
                        <td><?php echo $detalle['cantidad'] ?></td>
                        <td><?php echo number_format($detalle['valor_unitario'], 0, ',', '.') ?></td>
                        <td><?php echo number_format($detalle['valor_unitario'] * $detalle['cantidad'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <p class="text-primary h3 text-end ">Total: <?php echo $total ?></p>
    </div>
    
</div>
