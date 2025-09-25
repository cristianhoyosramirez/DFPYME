<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
HOME
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>


<?php $user_session = session(); ?>


<div class="container my-4">
    <p class="text-center h3 text-primary mb-3">Menú de configuración y administración del sistema </p>
    <div class="row g-3">


        <!-- Gestión de factura electrónica  -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/edicion_eliminacion_factura_pedido/gestionFe" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Caja </div>
            </a>
        </div>


        <!-- Comanda -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/comanda" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Comanda</div>
            </a>
        </div>

        <!-- Comprobante de Transferencia Electrónica -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/empresa/comprobante_transaccion" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Comprobante de Transferencia Electrónica</div>
            </a>
        </div>

        <!-- Configuración Bono -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/configuracionBono" class="card card-link card-link-pop h-100">

                <div class="card-body text-center">Configuración Bono</div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/edicion_eliminacion_factura_pedido/confOrdenPedido" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">
                    <!-- Download SVG icon from http://tabler-icons.io/i/list -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <line x1="9" y1="6" x2="20" y2="6" />
                        <line x1="9" y1="12" x2="20" y2="12" />
                        <line x1="9" y1="18" x2="20" y2="18" />
                        <line x1="5" y1="6" x2="5" y2="6.01" />
                        <line x1="5" y1="12" x2="5" y2="12.01" />
                        <line x1="5" y1="18" x2="5" y2="18.01" />
                    </svg>
                    <i class="bi bi-printer-fill fs-2 text-primary mb-2"></i>
                    <div>Conf. Imp. Orden pedido</div>
                </div>
            </a>
        </div>



        <!-- Consecutivos -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/empresa/consecutivos" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Consecutivos</div>
            </a>
        </div>

        <!-- Cuentas de Retiro -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/devolucion/listado" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Cuentas de Retiro</div>
            </a>
        </div>

        <!-- Empresa -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/empresa/datos" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Empresa</div>
            </a>
        </div>

        <!-- Encabezado y Pie de Factura -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/encabezado" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Encabezado y Pie de Factura</div>
            </a>
        </div>


        <!-- Gestión -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/edicion_eliminacion_factura_pedido/facturacion" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Facturación</div>
            </a>
        </div>
        <!-- Gestión -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/borrado_masivo" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Gestión</div>
            </a>
        </div>

        <!-- Gestión de Pedidos -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/actualizacion/parametrizacion" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Gestión de Pedidos</div>
            </a>
        </div>

        <!-- Impresoras -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/impresora/listado" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Impresoras</div>
            </a>
        </div>

        <!-- Mesas -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/mesas/list" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Mesas</div>
            </a>
        </div>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/edicion_eliminacion_factura_pedido/menuPedidosWhatsapp" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Pedidos whatsapp</div>
            </a>
        </div>


        <!-- Cálculo Propina -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/propina" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Propina</div>
            </a>
        </div>



        <!-- Productos con Impuestos -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/productos_impuestos" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Productos con Impuestos</div>
            </a>
        </div>


        <!-- Productos Favoritos -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/productos_favoritos" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Productos Favoritos</div>
            </a>
        </div>

        <!-- Asignación de usuario a venta -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/mesero" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">¿Requerir mesero en pedido?</div>
            </a>
        </div>

        <!-- Resolución Facturación Electrónica -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/empresa/resolucion_electronica" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Resolución Facturación Electrónica</div>
            </a>
        </div>

        <!-- Resolución Facturación POS -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/empresa/resolucion_facturacion" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Resolución Facturación POS</div>
            </a>
        </div>

        <!-- Salones -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/salones/list" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Salones</div>
            </a>
        </div>

        <!-- Sincronización -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/actualizacion/actualizaciones" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Sincronización</div>
            </a>
        </div>

        <!-- Tipos de Factura -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/tipos_de_factura" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Tipos de Factura</div>
            </a>
        </div>

        <!-- Tipos de Inventario -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/tipos_inventario" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Tipos de Inventario</div>
            </a>
        </div>

        <!-- Usuarios -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/usuarios/list" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Usuarios</div>
            </a>
        </div>

        <!-- Venta Múltiple -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/eventos/venta_multiple" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Venta Múltiple</div>
            </a>
        </div>

        <!-- Versión -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/configuracion/version" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Versión</div>
            </a>
        </div>

    </div>
</div>










<?= $this->endSection('content') ?>