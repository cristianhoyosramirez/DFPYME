<div class="container">
    <div class="row g-3"> <!-- Clase g-3 para separación entre columnas -->
        <?php foreach ($mesas as $detalle): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3"> <!-- Tamaños ajustados a los breakpoints -->
                <a href="#" class="card card-link card-link-pop bg-red-lt text-white"> <!-- Color rojo y texto blanco -->
                    <div class="card-body">
                        <p><?php echo $detalle['nombre']; ?></p>
                        <p><?php echo number_format($detalle['valor_total'], 0, ',', '.'); ?></p>
                        <p><?php echo $detalle['usuario']; ?></p>
                        <p><?php echo $detalle['nota_pedido']; ?></p>
                        <p><?php
                            setlocale(LC_TIME, 'es_ES.UTF-8'); // Asegurar que está en español

                            $datetime = new DateTime($detalle['fecha']);
                            $dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
                            $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

                            $diaSemana = $dias[$datetime->format('w')];
                            $dia = $datetime->format('j');
                            $mes = $meses[$datetime->format('n') - 1];
                            $hora = $datetime->format('g:i');
                            $meridiano = $datetime->format('a') == 'am' ? 'a.m.' : 'p.m.';

                            echo "$diaSemana $dia de $mes $hora $meridiano";
                            ?>
                        </p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>