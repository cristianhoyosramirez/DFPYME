<style id="select2style">
/* Contenedor padre (IMPORTANTE para posicionamiento tipo Select2) */
.select2-wrapper {
    position: relative;
    width: 100%;
}

/* Dropdown */
#idMunicipios {
    position: absolute;
    top: 100%;
    left: 0;

    width: 100%;

    border: 1px solid #ced4da;
    border-radius: 6px;

    max-height: 200px;
    overflow-y: auto;

    background: #fff;
    padding: 4px 0;

    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    font-size: 14px;

    margin: 0;
    list-style: none;

    z-index: 9999;
    box-sizing: border-box;
}

/* Items */
#idMunicipios li {
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

/* Hover estilo Select2 */
#idMunicipios li:hover {
    background-color: #0d6efd;
    color: #fff;
}

/* Activo */
#idMunicipios li.active {
    background-color: #0d6efd;
    color: #fff;
}

/* Separador */
#idMunicipios li:not(:last-child) {
    border-bottom: 1px solid #f1f1f1;
}
</style>

<div class="select2-wrapper">

    <!-- INPUT (puedes usarlo para búsqueda si quieres después) -->
    <input type="text" id="buscarCiudad" class="form-control" placeholder="Buscar ciudad...">

    <!-- LISTA -->
    <ul id="idMunicipios" class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content">

        <?php foreach ($ciudades as $ciudad): ?>
            <li onclick="seleccionarCiudadDestino(
                <?= $ciudad['id'] ?>,
                '<?= htmlspecialchars($ciudad['nombre'], ENT_QUOTES) ?>'
            )">
                <?= htmlspecialchars($ciudad['nombre'], ENT_QUOTES) ?>
            </li>
        <?php endforeach; ?>

    </ul>

</div>