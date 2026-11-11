<?php $session = session(); ?>
<?= $this->extend('template/template') ?>
<?= $this->section('title') ?>
Reporte de ventas por usuario
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>
<!-- Select 2 -->

<!-- Jquery date picker  -->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>/Assets/plugin/calendario/jquery-ui-1.12.1.custom/jquery-ui.css">


<div class="container">
    <p class="text-center text-primary h3">Informe de ventas por mesero </p>

    <div class="my-4"></div> <!-- Added space between the title and date inputs -->
    <!-- Agregar una barra de progreso -->


    <input type="hidden" id="url" value="<?php echo base_url() ?>">
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-dark text-white">
            Filtro reporte ventas por mesero
        </div>


        <div class="card-body">

            
                <div class="row g-3">

                    <!-- Ventas por mesero -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                Ventas por mesero
                            </div>

                            <div class="card-body">



                                <div class="row g-3">

                                    <!-- Mesero -->
                                    <div class="col-md-4">
                                        <label class="form-label">Mesero</label>
                                        <select name="id_mesero" id="id_mesero" class="form-select">
                                         
                                            <?php foreach ($meseros as $m): ?>
                                                <option value="<?php echo $m['idusuario_sistema'] ?>">
                                                    <?php echo $m['nombresusuario_sistema'] ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <!-- Fecha inicial -->
                                    <div class="col-md-4">
                                        <label class="form-label">Fecha inicial</label>
                                        <input type="date" name="fecha_inicial" id="fecha_inicial" class="form-control" value="<?php echo date('Y-m-d') ?>">
                                    </div>

                                    <!-- Fecha final -->
                                    <div class="col-md-4">
                                        <label class="form-label">Fecha final</label>
                                        <input type="date" name="fecha_final" id="fecha_final" class="form-control" value="<?php echo date('Y-m-d') ?>">
                                    </div>

                                    <!-- Botón -->
                                    <div class="col-12">
                                        <button onclick="bucarVentaMesero()" class="btn btn-success w-100">
                                            Buscar
                                        </button>
                                    </div>

                                </div>



                                <div id="resultadoBusqueda"></div>

                            </div>
                        </div>
                    </div>


                    <!-- Movimiento de caja -->
                    <div class="col-md-6">

                        <div class="card shadow-sm">

                            <div class="card-header bg-dark text-white">
                                Movimiento de caja
                            </div>

                            <div class="card-body">

                                <div class="row g-3">

                                    <!-- Mesero -->
                                    <div class="col-md-3">
                                        <label class="form-label">Mesero</label>
                                        <select name="idMesero" id="idMesero" class="form-select">
                                            
                                            <?php foreach ($meseros as $m): ?>
                                                <option value="<?php echo $m['idusuario_sistema'] ?>"><?php echo $m['nombresusuario_sistema'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <!-- Tabla de aperturas -->
                                    <div class="col-md-8">
                                        <label class="form-label">Movimientos de caja</label>

                                        <div class="border rounded" style="max-height:60px; overflow:auto">
                                            <table class="table table-sm table-hover mb-0">

                                                <thead class="table-light">
                                                    <tr>
                                                        <th></th>
                                                        <th>Apertura</th>
                                                        <th>Cierre</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php foreach ($aperturas as $a): ?>
                                                        <tr>
                                                            <td>
                                                                <input
                                                                    type="radio"
                                                                    name="id_apertura"
                                                                    id="id_apertura"
                                                                    value="<?php echo $a['id_apertura'] ?>">
                                                            </td>
                                                            <td>
                                                                <?php
                                                                echo date('Y-m-d h:i A', strtotime($a['fecha_apertura'] . " " . $a['hora_apertura']));
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                echo date('Y-m-d h:i A', strtotime($a['fecha_cierre'] . " " . $a['hora_cierre']));
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ?>
                                                </tbody>

                                            </table>

                                        </div>
                                    </div>

                                    <!-- Botón -->
                                    <div class="col-12 text-end">
                                        <button class="btn btn-success w-100" onclick="consultarMovimientoCaja()">
                                            Buscar
                                        </button>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            


            <table class="table table-striped mt-4">
                <thead class="table-dark">
                    <tr>
                        <td>Mesero</th>
                    
                        <td>Facturas</th>
                        <td>Ventas</th>
                        <td>Promedio ventas</th>
                        <td>Propina</th>
                        <td>Promedio propina</th>
                    </tr>
                </thead>

                <tbody id="datosUsuario">

                    <?= view('reportes/ventas_mesero') ?>

                </tbody>

            </table>


        </div>



        <div id="processing-bar" style="display: none;">
            <p class="text-primary h3">Procesando petición</p>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
            </div>
        </div>
        <div class="my-3"></div> <!-- Added space between the buttons and the table -->


        <input type="hidden" value="<?php echo base_url() ?> " id="url">
    </div>
</div>
<!-- Sweet alert -->
<script src="<?php echo base_url(); ?>/Assets/plugin/sweet-alert2/sweetalert2@11.js"></script>
<script src="<?= base_url() ?>/Assets/script_js/nuevo_desarrollo/sweet_alert_centrado.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
async function bucarVentaMesero() {

    // Obtener valores correctamente
    const id_mesero = document.getElementById('id_mesero').value;
    const fecha_inicial = document.getElementById('fecha_inicial').value;
    const fecha_final = document.getElementById('fecha_final').value;

    // Crear FormData
    const formData = new FormData();
    formData.append('id_mesero', id_mesero);
    formData.append('fecha_inicial', fecha_inicial);
    formData.append('fecha_final', fecha_final);

    // Mostrar spinner
    Swal.fire({
        title: 'Generando reporte...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        const response = await fetch("<?= base_url('reportes/ventasPorMesero') ?>", {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        Swal.close();

        if (data.response=="success") {
            Swal.fire({
                icon: 'success',
                title: 'Reporte generado',
                text: 'Ventas del mesero cargadas correctamente'
            });

            document.getElementById('datosUsuario').innerHTML=data.ventas

        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Sin resultados',
                text: 'No se encontraron ventas para los criterios seleccionados'
            });
        }

    } catch (error) {
        Swal.close();
        console.error('Error al buscar ventas:', error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al consultar las ventas'
        });
    }
}
</script>

