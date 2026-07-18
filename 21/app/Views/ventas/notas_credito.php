<?php $session = session(); ?>
<?= $this->extend('template/home') ?>

<?= $this->section('title') ?>
Notas Crédito
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<p class="text-primary text-center">Consulta notas crédito</p>

<div class="card container">
    <div class="card-body">

        <input
            type="hidden"
            value="<?php echo $session->id_usuario ?>"
            id="id_usuario">

        <input type="hidden" value="<?php echo base_url() ?>" name="url" id="url">

        <div class="row align-items-end g-2">



            <!-- Numero -->
            <div class="col-md-3">
                <label class="form-label">Número</label>



                <div class="input-group input-group-flat">

                    <input
                        type="text"
                        class="form-control"
                        id="numero_factura"
                        name="numero_factura"
                        oninput="buscarNcDelay(this.value)"
                        placeholder="Buscar por numero de nota crédito ">

                    <span class="input-group-text">
                        <a href="#"
                            class="link-secondary"
                            title="Limpiar campo"
                            onclick="limpiar_busqueda('numero_factura')">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round">

                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />

                            </svg>

                        </a>
                    </span>
                </div>

                <span id="error_cliente" class="text-danger"></span>
            </div>
            <!-- Cliente -->
            <div class="col-md-3">
                <label class="form-label">Cliente</label>

                <input type="hidden" id="nit_del_cliente" name="nit_del_cliente">

                <div class="input-group input-group-flat">
                    <input
                        type="text"
                        class="form-control"
                        id="busqueda_por_cliente_nc"
                        name="busqueda_por_cliente_nc"
                        placeholder="Nit o nombre del cliente"
                        oninput="buscarNcClienteDelay(this.value)">

                    <span class="input-group-text">
                        <a href="#"
                            class="link-secondary"
                            title="Limpiar campo"
                            onclick="limpiar_busqueda('busqueda_por_cliente_nc')">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="icon"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round">

                                <path d="M18 6l-12 12" />
                                <path d="M6 6l12 12" />

                            </svg>

                        </a>
                    </span>
                </div>

                <span id="error_cliente" class="text-danger"></span>
            </div>

            <!-- Desde -->
            <div class="col-md-2">
                <label class="form-label">Desde</label>
                <input
                    type="date"
                    class="form-control"
                    value="<?= date('Y-m-d') ?>"
                    id="fecha_inicial">


            </div>

            <!-- Hasta -->
            <div class="col-md-2">
                <label class="form-label">Hasta</label>
                <input
                    type="date"
                    class="form-control"
                    value="<?= date('Y-m-d') ?>"
                    id="fecha_final">


            </div>

            <!-- Botón -->
            <div class="col-md-2 d-flex flex-column justify-content-end">
                <label class="form-label invisible">Buscar</label>

                <button type="button"
                    class="btn btn-primary w-100"
                    onclick="buscarFecha()">

                    Buscar

                </button>
            </div>

        </div>

        <br>

        <!-- TARJETAS DIAN -->

        <div class="row">

            <div class="col">
                <a
                    class="card card-link cursor-pointer"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    title="Documentos acetados por la DIAN"
                    onclick="estado_dian_nc(2)">

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium text-primary h5 text-center">
                                    DIAN ACEPTADO
                                </div>
                                <div class="text-muted text-center" id="dian_aceptado">
                                    <span><?= $dian_aceptado; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

            <div class="col">
                <a
                    class="card card-link cursor-pointer"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    title="Documentos no enviados a la DIAN"
                    onclick="estado_dian_nc(1)">

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium text-primary h5 text-center">
                                    DIAN NO ENVIADO
                                </div>
                                <div class="text-muted text-center" id="dian_no_enviado">
                                    <span><?= $dian_no_aceptado; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

            <div class="col">
                <a
                    class="card card-link cursor-pointer"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    title="Documentos rechazados por la DIAN"
                    onclick="estado_dian_nc(3)">

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium text-primary h5 text-center">
                                    DIAN RECHAZADO
                                </div>
                                <div class="text-muted text-center" id="dian_rechazado">
                                    <span><?= $dian_rechazado; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

            <div class="col">
                <a
                    class="card card-link cursor-pointer"
                    data-bs-toggle="tooltip"
                    data-bs-placement="bottom"
                    title="Documentos con error"
                    onclick="estado_dian_nc(4)">

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-medium text-primary h5 text-center">
                                    DIAN ERROR
                                </div>
                                <div class="text-muted text-center" id="dian_error">
                                    <span><?= $dian_error; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </a>
            </div>

        </div>

        <br>

        <div id="data_table_ventas">

            <table class="table table-striped table-hover" id="consulta_nota_credito">

                <thead class="table-dark">
                    <tr class="table-dark">
                        <td>Fecha</th>
                        <td>Nit cliente</th>
                        <td>Cliente</th>
                        <td>N° nota crédito</th>
                        <td>N° factura</th>
                        <td>Motivo</th>

                        <td>Estado</th>
                        <td>Usuario</th>

                        <td>Acción</th>
                    </tr>
                </thead>

                <tbody id="notas_credito">

                    <?= $this->include('ventas/notasCredito') ?>

                </tbody>

            </table>

            <p class="text-end text-primary h3" id="totalNc">Total: <?= $total ?></p>

        </div>

    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="detalleNc" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Detalle de la nota crédito </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card border-0 shadow-sm mb-3">

                    <div class="card-body">

                        <div class="row g-3 align-items-end">

                            <!-- Factura -->
                            <div class="col-md-3">
                                <label class="form-label text-muted small mb-1">
                                    Factura
                                </label>

                                <div
                                    id="numero_factura_nota_credito"
                                    class="fw-bold text-primary fs-5">

                                </div>
                            </div>

                            <!-- Nota Crédito -->
                            <div class="col-md-3">
                                <label class="form-label text-muted small mb-1">
                                    Nota Crédito
                                </label>

                                <div
                                    id="numero_nota_credito"
                                    class="fw-bold text-danger fs-5">

                                </div>
                            </div>

                            <!-- Fecha factura -->
                            <div class="col-md-3">
                                <label class="form-label text-muted small mb-1">
                                    Fecha factura
                                </label>

                                <div
                                    id="fecha_de_factura"
                                    class="fw-bold text-primary fs-6">

                                </div>
                            </div>

                            <!-- Fecha Nota Crédito -->
                            <div class="col-md-3">
                                <label class="form-label text-muted small mb-1">
                                    Fecha Nota Crédito
                                </label>

                                <div
                                    id="fecha_nota_credito"
                                    class="fw-bold text-success fs-6">

                                </div>
                            </div>

                            <!-- Cliente -->
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">
                                    Cliente
                                </label>

                                <div
                                    id="cliente_nota_credito"
                                    class="fw-bold text-primary fs-5">

                                </div>
                            </div>

                            <!-- NIT -->
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">
                                    NIT / C.C.
                                </label>

                                <div
                                    id="nit_cliente"
                                    class="fw-bold text-primary fs-5">

                                </div>
                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Motivo Nota Crédito
                                </label>

                                <div>
                                    <span class="fw-bold text-danger fs-5" id="motivoAnulacion">

                                    </span>
                                </div>

                            </div>

                            <!-- Nota -->
                            <div class="col-md-6">

                                <label
                                    for="notaCredito"
                                    class="form-label text-muted small mb-1">

                                    Nota

                                </label>

                                <textarea
                                    id="notaCredito"
                                    name="notaCredito"
                                    class="form-control"
                                    rows="2"
                                    disabled></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <div id="detalle_nota_credito"></div>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-outline-success">Imprimir</button>
                <button type="button" class="btn btn-outline-dark">Enviar a la DIAN </button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php $ip = model('configuracionPedidoModel')->select('ip')->first(); ?>

