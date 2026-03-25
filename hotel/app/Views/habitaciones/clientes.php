<ul id="miLista" class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" tabindex="0">
    <?php foreach($clientes as $cliente): ?>
        <li 
            class="ui-menu-item" 
            tabindex="-1" 
            onclick="seleccionarCliente(<?= $cliente['id'] ?>, '<?= addslashes($cliente['nitcliente'].'/'.$cliente['nombrescliente']) ?>')">
            <?= $cliente['nitcliente'].'/'.$cliente['nombrescliente'] ?>
        </li>
    <?php endforeach; ?>
</ul>