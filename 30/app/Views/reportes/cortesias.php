<?php $session = session(); ?>
<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
Reporte de Ventas de Productos por Mesero
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!-- Select 2 -->

<!-- Jquery date picker  -->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">
<!-- Select 2 -->
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    #tablaCortesias tbody tr {
        cursor: pointer;
        transition: background-color 0.15s ease, box-shadow 0.15s ease;
    }

    /* Fila seleccionada */
    #tablaCortesias tbody tr.table-active {
        background-color: #e8f5e9 !important;
        color: #146c43;
        box-shadow: inset 4px 0 0 #198754;
    }

    /* Celdas de la fila seleccionada */
    #tablaCortesias tbody tr.table-active td {
        background-color: #e8f5e9 !important;
        color: #146c43 !important;
        font-weight: 500;
    }

    /* Efecto hover */
    #tablaCortesias tbody tr:hover:not(.table-active) {
        background-color: #f1f8f3;
    }
</style>


<div class="container">

    <p class="text-center text-primary  h4 ">Reporte de cortesias </p>

    <div class="card shadow-sm border-0">



        <div class="card-body">

            <div class="row g-2 mb-3 align-items-end">

                <!-- FILTRAR POR -->
                <div class="col-md-2">

                    <label class="form-label fw-semibold">
                        Filtrar por
                    </label>

                    <select class="form-select"
                        id="filtroFecha"
                        onchange="filtroFecha(this.value)">

                        <option value="tT">
                            Todos los tiempos
                        </option>

                        <option value="f">
                            Fecha
                        </option>

                        <option value="p">
                            Período
                        </option>

                        <option value="mC">
                            Movimiento de caja
                        </option>

                    </select>

                </div>


                <!-- FECHAS -->
                <div class="col-md-3"
                    id="contenedor_fechas">

                    <div id="criterio_fechas">

                        <?= $this->include('fechas/todos_los_tiempos') ?>

                    </div>

                </div>


                <!-- PRODUCTO -->
                <div class="col-md-2 d-none"
                    id="div_producto">

                    <label class="form-label fw-semibold">
                        Productos
                    </label>

                    <select id="id_producto"
                        class="form-select select2">

                        <option value=""></option>

                    </select>

                </div>


                <!-- ESPACIO -->
                <div class="col"></div>


                <!-- BOTONES -->
                <div class="col-md-auto">

                    <div class="d-flex gap-2">

                        <button type="button"
                            class="btn btn-outline-primary"
                            onclick="consultarReporteVentas()">

                            <i class="bi bi-search"></i>
                            Consultar

                        </button>

                        <button type="button"
                            class="btn btn-outline-success"
                            onclick="exportarExcel()">

                            <i class="bi bi-file-earmark-excel"></i>
                            Excel

                        </button>

                    </div>

                </div>

            </div>



            <div class="row g-3" style="height: calc(100vh - 270px);">

                <!-- COLUMNA IZQUIERDA -->
                <div class="col-md-7 h-100">

                    <div class="card h-100">

                        <div class="card-header fw-bold">
                            Cortesías
                        </div>

                        <div class="card-body p-0 d-flex flex-column">

                            <div class="d-flex flex-column"
                                style="height: calc(100vh - 320px);">

                                <!-- TABLA CON SCROLL -->
                                <div class="table-responsive flex-grow-1"
                                    style="overflow-y: auto; overflow-x: auto;">

                                    <table class="table table-striped table-hover mb-0 " id="tablaCortesias">

                                        <thead class="table-dark sticky-top">

                                            <tr>
                                                <td>Fecha</th>
                                                <td>Hora</th>
                                                <td>NIT</th>
                                                <td>Cliente</th>
                                                <td>Documento</th>
                                                <td>Valor</th>
                                                <td>Forma pago </th>

                                            </tr>

                                        </thead>

                                        <tbody id="reporte_cortesias">

                                            <?= $this->include('cortesias/cortesias') ?>

                                        </tbody>

                                    </table>

                                </div>


                                <!-- TOTAL SIEMPRE VISIBLE -->
                                <div class="table-responsive flex-shrink-0">

                                    <table class="table table-dark fw-bold mb-0"  >

                                        <tfoot class="table-dark fw-bold">

                                            <tr>

                                                <td colspan="4" class="text-end">
                                                    TOTAL REGISTROS
                                                </td>

                                                <td class="text-center" id="total_registros">
                                                    <?= $total_registros; ?>
                                                </td>

                                                <td class="text-end">
                                                    TOTAL GENERAL
                                                </td>

                                                <td class="text-end" id="total_ventas">
                                                    <?= $total; ?>
                                                </td>

                                            </tr>

                                        </tfoot>
                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- COLUMNA DERECHA -->
                <div class="col-md-5 h-100">

                    <div class="card h-100">

                        <div class="card-header fw-bold">
                            Detalle de cortesía
                        </div>

                        <div class="card-body p-0 d-flex flex-column">

                            <div id="detalle_cortesia"
                                class="flex-grow-1"
                                style="overflow-y: auto;">

                                <div class="text-center text-muted py-5">

                                    <i class="bi bi-receipt fs-1"></i>

                                    <p class="mt-3 mb-0">
                                        Seleccione una cortesía para ver el detalle
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>

