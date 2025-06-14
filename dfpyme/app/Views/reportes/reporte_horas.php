<?php $session = session(); ?>
<?php $user_session = session(); ?>
<?= $this->extend('template/home') ?>
<?= $this->section('title') ?>
REPORTE DE VENTAS POR HORA
<?= $this->endSection('title') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <p class="text-center text-primary h3 ">Reporte de ventas por horas </p>
    <div class="card">
        <div class="card-body">
            <div class="container mt-4">


                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row mb-3 align-items-end">

                                <!-- Fecha de inicio -->
                                <div class="col-2">
                                    <label for="fecha_inicio" class="form-label">Fecha Inicio:</label>
                                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>

                                <!-- Hora de inicio -->
                                <div class="col-2">
                                    <label for="hora_inicio" class="form-label">Hora Inicio:</label>
                                    <input type="time" id="hora_inicio" name="hora_inicio" class="form-control" value="<?= date('H:00') ?>">
                                </div>

                                <!-- Fecha de fin -->
                                <div class="col-2">
                                    <label for="fecha_fin" class="form-label">Fecha Fin:</label>
                                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>

                                <!-- Hora de fin -->
                                <div class="col-2">
                                    <label for="hora_fin" class="form-label">Hora Fin:</label>
                                    <input type="time" id="hora_fin" name="hora_fin" class="form-control" value="<?= date('H:00', strtotime('+1 hour')) ?>">
                                </div>

                                <!-- Botón Buscar -->
                                <div class="col-2">
                                    <button type="button" class="btn btn-outline-success w-100" onclick="buscarDatos()">Buscar</button>
                                </div>

                                <!-- Botón Excel -->
                                <div class="col-1">
                                    <button type="submit" name="export" value="excel" class="btn btn-outline-success w-100">Excel</button>
                                </div>

                                <!-- Botón PDF -->
                                <div class="col-1">
                                    <button type="submit" name="export" value="pdf" class="btn btn-outline-success w-100">PDF</button>
                                </div>

                            </div>
                        </form>


                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">

                                <tr class="table-dark">
                                    <td scope="col">Código</th>
                                    <td scope="col">Producto</th>
                                    <td scope="col">Cantidad</th>
                                    <td scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($hora = 0; $hora < 24; $hora++) {
                                    //$productos
                                    $inicio = date("g A", strtotime("$hora:00"));
                                    $fin = date("g A", strtotime(($hora + 1) . ":00"));

                                    echo "<tr><td colspan='4'><strong>HORA : {$inicio} - {$fin}</strong></td></tr>";

                                    // Aquí puedes insertar los productos que pertenecen a esa hora
                                    // A modo de ejemplo, agregamos un producto fijo
                                    echo "<tr>";
                                    echo "<td>001</td>";
                                    echo "<td>Arroz 1kg</td>";
                                    echo "<td>3</td>";
                                    echo "<td>$12.00</td>";
                                    echo "</tr>";
                                }
                                ?>

                                <tr>
                                    <td>002</td>
                                    <td>Leche Entera</td>
                                    <td>2</td>
                                    <td>$6.00</td>
                                </tr>
                                <tr>
                                    <td>003</td>
                                    <td>Pan Integral</td>
                                    <td>1</td>
                                    <td>$2.50</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    async function buscarDatos() {
        try {
            // Captura de valores del formulario
            const fechaInicio = document.getElementById("fecha_inicio").value;
            const horaInicio = document.getElementById("hora_inicio").value;
            const fechaFin = document.getElementById("fecha_fin").value;
            const horaFin = document.getElementById("hora_fin").value;

            // Unimos fecha y hora en formato completo tipo timestamp
            const datetimeInicio = `${fechaInicio} ${horaInicio}:00`;
            const datetimeFin = `${fechaFin} ${horaFin}:59`;

            const datos = {
                inicio: datetimeInicio,
                fin: datetimeFin
            };


            const response = await fetch("<?= base_url('pedidos/reporteHoras') ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            });

            if (!respuesta.ok) {
                throw new Error(`Error HTTP: ${respuesta.status}`);
            }

            const resultado = await respuesta.json();
            console.log(resultado); // Puedes mostrarlo en tabla o consola

            mostrarResultadosEnTabla(resultado);

        } catch (error) {
            console.error('Error al buscar datos:', error);
        }
    }

    function mostrarResultadosEnTabla(datos) {
        const tabla = document.getElementById("tabla_resultados");
        tabla.innerHTML = "";

        datos.forEach(item => {
            const fila = `
                <tr>
                    <td>${item.nombreproducto}</td>
                    <td>${item.hora}</td>
                    <td>${item.total_cantidad}</td>
                    <td>${item.total_valor}</td>
                </tr>
            `;
            tabla.innerHTML += fila;
        });
    }
</script>



<?= $this->endSection('content') ?>