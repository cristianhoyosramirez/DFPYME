<!doctype html>
<html lang="es">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1, viewport-fit=cover">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>
        <?= $this->renderSection('title') ?> DFPYME
    </title>

    <!-- ===========================================================
        Favicon
    ============================================================ -->

    <link rel="shortcut icon"
        href="<?= base_url('Assets/img/favicon.png') ?>">

    <!-- ===========================================================
        TABLER
    ============================================================ -->

    <link rel="stylesheet"
        href="<?= base_url('Assets/css/tabler.min.css') ?>">

    <!-- ===========================================================
        SELECT2
    ============================================================ -->

    <link rel="stylesheet"
        href="<?= base_url('Assets/plugin/select2/select2-bootstrap-5-theme.min.css') ?>">

    <!-- ===========================================================
        JQuery UI
    ============================================================ -->

    <link rel="stylesheet"
        href="<?= base_url('Assets/plugin/jquery-ui/jquery-ui.css') ?>">

    <?= $this->renderSection('styles') ?>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        body {
            background: #f5f7fb;
        }

        .wrapper {
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .page-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .page-body {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        .container-principal {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: .8rem;
        }

        .card-principal {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .card-principal>.card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 1rem;
        }

        .filtros,
        .indicadores,
        .total {
            flex-shrink: 0;
        }

        .tabla-creditos {
            flex: 1;
            overflow: auto;
            border: 1px solid #dee2e6;
            border-radius: .5rem;
            margin-top: 20px;
            /* <-- aumenta este valor */
        }

        .tabla-creditos table {
            margin-bottom: 0;
        }

        .tabla-creditos thead th {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #182433;
            color: #fff;
            white-space: nowrap;
        }

        .tabla-creditos tbody td {
            white-space: nowrap;
            vertical-align: middle;
        }

        .indicadores .card {
            height: 100%;
        }

        @media (max-width:768px) {

            .container-principal {
                padding: .5rem;
            }

            .card-principal>.card-body {
                padding: .75rem;
            }

            .tabla-creditos {
                font-size: .85rem;
            }

        }
    </style>

</head>

<body>

    <div class="wrapper">

        <!-- HEADER -->
        <?= $this->include('layout/header_mesas') ?>

        <div class="page-wrapper">

            <div class="page-body">


                <div class="container-principal">

                    <div class="card shadow-sm card-principal">

                        <div class="card-header">

                            <h2 class="card-title mb-0">
                                Consulta de Créditos Vigentes
                            </h2>

                        </div>

                        <div class="card-body">


                            <form action="<?= base_url('cartera/excel') ?>" method="POST">

                                <div class="row g-3 align-items-end filtros mb-3">

                                    <!-- Número de factura -->
                                    <div class="col-xl-2 col-lg-2 col-md-3">
                                        <label class="form-label">Número de factura</label>

                                        <input type="hidden" id="id_cliente" name="id_cliente">

                                        <div class="input-group">
                                            <input
                                                type="text"
                                                id="documento"
                                                class="form-control"
                                                placeholder="Buscar factura"
                                                onkeyup="buscarDocumento(this.value);controlFiltrosFactura();document.getElementById('errorFactura').innerHTML=''">

                                            <span
                                                class="input-group-text cursor-pointer"
                                                onclick="
                                                    document.getElementById('documento').value='';
                                                    document.getElementById('errorFactura').innerHTML='';
                                                    document.getElementById('documento').focus();
                                                    document.getElementById('EstadoCartera').disabled = false;
                                                    document.getElementById('EstadoCartera').value = '0';
                                                     // Período
                                                    document.getElementById('tipo_fecha').disabled = false;
                                                    document.getElementById('tipo_fecha').value = 't';

                                                    // Fechas
                                                    document.getElementById('fecha_inicial').disabled = false;
                                                    document.getElementById('fecha_final').disabled = false;
                                                    datos_cartera();

                                                ">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon"
                                                    width="22"
                                                    height="22"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="2"
                                                    stroke="currentColor"
                                                    fill="none"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <line x1="4" y1="7" x2="20" y2="7" />
                                                    <line x1="10" y1="11" x2="10" y2="17" />
                                                    <line x1="14" y1="11" x2="14" y2="17" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>

                                            </span>
                                        </div>

                                        <small id="errorFactura" class="text-danger"></small>
                                    </div>

                                    <!-- Cliente -->
                                    <div class="col-xl-3 col-lg-3 col-md-3">

                                        <label class="form-label">Cliente</label>

                                        <div class="input-group">

                                            <input
                                                type="text"
                                                id="buscarCliente"
                                                name="buscarCliente"
                                                class="form-control"
                                                placeholder="Nombre o NIT"
                                                oninput="document.getElementById('clienteNotiene').innerHTML='';controlFiltrosCliente()">

                                            <span
                                                class="input-group-text cursor-pointer"
                                                onclick="
                                                document.getElementById('buscarCliente').value='';
                                                document.getElementById('id_cliente').value='';
                                                document.getElementById('clienteNotiene').innerHTML='';
                                                document.getElementById('buscarCliente').focus();
                                                datos_cartera()
                                            ">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon"
                                                    width="22"
                                                    height="22"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="2"
                                                    stroke="currentColor"
                                                    fill="none"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <line x1="4" y1="7" x2="20" y2="7" />
                                                    <line x1="10" y1="11" x2="10" y2="17" />
                                                    <line x1="14" y1="11" x2="14" y2="17" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>

                                            </span>

                                        </div>

                                        <small id="clienteNotiene" class="text-danger"></small>

                                    </div>

                                    <!-- Estado -->
                                    <div class="col-xl-1 col-lg-2 col-md-2">

                                        <label class="form-label">Estado</label>

                                        <select class="form-select" id="EstadoCartera" name="EstadoCartera">
                                            <option value="0">Todos</option>
                                            <option value="1">Con saldo</option>
                                            <option value="2">Sin saldo</option>
                                        </select>

                                    </div>

                                    <!-- Tipo de periodo -->
                                    <div class="col-xl-2 col-lg-2 col-md-2">

                                        <label class="form-label">Período</label>

                                        <select
                                            id="tipo_fecha"
                                            name="tipo_fecha"
                                            class="form-select"
                                            onchange="cambiarTipoFecha()">

                                            <option value="t">Todos los tiempos</option>
                                            <option value="f">Una fecha</option>
                                            <option value="pp">Por período</option>

                                        </select>

                                    </div>

                                    <!-- Fecha -->
                                    <div class="col" id="divFecha" style="display:none;">

                                        <label class="form-label">Fecha</label>

                                        <input
                                            type="date"
                                            class="form-control"
                                            id="fecha"
                                            name="fecha"
                                            value="<?= date('Y-m-d') ?>">

                                    </div>

                                    <!-- Periodo -->
                                    <div class="col" id="divPeriodo" style="display:none;">

                                        <div class="row g-2">

                                            <div class="col">

                                                <label class="form-label">Fecha inicial</label>

                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    id="fecha_inicial"
                                                    name="fecha_inicial"
                                                    value="<?= date('Y-m-01') ?>">

                                            </div>

                                            <div class="col">

                                                <label class="form-label">Fecha final</label>

                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    id="fecha_final"
                                                    name="fecha_final"
                                                    value="<?= date('Y-m-d') ?>">

                                            </div>

                                        </div>

                                    </div>

                                    <!-- Botones -->
                                    <div class="col-auto">

                                        <div class="d-flex gap-2">

                                            <button
                                                type="button"
                                                class="btn btn-outline-success"
                                                onclick="buscarCartera()">
                                                Buscar
                                            </button>

                                            <button
                                                type="submit"
                                                class="btn btn-outline-primary">
                                                Excel
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </form>




                            <!-- INDICADORES -->

                            <div class="row g-3 indicadores mb-3">

                                <div class="col-lg-4">

                                    <div class="card bg-light shadow-sm">

                                        <div class="card-body text-center">

                                            <div class="text-danger fw-bold">
                                                Cartera Vigente
                                            </div>

                                            <h3 class="mb-0" id="carteraVigente">
                                                <?= number_format($total, 0, ',', '.'); ?>
                                            </h3>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-lg-4">

                                    <div class="card bg-light shadow-sm">

                                        <div class="card-body text-center">

                                            <div class="text-muted">
                                                Créditos
                                            </div>

                                            <h2 class="mb-0" id="cantidadFacturas">
                                                <?= $cantidad_facturas; ?>
                                            </h2>

                                        </div>

                                    </div>

                                </div>
                                <div class="col-lg-4">

                                    <div class="card bg-light shadow-sm">

                                        <div class="card-body text-center">

                                            <div class="text-muted">
                                                Valor pagado
                                            </div>

                                            <h2 class="mb-0" id="valorPagado">
                                                <?= $cantidad_facturas; ?>
                                            </h2>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- TABLA -->

                        <div class="table-responsive tabla-creditos">

                            <table class="table table-hover table-striped table-vcenter card-table align-middle">

                                <thead>

                                    <tr>

                                        <th>Fecha</th>
                                        <th>NIT</th>
                                        <th>Cliente</th>
                                        <th>Documento</th>
                                        <th>Valor factura </th>
                                        <th>Valor pagado </th>
                                        <th>Saldo</th>
                                        <th>Tipo documento</th>
                                        <th>
                                            Acciones
                                        </th>

                                    </tr>

                                </thead>

                                <tbody id="datosConsultaCartera">

                                    <?= $this->include('cartera/datosCartera') ?>


                                </tbody>

                            </table>

                        </div>



                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- FOOTER -->
    <input type="hidden" value="<?= base_url() ?>" id="url">

    <?= $this->include('layout/footer') ?>

    </div>



    <!-- ===========================================================
JQUERY
=========================================================== -->

    <script src="<?= base_url('Assets/js/jquery-3.5.1.js') ?>"></script>

    <!-- ===========================================================
TABLER
=========================================================== -->

    <script src="<?= base_url('Assets/js/tabler.min.js') ?>"></script>

    <!-- ===========================================================
SWEET ALERT
=========================================================== -->

    <script src="<?= base_url('Assets/plugin/sweet-alert2/sweetalert2@11.js') ?>"></script>

    <!-- ===========================================================
JQUERY UI
=========================================================== -->

    <script src="<?= base_url('Assets/plugin/jquery-ui/jquery-ui.js') ?>"></script>
    <script src="<?= base_url() ?>/Assets/script_js/cartera/imprimir_comprobante.js"></script>


    <!-- <script>
        async function imprimir_comprobante(id) {

            try {

                const response = await fetch(
                    url + '/consultas_y_reportes/imprimir_comprobante_ingreso', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: id
                        })
                    }
                );

                if (!response.ok) {
                    throw new Error('No fue posible generar el comprobante');
                }

               

            } catch (error) {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });

            }

        }
    </script> -->


    <script>
        async function datos_cartera() {

            try {

                const response = await fetch(url + '/cartera/datos_cartera');

                if (!response.ok) {
                    throw new Error('Error al cargar la información');
                }

                const resultado = await response.json();

                // Cargar la vista
                document.getElementById('datosConsultaCartera').innerHTML = resultado.datos;
                document.getElementById('carteraVigente').innerHTML = resultado.total;
                document.getElementById('cantidadFacturas').innerHTML = resultado.cantidad_facturas;

                // Acceder a los datos


                //document.getElementById('cliente').textContent = resultado.datos.cliente;
                //document.getElementById('saldo').textContent = resultado.datos.saldo;

            } catch (error) {

                console.error(error);

                Swal.fire(
                    'Error',
                    'No fue posible cargar la información.',
                    'error'
                );

            }

        }
    </script>



    <script>
        function controlFiltrosCliente() {

            const cliente = document.getElementById('buscarCliente').value.trim();

            const documento = document.getElementById('documento');

            const estado = document.getElementById('EstadoCartera');
            const tipoFecha = document.getElementById('tipo_fecha');
            const fecha = document.getElementById('fecha');
            const fechaInicial = document.getElementById('fecha_inicial');
            const fechaFinal = document.getElementById('fecha_final');

            if (cliente !== '') {

                // Limpiar factura
                documento.value = '';

                // Habilitar filtros
                estado.disabled = false;
                tipoFecha.disabled = false;
                fecha.disabled = false;
                fechaInicial.disabled = false;
                fechaFinal.disabled = false;

            }

        }
    </script>


    <script>
        function controlFiltrosFactura() {

            const documento = document.getElementById('documento').value.trim();

            const cliente = document.getElementById('buscarCliente');
            const idCliente = document.getElementById('id_cliente');

            const estado = document.getElementById('EstadoCartera');
            const tipoFecha = document.getElementById('tipo_fecha');
            const fecha = document.getElementById('fecha');
            const fechaInicial = document.getElementById('fecha_inicial');
            const fechaFinal = document.getElementById('fecha_final');

            if (documento !== '') {

                cliente.value = '';
                idCliente.value = '';

                estado.disabled = true;
                tipoFecha.disabled = true;
                fecha.disabled = true;
                fechaInicial.disabled = true;
                fechaFinal.disabled = true;

            } else {

                estado.disabled = false;
                tipoFecha.disabled = false;
                fecha.disabled = false;
                fechaInicial.disabled = false;
                fechaFinal.disabled = false;

            }

        }
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            cambiarTipoFecha();
        });
    </script>

    <script>
        function cambiarTipoFecha() {

            const tipo = document.getElementById("tipo_fecha").value;

            const divFecha = document.getElementById("divFecha");
            const divPeriodo = document.getElementById("divPeriodo");

            const fecha = document.getElementById("fecha");
            const fechaInicial = document.getElementById("fecha_inicial");
            const fechaFinal = document.getElementById("fecha_final");

            // Ocultar todos
            divFecha.style.display = "none";
            divPeriodo.style.display = "none";

            if (tipo === "t") {

                // Todos los tiempos
                fecha.value = "";
                fechaInicial.value = "";
                fechaFinal.value = "";

            } else if (tipo === "f") {

                divFecha.style.display = "block";

                fecha.value = "<?= date('Y-m-d') ?>";
                fechaInicial.value = "";
                fechaFinal.value = "";

            } else if (tipo === "pp") {

                divPeriodo.style.display = "block";

                fecha.value = "";
                fechaInicial.value = "<?= date('Y-m-01') ?>";
                fechaFinal.value = "<?= date('Y-m-d') ?>";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            cambiarTipoFecha();
        });
    </script>


    <script>
        function cancelar_pagar() {


            $("#efectivo").val(0);
            $("#transaccion").val(0);
            $('#clase_pago').prop('selectedIndex', 0);
            document.getElementById('abonoSuperior').innerHTML = '';
            const modal = bootstrap.Modal.getInstance(document.getElementById('finalizar_venta'));
            modal.hide();

        }
    </script>


    <script>
        function limpiar(id) {
            const input = document.getElementById(id);

            if (input) {
                input.value = '';
                input.focus();
            }
        }
    </script>

    <script>
        const url = document.getElementById('url').value;

        // Limpiar el campo Documento al empezar a escribir en Cliente
        $("#buscarCliente").on("input", function() {
            $("#documento").val("");
            $("#errorFactura").html("");
        });

        $("#buscarCliente").autocomplete({

            minLength: 2,
            delay: 300,

            source: function(request, response) {

                $.ajax({
                    url: url + "/clientes/clientes_cartera",
                    type: "POST",
                    dataType: "json",
                    data: {
                        buscar: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });

            },

            select: function(event, ui) {

                $("#buscarCliente").val(ui.item.value);
                $("#id_cliente").val(ui.item.id_cliente);

                buscarCliente(ui.item.id_cliente);

                return false;
            }
        });
    </script>

    <script>
        async function buscarDocumento(documento) {


            try {

                const response = await fetch("<?= base_url('cartera/buscarDocumento') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        documento: documento
                    })
                });

                if (!response.ok) {
                    throw new Error("Error en la petición.");
                }

                const data = await response.json();

                if (data.success == true) {

                    document.getElementById('datosConsultaCartera').innerHTML = data.data
                    document.getElementById('carteraVigente').innerHTML = data.total
                    document.getElementById('cantidadFacturas').innerHTML = data.cantidad
                    document.getElementById('valorPagado').innerHTML = data.valor_pagado

                } else {

                    document.getElementById('errorFactura').innerHTML = "No hay coincidencias "

                }

                return data;

            } catch (error) {

                console.error("Error:", error);

                return null;

            }

        }
    </script>


    <script>
        async function buscarCartera() {

            let fecha_inicial = '';
            let fecha_final = '';

            const tipoFecha = document.getElementById('tipo_fecha').value;

            switch (tipoFecha) {

                case 't': // Todos los tiempos
                    fecha_inicial = '';
                    fecha_final = '';
                    break;

                case 'f': // Una fecha
                    fecha_inicial = document.getElementById('fecha').value;
                    fecha_final = fecha_inicial;
                    break;

                case 'pp': // Por período
                    fecha_inicial = document.getElementById('fecha_inicial').value;
                    fecha_final = document.getElementById('fecha_final').value;
                    break;
            }





            const estado = document.getElementById('EstadoCartera').value;
            const id_cliente = document.getElementById('id_cliente').value;

            Swal.fire({
                title: 'Consultando cartera...',
                html: 'Por favor espere un momento.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {

                const response = await fetch("<?= base_url('cartera/getCartera') ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        fecha_inicial,
                        fecha_final,
                        estado,
                        id_cliente
                    })
                });

                const data = await response.json();

                Swal.close();

                if (data.success) {
                    document.getElementById('datosConsultaCartera').innerHTML = data.data;
                    document.getElementById('carteraVigente').innerHTML = data.total;
                    document.getElementById('cantidadFacturas').innerHTML = data.cantidad;
                    document.getElementById('valorPagado').innerHTML = data.valor_pagado;
                }

            } catch (error) {

                Swal.close();

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al consultar la cartera.'
                });

                console.error(error);
            }
        }
    </script>

    <script>
        async function buscarCliente(id_cliente) {

            //console.log(id_cliente)

            try {

                const response = await fetch("<?= base_url('cartera/buscarCliente') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        id_cliente: id_cliente
                    })
                });

                if (!response.ok) {
                    throw new Error("Error en la petición.");
                }

                const data = await response.json();

                if (data.success == true) {

                    document.getElementById('datosConsultaCartera').innerHTML = data.data
                    document.getElementById('carteraVigente').innerHTML = data.total
                    document.getElementById('cantidadFacturas').innerHTML = data.cantidad
                    document.getElementById('valorPagado').innerHTML = data.valor_pagado

                } else {


                    document.getElementById('clienteNotiene').innerHTML = data.message

                }

                return data;

            } catch (error) {

                console.error("Error:", error);

                return null;

            }

        }
    </script>



</body>

</html>