<script>
async function consultarMovimientoCaja() {

    // Obtener valores correctamente
    const id_mesero = document.getElementById('idMesero').value;
    const id_apertura = document.getElementById('id_apertura').value;
    

    // Crear FormData
    const formData = new FormData();
    formData.append('id_mesero', id_mesero);
    formData.append('id_apertura', id_apertura);


    // Mostrar spinner
    Swal.fire({
        title: 'Generando reporte...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        const response = await fetch("<?= base_url('reportes/ventasPorApertura') ?>", {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        Swal.close();

        if (data.response=="success") {
            Swal.fire({
                icon: 'success',
                title: 'Reporte generado',
                text: 'Ventas del mesero cargadas correctamente'
            });

            document.getElementById('datosUsuario').innerHTML=data.ventas

        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Sin resultados',
                text: 'No se encontraron ventas para los criterios seleccionados'
            });
        }

    } catch (error) {
        Swal.close();
        console.error('Error al buscar ventas:', error);

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al consultar las ventas'
        });
    }
}
</script>




<script>
    document.addEventListener("DOMContentLoaded", () => {

        const form = document.getElementById("formReporteMeseros");
        const btnBuscar = document.getElementById("btnBuscar");
        const resultado = document.getElementById("resultadoBusqueda");

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const datos = new FormData(form);

            const fechaInicial = datos.get("fecha_inicial");
            const fechaFinal = datos.get("fecha_final");

            // Validación profesional
            if (fechaInicial > fechaFinal) {
                alert("La fecha inicial no puede ser mayor que la fecha final");
                return;
            }

            try {

                btnBuscar.disabled = true;
                btnBuscar.innerHTML = "Buscando...";

                const response = await fetch("<?= base_url('reportes/ventasMesero') ?>", {
                    method: "POST",
                    body: datos
                });

                if (!response.ok) {
                    throw new Error("Error en la solicitud");
                }

                const html = await response.text();

                resultado.innerHTML = html;

            } catch (error) {

                console.error(error);
                resultado.innerHTML = `
                <div class="alert alert-danger">
                    Ocurrió un error al consultar los datos
                </div>
            `;

            } finally {

                btnBuscar.disabled = false;
                btnBuscar.innerHTML = "Buscar";

            }

        });

    });
</script>


<script>
    function cambiarFiltro() {

        let tipo = document.getElementById("tipo_reporte").value;

        let mesero = document.querySelector(".filtro_mesero");
        let caja = document.querySelector(".filtro_fechas");

        // ocultar todo primero
        mesero.classList.add("d-none");
        caja.classList.add("d-none");

        // mostrar según selección
        if (tipo === "mesero") {
            mesero.classList.remove("d-none");
        }

        if (tipo === "caja") {
            caja.classList.remove("d-none");
        }

    }
</script>




































<?= $this->endSection('content') ?>