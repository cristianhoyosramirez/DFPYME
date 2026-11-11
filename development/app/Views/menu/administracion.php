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
            <a href="#"
                class="card card-link card-link-pop h-100"
                data-bs-toggle="modal"
                data-bs-target="#modal_ip">
                <div class="card-body text-center">IP para factura electrónica</div>
            </a>
        </div>
        <!-- Impresoras -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="<?= base_url() ?>/impresora/listado" class="card card-link card-link-pop h-100">
                <div class="card-body text-center">Impresoras</div>
            </a>
        </div>

        <!-- Impriir nota producto en la factura  -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="#" class="card card-link card-link-pop h-100" onclick="modalImprimirNotaProducto()">
                <div class="card-body text-center">Imprimir nota producto</div>
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

        <!-- Asignación de usuario a venta -->
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a onclick="reinciarNumeracionPedidos()" class="card card-link card-link-pop h-100 cursor-pointer">
                <div class="card-body text-center">Reiniciar numeración de pedidos</div>
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


<!-- Modal -->
<div class="modal fade" id="modal_ip" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Asignar ip para la trasmisión de factura electrónica </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label for="" class="form-label">Ip para factura electrónica </label>
                        <input
                            type="text"
                            class="form-control"
                            id="ip_facturacion"
                            placeholder="Ej: 192.168.1.10"
                            inputmode="numeric"
                            maxlength="15" value="<?php  echo $ip; ?>">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" onclick="guardarIP()">Guardar </button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="imprimirNota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Configuración de impresión</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="imprimirNotasSelect" class="form-label">Imprimir notas de producto en factura:</label>
                    <select class="form-select" id="imprimirNotasSelect">
                        <option value="true" <?= ($imprimir === 't') ? 'selected' : '' ?>>Sí</option>
                        <option value="false" <?= ($imprimir === 'f') ? 'selected' : '' ?>>No</option>
                    </select>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarPreferencia()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    async function guardarIP() {
        const ip = document.getElementById('ip_facturacion').value.trim();



        try {
            const response = await fetch("<?= base_url('actualizacion/ip') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({
                    ip: ip
                })
            });

            const data = await response.json();

            if (data.status === "success") {
                sweet_alert_centrado('success', 'Registro actualizado')
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal_ip'));
                modal.hide();

            } else {
                alert("Error al guardar la IP");
            }

        } catch (error) {
            console.error("Error en la solicitud:", error);
            alert("Ocurrió un error al conectarse al servidor");
        }
    }
</script>

<script>
    // Cuando el modal se abre, poner foco en el input
    const modalIp = document.getElementById('modal_ip');
    modalIp.addEventListener('shown.bs.modal', function() {
        document.getElementById('ip_facturacion').focus();
    });
</script>

<script>
    function guardarPreferencia() {
        const valor = document.getElementById('imprimirNotasSelect').value;
        console.log("Imprimir notas de producto en factura:", valor);
        // Aquí puedes agregar la lógica para guardar la preferencia en tu base de datos
        // Por ejemplo, un fetch() o AJAX a tu backend
        $('#imprimirNota').modal('hide'); // Cierra el modal
    }
</script>


<script>
    function modalImprimirNotaProducto() {
        // Inicializar y mostrar el modal usando Bootstrap 5
        var myModal = new bootstrap.Modal(document.getElementById('imprimirNota'));
        myModal.show();
    }
</script>


<script>
    async function reinciarNumeracionPedidos() {
        const result = await Swal.fire({
            title: '¿Reiniciar numeración de pedidos?',
            text: 'Esta acción reiniciará la secuencia de pedidos. ¿Deseas continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, reiniciar',
            cancelButtonText: 'Cancelar',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-outline-success me-2',
                cancelButton: 'btn btn-outline-danger'
            }
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch("<?= base_url('actualizacion/reinciarSecuencia') ?>", {
                    method: 'GET'
                });

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Numeración reiniciada',
                        text: 'La secuencia de pedidos se ha reiniciado correctamente.',
                        confirmButtonText: 'Aceptar',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: 'btn btn-outline-success'
                        }
                    });
                } else {
                    throw new Error('Error al reiniciar la numeración.');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un problema al reiniciar la numeración.',
                    confirmButtonText: 'Aceptar',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-outline-danger'
                    }
                });
            }
        }
    }
</script>










<?= $this->endSection('content') ?>