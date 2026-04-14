<style id="select2style">
/* Contenedor dropdown */
#miListaClientes {
    position: absolute;
    top: 100%;
    left: 0;

    width: 100%; /* 👈 clave para que no ocupe toda la pantalla */
    
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
#miListaClientes li {
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

/* Hover tipo Select2 */
#miListaClientes li:hover {
    background-color: #0d6efd;
    color: #fff;
}

/* Item activo */
#miListaClientes li.active {
    background-color: #0d6efd;
    color: #fff;
}

/* Separador entre items */
#miListaClientes li:not(:last-child) {
    border-bottom: 1px solid #f1f1f1;
}
</style>


<ul id="miListaClientes" class="custom-select2-list">
    <?php foreach ($clientes as $cliente): ?>
        <li
            onclick="seleccionarCliente(<?= $cliente['id'] ?>, '<?= addslashes($cliente['nitcliente'] . '/' . $cliente['nombrescliente']) ?>')">
            <strong><?= $cliente['nitcliente'] ?></strong><br>
            <small><?= $cliente['nombrescliente'] ?></small>
        </li>
    <?php endforeach; ?>
</ul>