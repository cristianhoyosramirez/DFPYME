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

                            <!-- FILTROS -->

                            <div class="row g-3 filtros mb-3">

                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                    <label class="form-label">
                                        Número de factura
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Buscar una factura por número " onkeyup="buscarDocumento(this.value)">
                                </div>

                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

                                    <label class="form-label">
                                        Cliente
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Nombre o NIT" oninput="buscarCliente(this.value)">

                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">

                                    <label class="form-label">
                                        Estado
                                    </label>

                                    <select class="form-select" id="EstadoCartera">

                                        <option value="0">Todos</option>
                                        <option value="1">Con saldo</option>
                                        <option value="2">Sin saldo</option>

                                    </select>

                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">

                                    <label class="form-label">
                                        Fecha desde
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control" id="fecha_inicial">

                                    <small class="text-muted">
                                        Vacío = desde el primer registro
                                    </small>

                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">

                                    <label class="form-label">
                                        Fecha hasta
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control" id="fecha_final">

                                    <small class="text-muted">
                                        Vacío = hasta el último registro
                                    </small>

                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-4 d-grid">

                                    <label class="form-label invisible">
                                        Buscar
                                    </label>

                                    <button class="btn btn-outline-success" onclick="buscarCartera()">
                                        Buscar
                                    </button>

                                    <p></p>

                                </div>

                            </div>
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

            const fecha_inicial = document.getElementById('fecha_inicial').value;
            const fecha_final = document.getElementById('fecha_final').value;
            const estado = document.getElementById('EstadoCartera').value;

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
                        estado
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
        async function buscarCliente(cliente) {


            try {

                const response = await fetch("<?= base_url('cartera/buscarCliente') ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        cliente: cliente
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