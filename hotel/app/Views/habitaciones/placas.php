<ul id="idMunicipios" class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content">
    <?php foreach ($placas as $placa): ?>
        <li onclick="seleccionarPlaca(<?= $placa['id'] ?>, '<?= htmlspecialchars($placa['tipo'], ENT_QUOTES) ?>')">
            <?= htmlspecialchars($placa['tipo'] . ' - ' . $placa['placa'], ENT_QUOTES) ?>
        </li>
    <?php endforeach; ?>
</ul>