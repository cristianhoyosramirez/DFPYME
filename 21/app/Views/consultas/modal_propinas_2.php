<!-- Modal -->
<div class="modal fade" id="modal_propinas" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" style="max-width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Consulta de ingresos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="tabla_propinas"></div>
            </div>
            <div class="modal-footer">
                <!-- <table class="table">

                    <tbody class="table-scroll">

                        <tr>
                            <td class="table-dark">VENTAS POS </td>
                            <td class="table-dark">ELECTRÓNICA </td>
                           
                            <td class="table-dark">VALOR NETO </td>
                            <td class="table-dark">PROPINA</td>
                            <td class="table-dark">TOTAL DOCUMENTO </td>
                            <td class="table-dark">EFECTIVO </td>
                            <td class="table-dark">TRANSFERENCIA </td>
                            
                            <td class="table-dark">TOTAL INGRESOS</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <p id="ventas_pos"></p>
                            </td>
                            <td>
                                <p id="ventas_electronicas"></p>
                            </td>
                            
                            <td><p class="text-primary h3" id="total_ventas" ></p> </td>
                            <td>
                                <p id="valor_total_propinas"> </p>
                            </td>
                            <td>
                                <p id="total_documento"></p>
                            </td>
                            <td>
                                <p id="efectivo"></p>
                            </td>
                            <td>
                                <p id="transferencia">
                            </td>
                            
                            <td>
                                <p id="total_de_ingresos"></p>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table> -->
                <!-- <table class="table table-bordered table-hover align-middle text-center">

                    <tbody class="table-scroll">

                        
                        <tr>

                            <td class="table-dark">VENTAS POS</td>

                            <td class="table-dark">ELECTRÓNICA</td>

                            <td class="table-dark">VALOR NETO</td>

                            <td class="table-dark">PROPINA</td>

                            <td class="table-dark">TOTAL DOCUMENTO</td>

                            <td class="table-dark">EFECTIVO</td>

                            <td class="table-dark">TRANSFERENCIA</td>

                            
                            <td class="table-success">
                                ABONOS
                            </td>

                           
                            <td class="table-primary">
                                TOTAL INGRESOS
                            </td>

                        </tr>

                       
                        <tr>

                            <td>
                                <p id="ventas_pos"></p>
                            </td>

                            <td>
                                <p id="ventas_electronicas"></p>
                            </td>

                            <td>
                                <p class="text-primary h5 fw-bold"
                                    id="total_ventas">
                                </p>
                            </td>

                            <td>
                                <p id="valor_total_propinas"></p>
                            </td>

                            <td>
                                <p id="total_documento"></p>
                            </td>

                            <td>
                                <p id="efectivo"></p>
                            </td>

                            <td>
                                <p id="transferencia"></p>
                            </td>

                           
                            <td>

                                <p class="text-success fw-bold h5"
                                    id="total_abonos">

                                    $ 0

                                </p>

                            </td>

                            
                            <td>

                                <p class="text-primary fw-bold h4"
                                    id="total_de_ingresos">

                                    $ 0

                                </p>

                            </td>

                        </tr>

                    </tbody>

                </table> -->


                <table class="table table-bordered table-hover align-middle text-center">

                    <tbody class="table-scroll">

                      <style>
    .tabla-ingresos {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        font-family: Arial, sans-serif;
    }

    .tabla-ingresos thead td {
        padding: 14px;
        font-weight: 600;
        text-align: center;
        font-size: 14px;
        border: none;
    }

    .tabla-ingresos tbody td {
        background: #ffffff;
        padding: 18px 12px;
        text-align: center;
        border-bottom: 1px solid #e5e7eb;
    }

    .tabla-ingresos tbody p {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
        color: #1f2937;
    }

    /* COLORES TITULOS */

    .th-cortesia{
        background-color:#ede9fe;
        color:#5b21b6;
    }

    .th-credito{
        background-color:#e2e8f0;
        color:#334155;
    }

    .th-contado{
        background-color:#dcfce7;
        color:#166534;
    }

    .th-abonos{
        background-color:#fef3c7;
        color:#92400e;
    }

    .th-efectivo{
        background-color:#dbeafe;
        color:#1d4ed8;
    }

    .th-banco{
        background-color:#cffafe;
        color:#0f766e;
    }

    .th-total{
        background-color:#2563eb;
        color:white;
    }

    /* EFECTO HOVER */

    .tabla-ingresos tbody tr:hover td{
        background-color:#f9fafb;
        transition: 0.2s;
    }
</style>


<table class="tabla-ingresos">

    <thead>
        <tr>

            <td class="th-contado">
                Cortesías
            </td>

            <td class="th-contado">
                Ventas crédito
            </td>

            <td class="th-credito">
                Ventas contado
            </td>
            <td class="th-credito">
                Total ventas 
            </td>

            <td class="th-credito">
                Abonos
            </td>

            <td class="th-credito">
                Ingresos efectivo
            </td>

            <td class="th-credito">
                Ingresos banco
            </td>

            <td class="th-total">
                Total ingresos
            </td>

        </tr>
    </thead>

    <tbody>
        <tr>

            <td>
                <p id="cortesias">$ 120.000</p>
            </td>

            <td>
                <p id="ventas_credito">$ 850.000</p>
            </td>

            <td>
                <p id="ventas_contado">$ 1.450.000</p>
            </td>
            <td>
                <p id="total_de_ventas">$ 1.450.000</p>
            </td>

            <td>
                <p id="abonos">$ 320.000</p>
            </td>

            <td>
                <p id="ingresos_en_efectivo">$ 980.000</p>
            </td>

            <td>
                <p id="ingresos_banco">$ 1.640.000</p>
            </td>

            <td>
                <p id="totalidad_ingresos">$ 2.620.000</p>
            </td>

        </tr>
    </tbody>

</table>

                    </tbody>

                </table>


            </div>
        </div>
    </div>
</div>