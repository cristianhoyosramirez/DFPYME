<ul class="horizontal-list">
    <ul class="horizontal-list">
        <?php
        $notaPedidoConfig = model('configuracionPedidoModel')
            ->select('notaPedido')
            ->first();

        foreach ($mesas as $detalle):

            $pedidoData = model('pedidoModel')->pedido_mesa($detalle['id']);
            $pedido = $pedidoData[0] ?? null;

            $isLibre = empty($pedido);

            // Estados visuales
            $cardClass = $isLibre ? 'bg-green-lt' : 'bg-red-lt';
            $imgSrc    = $isLibre ? 'libre.png' : 'ocupada.png';

            // Acciones
            $onclickFunction = $isLibre
                ? "pedido('{$detalle['id']}','{$detalle['nombre']}')"
                : "pedido_mesa('{$detalle['id']}','{$detalle['nombre']}')";

            // Datos del pedido (seguros)
            $notaPedido = $pedido['nota_pedido'] ?? '';
            $usuario    = $pedido['nombresusuario_sistema'] ?? '';
            $valor      = ($pedido['valor_total'] ?? 0) + ($pedido['propina'] ?? 0);

            // Tooltip seguro
            $tooltip = !$isLibre
                ? htmlspecialchars($notaPedido, ENT_QUOTES, 'UTF-8')
                : '';

            // Valor formateado
            $valorTotal = !$isLibre
                ? "$" . number_format($valor, 0, ",", ".")
                : '';

            // Nota mostrada
            if (!$isLibre) {
                $textoBase = ($notaPedidoConfig['notaPedido'] === 't') ? $notaPedido : $usuario;
                $notaMostrada = substr($textoBase, 0, 10) . '...';
            } else {
                $notaMostrada = '';
            }
        ?>
            <li>
                <div id="UpdateMesa<?= $detalle['id'] ?>">
                    <div id="mesa<?= $detalle['id'] ?>"
                        class="cursor-pointer card card_mesas text-white <?= $cardClass ?>"
                        onclick="<?= $onclickFunction ?>"
                        <?= !$isLibre ? "data-bs-toggle='tooltip' data-bs-placement='bottom' title='{$tooltip}'" : "" ?>
                        style="height: auto;">

                        <div class="row">
                            <!-- Imagen -->
                            <div class="col-3">
                                <span class="avatar">
                                    <img src="<?= base_url(); ?>/Assets/img/<?= $imgSrc ?>"
                                        width="110" height="32"
                                        alt="Mesa"
                                        class="navbar-brand-image">
                                </span>
                            </div>

                            <!-- Información -->
                            <div class="col">
                                <div class="text-center">
                                    <strong style="font-size: 12px;">
                                        <?= htmlspecialchars($detalle['nombre'], ENT_QUOTES, 'UTF-8') ?>
                                    </strong>
                                </div>

                                <?php if (!$isLibre): ?>
                                    <div class="text-center">
                                        <strong style="font-size: 12px;">
                                            <?= $valorTotal ?>
                                        </strong>
                                    </div>

                                    <div class="text-center">
                                        <strong style="font-size: 12px; height: 1em; overflow: hidden;">
                                            <?= htmlspecialchars($notaMostrada, ENT_QUOTES, 'UTF-8') ?>
                                        </strong>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</ul>