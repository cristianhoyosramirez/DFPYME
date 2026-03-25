<ul id="idMunicipios" class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content">
    <?php foreach ($ciudades as $ciudad): ?>
        <li onclick="seleccionarCiudadProcedencia(<?= $ciudad['id'] ?>, '<?= $ciudad['nombre'] ?>')">
            <?= htmlspecialchars($ciudad['nombre'], ENT_QUOTES) ?>
        </li>
    <?php endforeach; ?>
</ul>