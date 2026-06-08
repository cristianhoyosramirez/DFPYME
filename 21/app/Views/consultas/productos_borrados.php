<?php $session = session(); ?>
<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
Reporte de costos
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!-- Jquery date picker  -->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">
<!-- Data tables -->
<link href="<?= base_url() ?>/Assets/plugin/data_tables/bootstrap.min.css" />
<link href="<?= base_url() ?>/Assets/plugin/data_tables/dataTables.bootstrap5.min.css" />

<!-- Select 2 -->
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>/Assets/plugin/select2/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<div class="container">
    <p class="text-center text-primary h3">Informe de productos borrados </p>

    <div class="my-4"></div> <!-- Added space between the title and date inputs -->


    <input type="hidden" id="url" value="<?php echo base_url() ?>">
    <form action="<?= base_url('caja_general/exportProductosBorradosExcel') ?>" method="POST">

        <div id="entre_fechas" class="col-12">
            <div class="row g-3 align-items-end">

                <!-- Fecha inicial -->
                <div class="col-12 col-md-3">
                    <label for="fecha_inicial" class="form-label">Fecha inicial</label>
                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control" id="fecha_inicial" name="fecha_inicial"
                            value="<?php echo date('Y-m-d'); ?>"
                            onkeyup="document.getElementById('error_de_fecha_inicial').innerText = '';"
                            onchange="document.getElementById('error_de_fecha_inicial').innerText = '';">
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="Limpiar"
                                onclick="limpiar_campo('fecha_inicial')">✕</a>
                        </span>
                    </div>
                    <small id="error_de_fecha_inicial" class="text-danger"></small>
                </div>

                <!-- Fecha final -->
                <div class="col-12 col-md-3">
                    <label for="fecha_final" class="form-label">Fecha final</label>
                    <div class="input-group input-group-flat">
                        <input type="text" class="form-control" id="fecha_final" name="fecha_final"
                            value="<?php echo date('Y-m-d'); ?>">
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="Limpiar"
                                onclick="limpiar_campo('fecha_final')">✕</a>
                        </span>
                    </div>
                </div>

                <!-- Buscar -->
                <div class="col-6 col-md-2">
                    <button type="button"
                        class="btn btn-outline-primary w-100"
                        onclick="buscar()"
                        title="Buscar datos">
                        🔍 Buscar
                    </button>
                </div>

                <!-- Exportar -->
                <div class="col-6 col-md-2">
                    <button class="btn btn-outline-success w-100"
                        type="submit"
                        title="Exportar a Excel">
                        📊 Excel
                    </button>
                </div>

                <!-- Error -->
                <div class="col-12 col-md-2">
                    <small class="text-danger" id="error_fecha"></small>
                </div>

            </div>
        </div>

    </form>





    <div class="my-3"></div> <!-- Added space between the buttons and the table -->
    <div class="table-responsive" style="max-height: 50vh; overflow-y: auto;">
        <table class="table">
            <thead class="table-dark">
                <td scope="col">Pedido</th>
                <td scope="col">Código </th>
                <td scope="col">Producto </th>
                <td scope="col">Cantidad </th>
                <td scope="col">Fecha</th>
                <td scope="col">Hora</th>
                <td scope="col">Usuario creacion</th>
                <td scope="col">Usuario eliminación</th>
                <td>Justificación</td>

            </thead>
            <tbody id="productos_borrados">

                <?php if (!empty($productos)) {  ?>
                    <?php foreach ($productos as $detalle) : ?>

                        <tr>

                            <td><?php echo $detalle['pedido']
                                ?></td>
                            <td><?php echo $detalle['codigointernoproducto'] ?></td>
                            <td><?php echo $detalle['nombreproducto'] ?></td>
                            <td><?php echo $detalle['cantidad'] ?></td>
                            <td>
                                <?php
                                $fecha = $detalle['fecha_eliminacion']; // ejemplo "2025-09-26"
                                $fmt = new IntlDateFormatter(
                                    'es_ES',
                                    IntlDateFormatter::FULL,  // Muestra día de la semana, día, mes y año
                                    IntlDateFormatter::NONE   // Sin hora
                                );

                                echo $fmt->format(new DateTime($fecha));
                                ?>
                            </td>

                            <td><?php
                                $hora = $detalle['hora_eliminacion'];
                                $hora_formateada = date("h:i A", strtotime($hora));
                                echo $hora_formateada; ?></td>
                            <td><?php echo $detalle['nombresusuario_sistema'] ?></td>
                            <td><?php echo $detalle['mesero_nombre'] ?></td>
                            <td><?php echo $detalle['justificacion'] ?></td>

                        </tr>

                    <?php endforeach ?>
                <?php } ?>

            </tbody>
        </table>

        <br>
        <p class="text-primary h1 text-center " id="no_hay_datos"> </p>
    </div>
