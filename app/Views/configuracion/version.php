<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
VERSIÓN
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="text-center mb-4">
        <p class="h4 text-primary">Versión actual: <?php echo $version; ?></p>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-dark text-white">
            <strong>Historial de versiones</strong>
        </div>
        <div class="card-body">

            <div class="mb-4">
                <h5 class="text-secondary">Versión [1.1.2] - 2025-05-17</h5>
                <h6 class="text-muted">Generalidades:</h6>
                <ul>
                    <li>Se corrigió la consulta de ventas entre fechas, ya que al usar la vista correspondiente, los datos se distorsionaban.</li>
                    <li>Se implementó un módulo completo para la impresión de comandas por grupos, que incluye:
                        <ul>
                            <li>Creación de grupos de impresión.</li>
                            <li>Asignación de impresoras a grupos.</li>
                            <li>Edición de nombres de grupos e impresoras asignadas.</li>
                            <li>Eliminación de grupos.</li>
                        </ul>
                    </li>
                    <li>Se habilitó la asignación de grupos a productos para que se impriman correctamente en las comandas.</li>
                    <li>Se agregó la generación de consecutivos para el número del informe fiscal, incorporando un nuevo campo en la tabla de consecutivos desde el cual se tomará el número a partir de esta versión.</li>
                </ul>
            </div>


            <div class="mb-4">
                <h5 class="text-secondary">Versión [1.1.1] - 2025-05-07</h5>
                <h6 class="text-muted">Correcciones:</h6>
                <ul>
                    <li>Se corrigió el reporte de costo de productos, ya que anteriormente, si en una fecha no se registraban ventas, el informe no se generaba. Ahora, el sistema toma el rango de productos según los <strong>Según fechas válidas </strong>, permitiendo identificar correctamente los días sin ventas y generando un reporte más preciso.</li>
                </ul>
            </div>

            <div class="mb-4">
                <h5 class="text-secondary">Versión [1.0.0] - 2025-05-05</h5>
                <h6 class="text-muted">Características iniciales:</h6>
                <ul>
                    <li>Primera versión funcional del sistema.</li>
                    <li>Control básico de inventario.</li>
                    <li>Generación de reportes estándar.</li>
                </ul>
            </div>
        </div>
    </div>
</div>




<?= $this->endSection('content') ?>