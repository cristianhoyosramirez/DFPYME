<style>
    /* Contenedor principal tipo Select2 */
    #idMunicipios {
        position: absolute;
        width: 100%;
        max-height: 260px;
        overflow-y: auto;

        background: #fff;
        border: 1px solid #aaa;
        border-radius: 6px;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin: 0;
        padding: 4px 0;

        list-style: none;
        z-index: 9999;
    }

    /* Items */
    #idMunicipios li {
        padding: 8px 12px;
        cursor: pointer;
        font-size: 14px;
        color: #333;

        transition: background 0.2s ease, color 0.2s ease;
    }

    /* Hover estilo Select2 */
    #idMunicipios li:hover {
        background-color: #5897fb;
        color: #fff;
    }

    /* Scroll bonito */
    #idMunicipios::-webkit-scrollbar {
        width: 6px;
    }

    #idMunicipios::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    #idMunicipios::-webkit-scrollbar-thumb:hover {
        background: #999;
    }

    /* Opcional: estado activo (si quieres marcar selección) */
    #idMunicipios li.active {
        background-color: #3875d7;
        color: #fff;
    }
</style>

<ul id="idMunicipios" class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content">
    <?php foreach ($ciudades as $ciudad): ?>
        <li onclick="seleccionarCiudadProcedencia(<?= $ciudad['id'] ?>, '<?= $ciudad['nombre'] ?>')">
            <?= htmlspecialchars($ciudad['nombre'], ENT_QUOTES) ?>
        </li>
    <?php endforeach; ?>
</ul>