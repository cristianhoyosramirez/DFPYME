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


<div class="container">

    <p class="text-center text-primary  h4 ">Reporte de ventas por producto</p>

    <div class="card shadow-sm border-0">



        <div class="card-body">

            <div class="row g-2 mb-3">

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Filtrar por</label>
                    <select class="form-select" id="filtroFecha" onchange="filtroFecha(this.value)">
                        
                        <option value="tT">Todos los tiempos</option>
                        <option value="f">Fecha</option>
                        <option value="p">Período</option>
                        <option value="mC">Movimiento de caja</option>
                    </select>
                </div>

                <div class="col-md-3 " id="contenedor_fechas">
                    <div id="criterio_fechas">
                        <?= $this->include('fechas/todos_los_tiempos') ?>
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Agrupar por</label>
                    <select id="agrupar_por" class="form-select select2" onchange="agruparPor(this.value)">
                        <option></option>
                        <option value="categoria">Categoría</option>
                        <option value="producto">Producto</option>
                    </select>
                </div>

                <div class="col-md-2 d-none" id="div_categoria">
                    <label class="form-label fw-semibold">Categorías</label>
                    <select id="id_de_categoria" class="form-select select2">
                        <option value=""></option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['id'] ?>">
                                <?= $categoria['nombrecategoria'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="col-md-2 d-none " id="div_producto">
                    <label class="form-label fw-semibold">Productos</label>
                    <select id="id_producto" class="form-select select2">
                        <option value=""></option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?= $producto['id'] ?>">
                                <?= $producto['nombreproducto'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Usuario</label>
                    <select class="form-select" id="id_usuario_mesero">
                        <option></option>

                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?= $usuario['id_usuario'] ?>">
                                <?= $usuario['nombresusuario_sistema'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <button class="btn btn-outline-primary me-2" onclick="consultarReporteVentas()">
                        Consultar
                    </button>

                    <button class="btn btn-outline-success">
                        Excel
                    </button>
                </div>
            </div>

            <div class="table-responsive"
                style="max-height: calc(100vh - 300px); overflow-y: auto;">

                <table class="table table-striped table-hover mb-0">

                    <thead class="table-dark sticky-top">
                        <tr>
                            <td>Producto</th>
                            <td>Usuario</th>
                            <td class="text-end">Cantidad</th>
                            <td class="text-end">Valor Unitario</th>
                            <td class="text-end">Valor Total</th>
                        </tr>
                    </thead>

                    <tbody id="ventas_de_mesero">
                        <?= $this->include('fechas/ventas_mesero') ?>
                    </tbody>

                    <tfoot class="table-dark fw-bold sticky-footer">
                        <tr>
                            <td colspan="4">TOTAL GENERAL</td>
                            <td class="text-end" id="total_ventas">
                                <?= number_format($total_ventas, 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tfoot>

                </table>

            </div>

            <style>
                .sticky-footer {
                    position: sticky;
                    bottom: 0;
                    z-index: 10;
                    background-color: #212529 !important;
                    /* table-dark */
                }

                .sticky-footer td {
                    background-color: #212529 !important;
                    color: #fff !important;
                }
            </style>

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

            const codigo_producto = document.getElementById('id_producto')?.value || '';
            const id_categoria = document.getElementById('id_de_categoria')?.value || '';
            const id_usuario = document.getElementById('id_usuario_mesero')?.value || '';
            const agrupar_por = document.getElementById('agrupar_por')?.value || '';
            const id_apertura = document.getElementById('id_apertura_seleccionada')?.value || '';

            const formData = new FormData();

            formData.append('fecha_inicial', fecha_inicial);
            formData.append('fecha_final', fecha_final);
            formData.append('codigo_producto', codigo_producto);
            formData.append('id_categoria', id_categoria);
            formData.append('id_usuario', id_usuario);
            formData.append('agrupar_por', agrupar_por);
            formData.append('id_apertura', id_apertura);

            const response = await fetch(url + '/empresa/reporteVentasKardex', {
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

            document.getElementById('ventas_de_mesero').innerHTML = resultado.ventas;
            document.getElementById('total_ventas').innerHTML = resultado.total_ventas;

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
    function agruparPor(valor) {

        const divCategoria = document.getElementById('div_categoria');
        const divProducto = document.getElementById('div_producto');

        // Ocultar ambos
        divCategoria.classList.add('d-none');
        divProducto.classList.add('d-none');

        // Mostrar según selección
        if (valor === 'categoria') {
            divCategoria.classList.remove('d-none');
        }

        if (valor === 'producto') {
            divProducto.classList.remove('d-none');
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
    $('#id_de_categoria').select2({
        theme: 'bootstrap-5',
        placeholder: 'Categoria o producto',
        allowClear: true,
        width: '100%'
    });
</script>

<script>
    $('#agrupar_por').select2({
        theme: 'bootstrap-5',
        placeholder: 'Buscar por categoria o producto',
        allowClear: true,
        width: '100%'
    });
</script>

<script>
    $('#id_producto').select2({
        theme: 'bootstrap-5',
        placeholder: 'Seleccionar un producto',
        allowClear: true,
        width: '100%'
    });
</script>

<script>
    $('#id_de_categoria').select2({
        theme: 'bootstrap-5',
        placeholder: 'Seleccionar una categoria ',
        allowClear: true,
        width: '100%'
    });
</script>


<script>
    $('#id_usuario_mesero').select2({
        theme: 'bootstrap-5',
        placeholder: 'Seleccione un usuario ',
        allowClear: true,
        width: '100%'
    });
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