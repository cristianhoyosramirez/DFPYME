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
                <h5 class="text-secondary">Versión [1.1.3] - 29 de mayo de 202</h5>
                <h6 class="text-muted">Generalidades:</h6>
                <ul>
                    <li>Se ajustó el tamaño de impresión de la comanda para mejorar su presentación.</li>
                   
                    <li>En la carga de insumos, ahora se registra automáticamente el inventario con tipo o ID 1.</li>
                    <li>En la interfaz de toma de pedidos, los campos en el menú de tres puntos ahora se mantienen visibles para evitar confusiones.</li>
                    <li>Se eliminó el botón "Abrir cajón" en el perfil de usuario de toma de pedidos.</li>
                    <li>Se eliminó el botón "Abrir cajón" en el perfil de usuario de toma de pedidos.</li>
                    <li>En el módulo de administración, al eliminar productos se refresca correctamente la vista y se refleja la eliminación.</li>
                    <li>Optimización del script de eliminación de base de datos.</li>
                    <li>Inserción automática del tipo de empresa con ID 3, utilizado para la edición de precios.</li>
                    <li>En la configuración de productos, se recuperan nuevamente las recetas al presionar la tecla Enter y se agregó un botón para restablecerlas manualmente.</li>
                    <li>Simplificación del proceso de creación de clientes.</li>
                    <li> Se corrigió el proceso de eliminación de atributos: anteriormente era necesario ejecutar dos borrados, ya que primero se eliminaban los componentes asociados y luego el atributo. Ahora, el proceso se realiza correctamente en una sola operación.</li>
                    <li>Se permite asignar un mismo componente a múltiples atributos</li>
                    <li>Se añadió un reporte de ventas por categoría, agrupado desde la consulta de caja exportable a excel .</li>
                    <li>En la impresión de la factura electrónica, ahora se incluyen también los datos de la mesa y del mesero.</li>
                    <li>Se mejoró la interfaz de toma de pedidos en dispositivos móviles, optimizando su usabilidad.</li>
                    <li>Las cortesías ahora se registran con un valor simbólico de un peso.</li>
                    <li>Se mejoró la interfaz de toma de pedidos en dispositivos móviles, optimizando su usabilidad.</li>
                    <li> Se ajustó el tamaño de impresión de la comanda para una mejor presentación.</li>
                </ul>
            </div>

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