<input type="hidden" value="<?= $ip['ip']; ?>" id="ip">

<script>
    async function estado_dian_nc(estado) {

        try {

            Swal.fire({
                title: 'Consultando...',
                html: 'Espere un momento',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch("<?= base_url('reportes/nCEstado') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    estado: estado
                })
            });

            Swal.close();

            if (!response.ok) {
                throw new Error(`Error ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {

                document.getElementById('notas_credito').innerHTML = data.nc
                document.getElementById('totalNc').innerHTML = data.total

                const estados = {
                    1: 'dian_no_enviado',
                    2: 'dian_aceptado',
                    3: 'dian_rechazado',
                    4: 'dian_error'
                };

                const elemento = document.getElementById(estados[data.id_status]);

                if (elemento) {
                    elemento.innerHTML = data.resultados;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Consulta realizada',
                    text: 'Se encontraron ' + data.resultados + ' registros.',
                    timer: 2000, // Se cierra en 2 segundos
                    showConfirmButton: false,
                    timerProgressBar: true
                });
                return;
            }



        } catch (error) {

            Swal.close();

            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al consultar la información.'
            });

        }

    }
</script>

<script>
    function recargarNotasCredito() {

        fetch('<?= base_url('reportes/allNc') ?>')
            .then(response => response.json())
            .then(data => {

                if (!data.success) return;

                // Recargar la tabla
                document.getElementById('notas_credito').innerHTML = data.nc;

                // Actualizar los indicadores si existen
                document.getElementById('dian_aceptado').textContent = data.dian_aceptado;
                document.getElementById('dian_no_enviado').textContent = data.dian_no_aceptado;
                document.getElementById('dian_rechazado').textContent = data.dian_rechazado;
                document.getElementById('dian_error').textContent = data.dian_error;
                document.getElementById('total').textContent = data.total;

            })
            .catch(error => console.error(error));

    }
</script>

<script>
    function limpiar_busqueda(id) {

        const campo = document.getElementById(id);

        if (!campo) return;

        // Limpiar el campo
        campo.value = '';

        // Enfocar nuevamente el input
        campo.focus();

        // Disparar el evento input para que se ejecute
        // automáticamente la función definida en oninput
        campo.dispatchEvent(new Event('input', {
            bubbles: true
        }));

        recargarNotasCredito();

    }
</script>

<script>
    async function buscarFecha() {

        const fechaInicial = document.getElementById('fecha_inicial').value;
        const fechaFinal = document.getElementById('fecha_final').value;

        Swal.fire({
            title: 'Consultando información...',
            html: 'Espere un momento',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {

            const response = await fetch("<?= base_url('reportes/buscarNcFecha') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    fecha_inicial: fechaInicial,
                    fecha_final: fechaFinal
                })
            });

            Swal.close();

            if (!response.ok) {
                throw new Error(`Error ${response.status}`);
            }

            const data = await response.json();

            if (!data.success) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: data.message ?? 'No fue posible obtener la información.'
                });
                return;
            }

            document.getElementById("dian_aceptado").innerHTML = data.dian_aceptado ?? 0;
            document.getElementById("dian_no_enviado").innerHTML = data.dian_no_enviado ?? 0;
            document.getElementById("dian_rechazado").innerHTML = data.dian_rechazadas ?? 0;
            document.getElementById("notas_credito").innerHTML = data.nc ?? 0;

        } catch (error) {

            Swal.close();

            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al consultar la información.'
            });

        }

    }
</script>



<script>
    async function enviarNotaCredito(idNotaCredito) {

        const confirmacion = await Swal.fire({
            title: '¿Confirmar envío?',
            html: 'Está a punto de transmitir la <b>Nota Crédito</b> a la DIAN para su validación.<br><br><b>Una vez enviada, esta acción no podrá revertirse.</b> ¿Desea continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, enviar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                actions: 'gap-2',
                confirmButton: 'btn btn-outline-success',
                cancelButton: 'btn btn-outline-danger'
            }
        });

        if (!confirmacion.isConfirmed) {
            return;
        }

        $("#id_de_factura").val(idNotaCredito);
        $("#barra_progreso").modal("show");
        $("#barra_de_progreso").show();
        $("#respuesta_de_dian").html("Esperando respuesta DIAN");
        $("#texto_dian").html("");
        $("#opciones_dian").hide();

        let ip = document.getElementById("ip").value;

        let url = new URL("http://" + ip + ":5000/api/NotaCredito/id");
        url.search = new URLSearchParams({
            id: idNotaCredito
        });

        try {

            const response = await fetch(url, {
                method: "GET"
            });

            const data = await response.json();

            if (response.status === 200) {

                $("#id_factura").val(idNotaCredito);
                $("#barra_de_progreso").hide();
                $("#opciones_dian").hide();
                $("#error_dian").hide();
                $("#respuesta_dian").show();
                $("#opciones_dian").show();

                $("#texto_dian").html(
                    data.order_reference + " " + data.dian_status
                );

                const resultado = await Swal.fire({
                    title: '¿Actualizar inventario y caja?',
                    html: `
            La Nota Crédito fue transmitida correctamente a la DIAN.<br><br>
            ¿Desea <b>cargar nuevamente los productos al inventario</b> y
            <b>registrar la devolución en el cuadre de caja</b>?
        `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, realizar proceso',
                    cancelButtonText: 'No',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        actions: 'gap-2',
                        confirmButton: 'btn btn-outline-success',
                        cancelButton: 'btn btn-outline-danger'
                    }
                });

                if (resultado.isConfirmed) {
                    // Llamar aquí la función que realiza el proceso
                    await cargarInventarioYGenerarDevolucion(idNotaCredito);
                }

            } else if (response.status === 400) {

                $("#barra_de_progreso").hide();
                $("#respuesta_dian").show();
                $("#error_dian").show();

                $("#texto_dian").html(data.errors[0].error);

                $("#id_factura").val(idNotaCredito);

            } else {

                $("#barra_de_progreso").hide();
                $("#respuesta_dian").show();
                $("#error_dian").show();

                $("#texto_dian").html(
                    "Respuesta DIAN: " +
                    (data.errors ? data.errors[0].error : "Error desconocido")
                );

                $("#id_factura").val(idNotaCredito);

            }

        } catch (error) {

            $("#barra_de_progreso").hide();
            $("#respuesta_dian").show();
            $("#error_dian").show();

            $("#texto_dian").html(
                "No fue posible conectar con el servidor de facturación electrónica."
            );

            console.error(error);
        }
    }
</script>

<script>
    async function cargarInventarioYGenerarDevolucion(idNotaCredito) {

        try {

            Swal.fire({
                title: 'Procesando...',
                html: 'Actualizando inventario y caja.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

           
            const response = await fetch('<?= base_url('reportes/devolucionNc') ?>', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id_nota_credito: idNotaCredito
                })
            });

            Swal.close();

            if (!response.ok) {
                throw new Error(`Error ${response.status}`);
            }

            const data = await response.json();

            if (!data.success) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Proceso realizado',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });

        } catch (error) {

            Swal.close();

            //console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error durante el proceso.'
            });

        }

    }
</script>

<script>
    async function eliminarNotaCredito(id) {

        const confirmar = await Swal.fire({
            title: "¿Eliminar nota de crédito?",
            text: "Esta acción no se puede deshacer.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
            allowOutsideClick: false
        });

        if (!confirmar.isConfirmed) {
            return;
        }

        // Spinner
        Swal.fire({
            title: "Eliminando...",
            text: "Por favor espere.",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {

            const url = document.getElementById("url").value;

            const response = await fetch(url + "/reportes/eliminarNotaCredito", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    id: id
                })
            });

            if (!response.ok) {
                throw new Error("Error en la comunicación con el servidor.");
            }

            const resultado = await response.json();

            Swal.close();

            if (resultado.success) {

                await Swal.fire({
                    icon: "success",
                    title: "Proceso exitoso",
                    text: resultado.mensaje,
                    confirmButtonText: "Aceptar"
                });

                location.reload();

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: resultado.mensaje
                });

            }

        } catch (error) {

            Swal.close();

            console.error(error);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Ocurrió un error al eliminar la nota de crédito."
            });

        }
    }
</script>

<script>
    let timerBuscar;

    function buscarNcDelay(valor) {
        clearTimeout(timerBuscar);

        timerBuscar = setTimeout(() => {
            buscarNc(valor);
        }, 300);
    }

    async function buscarNc(valor) {

        valor = valor.trim();

        // Evita consultar si está vacío
        if (valor === '') {
            return;
        }

        try {

            const response = await fetch('<?= base_url('reportes/buscarNcNumero') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    buscar: valor
                })
            });

            if (!response.ok) {
                throw new Error('Error al consultar el servidor');
            }

            const data = await response.json();

            if (data.success == true) {
                document.getElementById('dian_aceptado').innerHTML = data.dian_aceptado
                document.getElementById('dian_no_enviado').innerHTML = data.dian_no_enviado
                document.getElementById('dian_rechazado').innerHTML = data.dian_rechazadas
                document.getElementById('notas_credito').innerHTML = data.nc
            }



        } catch (error) {
            console.error(error);
        }
    }
</script>

<script>
    let timerBuscador = null;
    let controller = null;
    let ultimaBusqueda = '';

    function buscarNcClienteDelay(valor) {

        clearTimeout(timerBuscador);

        timerBuscador = setTimeout(() => {
            buscarNcCliente(valor);
        }, 400);

    }

    async function buscarNcCliente(valor) {

        valor = valor.trim();

        // Si está vacío reinicia los indicadores
        if (valor === '') {

            document.getElementById("dian_aceptado").textContent = 0;
            document.getElementById("dian_no_enviado").textContent = 0;
            document.getElementById("dian_rechazado").textContent = 0;
            document.getElementById("notas_credito").textContent = 0;

            ultimaBusqueda = '';

            if (controller) {
                controller.abort();
            }

            return;
        }

        // Evita consultar dos veces el mismo texto
        if (valor === ultimaBusqueda) {
            return;
        }

        ultimaBusqueda = valor;

        // Cancela la petición anterior
        if (controller) {
            controller.abort();
        }

        controller = new AbortController();

        try {

            const response = await fetch("<?= base_url('reportes/buscarNcCliente') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    buscar: valor
                }),
                signal: controller.signal
            });

            if (!response.ok) {
                throw new Error(`Error ${response.status}`);
            }

            const data = await response.json();

            if (data.success == true) {
                document.getElementById("dian_aceptado").innerHTML = data.dian_aceptado;
                document.getElementById("dian_no_enviado").innerHTML = data.dian_no_enviado;
                document.getElementById("dian_rechazado").innerHTML = data.dian_rechazadas;
                document.getElementById("notas_credito").innerHTML = data.nc;
            }

        } catch (error) {

            if (error.name !== "AbortError") {
                console.error(error);
            }

        } finally {

            controller = null;

        }

    }
</script>


<?= $this->endSection('content') ?>