</div>
<input type="text" id="url" value="<?= base_url() ?>" hidden>

<!-- Modal -->
<div class="modal fade" id="modalAperturas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Movimientos de caja </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="movimientosCaja"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar </button>

            </div>
        </div>
    </div>
</div>
<input type="text" value="<?= base_url() ?>" id="url" hidden>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>

<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>

<!--jQuery -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>

<!--select2 -->
<script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>

<script>
    async function exportarExcel() {

        const fechaInicial = document.getElementById('fecha_inicial')?.value || '';
        const fechaFinal = document.getElementById('fecha_final')?.value || fechaInicial;
        const idApertura = document.getElementById('id_apertura_seleccionada')?.value || '';
        const baseUrl = document.getElementById('url')?.value || '';

       

        const formData = new FormData();

        formData.append('fecha_inicial', fechaInicial);
        formData.append('fecha_final', fechaFinal);
        formData.append('id_apertura', idApertura);

        Swal.fire({
            title: 'Generando Excel...',
            text: 'Por favor espera un momento',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {

            const response = await fetch(
                baseUrl + '/factura_directa/reporteCortesias', {
                    method: 'POST',
                    body: formData
                }
            );

            if (!response.ok) {
                throw new Error(
                    `Error HTTP: ${response.status}`
                );
            }

            // Convertir respuesta a archivo
            const blob = await response.blob();

            // Crear URL temporal
            const blobUrl = window.URL.createObjectURL(blob);

            // Crear enlace de descarga
            const enlace = document.createElement('a');

            enlace.href = blobUrl;
            enlace.download =
                `Reporte_Cortesias_${fechaInicial}_${fechaFinal}.xlsx`;

            document.body.appendChild(enlace);

            enlace.click();

            // Limpiar
            enlace.remove();
            window.URL.revokeObjectURL(blobUrl);

            Swal.close();

            Swal.fire({
                icon: 'success',
                title: 'Excel generado',
                text: 'El reporte se descargó correctamente.',
                timer: 1800,
                showConfirmButton: false
            });

        } catch (error) {

            console.error('Error al generar Excel:', error);

            Swal.close();

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible generar el reporte de Excel.'
            });
        }
    }
</script>


<script>
    async function verDetalle(id_factura) {

        const base_url = "<?= base_url() ?>/";

        try {

            // Quitar selección anterior
            document.querySelectorAll('#tablaCortesias tbody tr')
                .forEach(fila => {
                    fila.classList.remove('table-active');
                });

            // Marcar fila seleccionada
            const filaActual = document.getElementById(`fila${id_factura}`);

            if (filaActual) {
                filaActual.classList.add('table-active');
            }

            Swal.fire({
                title: 'Cargando detalle...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();

            formData.append('id_factura', id_factura);

            const response = await fetch(
                `${base_url}reportes/verDetalle`, {
                    method: 'POST',
                    body: formData
                }
            );

            if (!response.ok) {
                throw new Error('Error al consultar el detalle');
            }

            const data = await response.json();

            document.getElementById('detalle_cortesia').innerHTML =
                data.documento;

            Swal.close();

        } catch (error) {

            console.error(error);

            Swal.close();

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No fue posible cargar el detalle de la cortesía.'
            });
        }
    }