</div>

<!-- jQuery -->
<script src="<?= base_url() ?>/Assets/js/jquery-3.5.1.js"></script>
<!-- jQuery UI -->
<script src="<?= base_url() ?>/Assets/plugin/jquery-ui/jquery-ui.js"></script>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>

<!--select2 -->
<script src="<?php echo base_url(); ?>/Assets/plugin/select2/select2.min.js"></script>

<script>
    function select_periodo(periodo) {

        var inicial = document.getElementById("inicial");
        var final = document.getElementById("final");
        $('#error_periodo').html('')

        if (periodo == 1) {
            inicial.style.display = "none";
            final.style.display = "none";
        }
        if (periodo == 2) {
            inicial.style.display = "block";
            final.style.display = "none";
        }
        if (periodo == 3) {
            inicial.style.display = "block";
            final.style.display = "block";
        }

    }
</script>

<script>
    $("#periodo").select2({
        width: "100%",
        language: "es",
        theme: "bootstrap-5",
        allowClear: false,
        placeholder: "Seleccionar un rango ",
        minimumResultsForSearch: -1,
        language: {
            noResults: function() {
                return "No hay resultado";
            },
            searching: function() {
                return "Buscando..";
            }
        },

    });
</script>

<script>
    let mensaje = "<?php echo $session->getFlashdata('mensaje'); ?>";
    let iconoMensaje = "<?php echo $session->getFlashdata('iconoMensaje'); ?>";
    if (mensaje != "") {

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            //position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: iconoMensaje,
            title: mensaje
        })


    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('fecha_inicial');
        var input2 = document.getElementById('fecha_final');
        var parrafo = document.getElementById('error_fecha');

        // Función para borrar el párrafo
        function borrarParrafo() {
            parrafo.textContent = ""; // o parrafo.innerHTML = "";
        }

        // Evento clic en el input
        input.addEventListener('click', borrarParrafo);

        // Evento de escritura en el input
        input.addEventListener('input', borrarParrafo);


        // Eventos clic y escritura para el input2
        input2.addEventListener('click', borrarParrafo);
        input2.addEventListener('input', borrarParrafo);
    });
</script>
<script>
    function limpiar_campo(campo) {
        $('#' + campo).val('');
    }
</script>


<script>
    $(function() {
        var dateFormat = "yy-mm-dd"; // Cambia el formato de fecha a "yy-mm-dd"

        var from = $("#fecha_inicial").datepicker({
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: true,
            dateFormat: dateFormat, // Establece el nuevo formato de fecha
            onClose: function(selectedDate) {
                to.datepicker("option", "minDate", selectedDate);
            }
        });

        var to = $("#fecha_final").datepicker({
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: true,
            dateFormat: dateFormat, // Establece el nuevo formato de fecha
            onClose: function(selectedDate) {
                from.datepicker("option", "maxDate", selectedDate);
            }
        });

        // Export to Excel and PDF functionality
        $('#exportExcelBtn').click(function() {
            // Agrega tu código para exportar a Excel aquí
        });

        $('#exportPdfBtn').click(function() {
            // Agrega tu código para exportar a PDF aquí
        });
    });
</script>

<script>
    function buscar() {
        var url = document.getElementById("url").value;
        var fecha_inicial = document.getElementById("fecha_inicial").value;
        var fecha_final = document.getElementById("fecha_final").value;
        var periodo = 3
        buscar_productos_borrados(periodo, fecha_inicial, fecha_final, url)
    }
</script>

<script>
    function buscar_productos_borrados(periodo, fecha_inicial, fecha_final, url) {

        // 🔄 SPINNER INICIAL
        Swal.fire({
            title: 'Consultando...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: url + "/" + "reportes/datos_productos_borrados",
            type: "POST",
            data: {
                fecha_inicial,
                fecha_final,
                periodo
            },
            success: function(resultado) {

                Swal.close(); // ❗ cerrar spinner

                var resultado = JSON.parse(resultado);

                if (resultado.resultado == 1) {

                    $('#productos_borrados').html(resultado.productos);

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Registros encontrados'
                    });
                }

                if (resultado.resultado == 0) {
                    $('#productos_borrados').html('');
                    $('#no_hay_datos').html('¡No hay datos para el rango de fecha solicitado!');
                }
            },
            error: function() {

                Swal.close();

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un problema al consultar'
                });
            }
        });
    }
</script>


<?= $this->endSection('content') ?>