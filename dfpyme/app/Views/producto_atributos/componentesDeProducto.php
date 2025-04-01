<?php

foreach ($atributos as $keyAtributo):
    $componente = model('componentesAtributosProductoModel')->select('nombre')->where('id', $keyAtributo['id_componente'])->first();
?>

    <button type="button" class="btn btn-success rounded-pill position-relative">
        <?php echo ($componente['nombre']); ?>
        <span class="badge rounded-pill bg-success">

            <span class="badge rounded-pill bg-success" onclick="eliminacionComponente(<?php echo $keyComponente['id'] ?>)">
                <!-- Download SVG icon from http://tabler-icons.io/i/x -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </span>

        </span>
    </button>

<?php endforeach ?>