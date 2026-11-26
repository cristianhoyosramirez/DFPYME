<div class="row">

<?php foreach ($formas_pago as $pago): ?>

    <div class="col-md-6 mb-3">

        <label class="form-label">
            <?= $pago['nombre'] ?>
        </label>

        <input
            type="number"
            class="form-control valor_pago"
            data-id="<?= $pago['id_clase_pago'] ?>"
            value="<?= $pago['valor_pago'] ?>">

    </div>

<?php endforeach; ?>

</div>