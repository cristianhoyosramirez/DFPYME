<?php $user_session = session(); ?>
<?= $this->extend('template/consultas_reportes') ?>
<?= $this->section('title') ?>
REPORTE DE VENTAS DIARIAS
<?= $this->endSection('title') ?>
<?= $this->section('content') ?>
<!--Star Breadcum-->

<div class="container">
    <div class="row align-items-center position-relative mb-3">

        <!-- Botón regresar -->
        <div class="col-auto position-absolute start-0">
            <a class="nav-link">
                <img
                    style="cursor:pointer;"
                    src="<?php echo base_url(); ?>/Assets/img/atras.png"
                    width="20"
                    height="20"
                    onClick="history.go(-1);"
                    title="Sección anterior">
            </a>
        </div>

        <!-- Título centrado -->
        <div class="col-12 text-center">
            <p class="text-primary h3 mb-0">
                Reporte de egresos
            </p>
        </div>

    </div>
</div>


<div class="card container">
    <div class="card-body">
        <!--     <div class="row">
           
            <div class="col-md-4">
                <label for="inputEmail4">Desde</label>
                <input type="date" class="form-control" id="fecha_inicial" value="<?php echo date('Y-m-d') ?>"> <br>
            </div>
            <div class="col-md-4">
                <label for="inputEmail4">Hasta </label>
                <input type="date" class="form-control" id="fecha_final" value="<?php echo date('Y-m-d') ?>">
            </div>

            <div class="col-4"> <br>
                <button type="button" onclick="reporte_movimiento_efectivo()" class="btn btn-primary">Buscar</button>
            </div>

        </div> -->



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
                        Cuentas 
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
                                            <td>Nombre</th>
                                            
                                        </tr>

                                    </thead>

                                    <tbody id="reporte_cortesias">

                                        <?= $this->include('cortesias/cortesias') ?>

                                    </tbody>

                                </table>

                            </div>


                            <!-- TOTAL SIEMPRE VISIBLE -->
                            <div class="table-responsive flex-shrink-0">

                                <table class="table table-dark fw-bold mb-0">

                                    <tfoot class="table-dark fw-bold">

                                        <tr>

                                            <td colspan="4" class="text-end">
                                                TOTAL REGISTROS
                                            </td>

                                            <td class="text-center" id="total_registros">
                                               
                                            </td>

                                            <td class="text-end">
                                                TOTAL CUENTAS
                                            </td>

                                            <td class="text-end" id="total_ventas">
                                               
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
                        Sub cuenta
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



        <input type="hidden" value="<?= base_url() ?>" id="url"> <br>

        <div id="reporte_flujo_efectivo">

        </div>

    </div>
</div>

<?= $this->endSection('content') ?>