<!-- =========================
     BASES DE IMPUESTOS
========================= -->

<?php if (!empty($iva)) : ?>
    <?php foreach ($iva as $item) : ?>

        <?php
        $porcentaje = $item['iva'];

        $venta      = model('kardexModel')->total_iva_apertura($apertura, $porcentaje);
        $impuesto   = model('kardexModel')->total_iva_producto_apertura($apertura, $porcentaje);

        $base = $venta[0]['total'] - $impuesto[0]['iva'];
        ?>

        <div class="row">
            <div class="col-8"></div>

            <div class="col-2 text-end">
                <?= "Base IVA {$porcentaje}%" ?>
            </div>

            <div class="col-2 text-end">
                <?= "$ " . number_format($base, 0, ",", ".") ?>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>


<?php if (!empty($inc)) : ?>
    <?php foreach ($inc as $item) : ?>

        <?php
        $porcentaje = $item['inc'];

        $venta      = model('kardexModel')->total_inc_apertura($apertura, $porcentaje);
        $impuesto   = model('kardexModel')->total_inc_producto_apertura($apertura, $porcentaje);

        $base = $venta[0]['total'] - $impuesto[0]['inc'];
        ?>

        <div class="row">
            <div class="col-8"></div>

            <div class="col-2 text-end">
                <?= "Base INC {$porcentaje}%" ?>
            </div>

            <div class="col-2 text-end">
                <?= "$ " . number_format($base, 0, ",", ".") ?>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>


<!-- =========================
     VALOR DE IMPUESTOS
========================= -->

<?php if (!empty($iva)) : ?>
    <?php foreach ($iva as $item) : ?>

        <?php
        $porcentaje = $item['iva'];

        model('kardexModel')->total_iva_fecha_apertura($apertura, $porcentaje); // Se conserva por compatibilidad

        $impuesto = model('kardexModel')->total_iva_producto_apertura($apertura, $porcentaje);
        ?>

        <div class="row">
            <div class="col-8"></div>

            <div class="col-2 text-end">
                <?= "Valor IVA {$porcentaje}%" ?>
            </div>

            <div class="col-2 text-end">
                <?= "$ " . number_format($impuesto[0]['iva'], 0, ",", ".") ?>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>


<?php if (!empty($inc)) : ?>
    <?php foreach ($inc as $item) : ?>

        <?php
        $porcentaje = $item['inc'];

        $impuesto = model('kardexModel')->total_inc_fecha_apertura($apertura, $porcentaje);
        ?>

        <div class="row">
            <div class="col-8"></div>

            <div class="col-2 text-end">
                <?= "Valor INC {$porcentaje}%" ?>
            </div>

            <div class="col-2 text-end">
                <?= "$ " . number_format($impuesto[0]['total'], 0, ",", ".") ?>
            </div>
        </div>

    <?php endforeach; ?>
<?php endif; ?>


<div class="row">
    <div class="col-8"></div>

    <div class="col-2 text-end">
        <p class="text-orange h3">Venta total</p>
    </div>

    <div class="col-2 text-end">
        <p class="text-orange h3">
            <?= "$ " . number_format($venta_total, 0, ",", ".") ?>
        </p>
    </div>
</div>