</script>



<script>
    async function consultarReporteVentas() {

        Swal.fire({
            title: 'Consultando reporte...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {

            /* const fecha_inicial = document.getElementById('fecha_inicial').value;
            //const fecha_final = document.getElementById('fecha_final').value;
            const fecha_final = document.getElementById('fecha_final')?.value || ''; */

            const fecha_inicial = document.getElementById('fecha_inicial')?.value || '';
            const fecha_final = document.getElementById('fecha_final')?.value || fecha_inicial;

            const url = document.getElementById('url').value;

            const id_apertura = document.getElementById('id_apertura_seleccionada')?.value || '';

            const formData = new FormData();

            formData.append('fecha_inicial', fecha_inicial);
            formData.append('fecha_final', fecha_final);
            formData.append('id_apertura', id_apertura);

            const response = await fetch(url + '/empresa/reporteCortesias', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {

                let mensaje = 'Error al consultar el reporte';

                try {
                    mensaje = await response.text();
                } catch (e) {}

                throw new Error(mensaje);
            }

            const resultado = await response.json();



            Swal.close();

            if (!resultado.response) {
                throw new Error(resultado.mensaje || 'Ocurrió un error al generar el reporte.');
            }

            document.getElementById('total_registros').innerHTML = resultado.total_registros
            document.getElementById('total_ventas').innerHTML = resultado.total
            document.getElementById('reporte_cortesias').innerHTML = resultado.cortesias

        } catch (error) {

            console.error(error);

            Swal.close();

            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `<div style="text-align:left;">${error.message}</div>`
            });

        }

    }
</script>




<script>
    function seleccionarApertura(
        idApertura,
        fechaApertura,
        horaApertura,
        fechaCierre,
        horaCierre
    ) {

        fechaCierre = fechaCierre || 'Caja abierta';
        horaCierre = horaCierre || '';

        const html = `
<div class="row g-3">

    <div class="col-md-6">
        <label class="form-label fw-semibold text-success">
            Apertura
        </label>
        <input type="text"
               class="form-control"
               value="${fechaApertura} ${horaApertura}"
               readonly>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold text-danger">
            Cierre
        </label>
        <input type="text"
               class="form-control"
               value="${fechaCierre} ${horaCierre}"
               readonly>
    </div>

</div>

<input type="hidden"
       id="id_apertura_seleccionada"
       value="${idApertura}">
`;

        document.getElementById('contenedor_fechas').classList.remove('d-none');
        const contenedor = document.getElementById('criterio_fechas');

        //if (contenedor) {
        contenedor.innerHTML = html;
        //}

        const modalElement = document.getElementById('modalAperturas');

        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }
    }
</script>

<script>
    async function filtroFecha(valor) {


        try {


            const response = await fetch("<?= base_url('empresa/filtro_fecha') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    filtro: valor,

                })
            });

            const data = await response.json();

            if (data.response) {

                if (data.abreModal) {



                    document.getElementById('movimientosCaja').innerHTML = data.fecha;
                    // Abrir modal
                    $('#modalAperturas').modal('show');

                } else {

                    document.getElementById('contenedor_fechas').classList.remove('d-none');
                    document.getElementById('criterio_fechas').innerHTML = data.fecha;

                }
            }
        } catch (error) {
            alert("Error en la validación de resolución.");
            console.error(error);
        }
    }
</script>




<script>
    $('#filtroFecha').select2({
        theme: 'bootstrap-5',
        placeholder: 'Criterio de fechas',
        allowClear: true,
        width: '100%'
    });

    $('#filtroFecha').on('change', function() {

        if ($(this).val() === null || $(this).val() === '') {
            $('#contenedor_fechas').addClass('d-none');
        } else {
            $('#contenedor_fechas').removeClass('d-none');
        }

    });
</script>































<?= $this->endSection('content') ?>