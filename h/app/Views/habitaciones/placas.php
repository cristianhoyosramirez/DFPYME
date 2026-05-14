<style>
/* Contenedor dropdown */
#idMunicipios {
    position: absolute;
    top: 100%;
    left: 0;

    width: 100%;

    background: #fff;
    border: 1px solid #ced4da;
    border-radius: 6px;

    max-height: 220px;
    overflow-y: auto;

    margin: 0;
    padding: 4px 0;
    list-style: none;

    box-shadow: 0 4px 12px rgba(0,0,0,0.12);

    font-size: 14px;
    z-index: 9999;

    box-sizing: border-box;
}

/* Items */
#idMunicipios li {
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.2s ease;

    display: flex;
    justify-content: space-between;
    gap: 10px;
}

/* Hover estilo Select2 */
#idMunicipios li:hover {
    background-color: #0d6efd;
    color: #fff;
}

/* Línea separadora */
#idMunicipios li:not(:last-child) {
    border-bottom: 1px solid #f1f1f1;
}

/* Opcional: texto secundario */
#idMunicipios li span {
    opacity: 0.7;
    font-size: 12px;
}
</style>
<div class="select2-wrapper" style="position:relative; width:100%;">

<ul id="idMunicipios" class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content">

    <?php foreach ($placas as $placa): ?>
        <li onclick="seleccionarPlaca(
            <?= $placa['id'] ?>,
            '<?= htmlspecialchars($placa['tipo'] . ' - ' . $placa['placa'], ENT_QUOTES) ?>'
        )">

            <?= htmlspecialchars($placa['tipo'] . ' - ' . $placa['placa'], ENT_QUOTES) ?>

        </li>
    <?php endforeach; ?>

</ul>

</div>