<?php

namespace App\Libraries;

class data_table
{
    public function row_data_table($id_estado, $id_factura, $saldo)
    {

        $sub_array = [];

        switch ($id_estado) {

            /*
                |--------------------------------------------------------------------------
                | FACTURA ELECTRÓNICA
                |--------------------------------------------------------------------------
                */
            case 8:

                $status = model('facturaElectronicaModel')
                    ->select('id_status,pdf_url')
                    ->where('id', $id_factura)
                    ->first();

                $tieneNc = model('notaCreditoModel')
                    ->where('id_factura', $id_factura)
                    ->countAllResults() > 0;

                //print_r($tieneNc['id']);



                $acciones = '';

                switch ($status['id_status']) {

                    /*
            |--------------------------------------------------------------------------
            | DOCUMENTO NO ENVIADO
            |--------------------------------------------------------------------------
            */
                    case 1:

                        $config = model('configuracionPedidoModel')
                            ->select('borrarFe')
                            ->first();

                        // ORDEN UNIFICADO
                        $acciones .= $this->btnImprimirElectronica($id_factura);
                        $acciones .= $this->btnDetalleFE($id_factura);
                        $acciones .= $this->btnEditar($id_factura, $id_estado);
                        //$acciones .= $this->btnEnviar($id_factura);

                        if ($config['borrarFe'] == 't') {
                            $acciones .= $this->btnEliminarFE($id_factura);
                        }

                        break;


                    /*
            |--------------------------------------------------------------------------
            | DOCUMENTO ACEPTADO
            |--------------------------------------------------------------------------
            */
                    case 2:

                        // ORDEN UNIFICADO
                        $acciones .= $this->btnImprimirElectronica($id_factura);
                        $acciones .= $this->btnDetalleFE($id_factura);
                        //$acciones .= $this->notaCredito($id_factura);

                        if (!$tieneNc) {
                            $acciones .= $this->notaCredito($id_factura);
                        }


                        break;


                    /*
            |--------------------------------------------------------------------------
            | DOCUMENTO RECHAZADO
            |--------------------------------------------------------------------------
            */
                    case 3:

                        // ORDEN UNIFICADO
                        $acciones .= $this->btnImprimirElectronica($id_factura);
                        $acciones .= $this->btnDetalleFE($id_factura);
                        $acciones .= $this->btnReenviar($id_factura);

                        break;


                    /*
            |--------------------------------------------------------------------------
            | ERROR ENVÍO
            |--------------------------------------------------------------------------
            */
                    case 4:

                        // ORDEN UNIFICADO
                        $acciones .= $this->btnImprimirElectronica($id_factura);
                        $acciones .= $this->btnDetalleFE($id_factura);
                        $acciones .= $this->btnEditar($id_factura, $id_estado);
                        //$acciones .= $this->btnReintentar($id_factura);

                        break;
                }

                /*
                |--------------------------------------------------------------------------
                | SALDO PENDIENTE
                |--------------------------------------------------------------------------
                */
                if ($saldo > 0) {

                    $acciones .= $this->btnSaldo($id_factura, $id_estado);
                    //$acciones .= $this->btnAbono($id_factura);
                }

                //$sub_array[] = $this->dropdownAcciones($acciones);

                $sub_array[] = $this->dropdownAcciones(
                    $acciones,
                    $id_estado,
                    $id_factura,
                    $status
                );

                break;



            /*
                |--------------------------------------------------------------------------
                | FACTURA CRÉDITO
                |--------------------------------------------------------------------------
                */
            case 2:

                $acciones = '';

                // ORDEN UNIFICADO
                $acciones .= $this->btnImprimirDuplicado($id_factura);
                $acciones .= $this->btnDetalleFactura($id_factura);
                $acciones .= $this->btnEditar($id_factura, $id_estado);

                if ($saldo > 0) {

                    $acciones .= $this->btnSaldo($id_factura, $id_estado);
                    //$acciones .= $this->btnAbono($id_factura);
                }

                $sub_array[] = $this->dropdownAcciones($acciones);

                break;



            /*
                    |--------------------------------------------------------------------------
                    | FACTURA CONTADO
                    |--------------------------------------------------------------------------
                    */
            case 1:
            case 6:

                $acciones = '';

                // ORDEN UNIFICADO
                $acciones .= $this->btnImprimirDuplicado($id_factura);
                $acciones .= $this->btnDetalleFactura($id_factura);
                $acciones .= $this->btnEditar($id_factura, $id_estado);

                if ($saldo > 0) {

                    $acciones .= $this->btnSaldo($id_factura, $id_estado);
                    //$acciones .= $this->btnAbono($id_factura);
                }

                $sub_array[] = $this->dropdownAcciones($acciones);

                break;



            /*
                        |--------------------------------------------------------------------------
                        | PREFACTURA
                        |--------------------------------------------------------------------------
                        */
            case 7:

                $acciones = '';

                // ORDEN UNIFICADO
                $acciones .= $this->btnImprimirDuplicado($id_factura);
                $acciones .= $this->btnDetalleFactura($id_factura);
                $acciones .= $this->btnEditar($id_factura, $id_estado);

                $sub_array[] = $this->dropdownAcciones($acciones);

                break;
        }

        return $sub_array;
    }



    /*
    |--------------------------------------------------------------------------
    | DROPDOWN ACCIONES
    |--------------------------------------------------------------------------
    */

    /*  private function dropdownAcciones($acciones)
{
    return '

    <div class="d-flex align-items-center gap-2">

        <!-- Nuevo botón -->
        <button
            type="button"
            class="btn btn-primary shadow-sm"
            title="Nueva acción">

            <svg xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2">

                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>

            </svg>

        </button>

        <!-- Dropdown -->
        <div class="dropdown">

            <button
                class="btn btn-light border shadow-sm"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                title="Acciones"
                style="
                    width:42px;
                    height:42px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                ">

                <svg xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    viewBox="0 0 24 24"
                    fill="currentColor">

                    <circle cx="12" cy="5" r="1.8"/>
                    <circle cx="12" cy="12" r="1.8"/>
                    <circle cx="12" cy="19" r="1.8"/>

                </svg>

            </button>

            <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2"
                style="min-width:270px;">

                <div class="d-flex flex-column gap-1">
                    ' . $acciones . '
                </div>

            </div>

        </div>

    </div>
    ';
} */


    private function dropdownAcciones(
        $acciones,
        $id_estado = null,
        $id_factura = null,
        $status = null
    ) {

        $botonRapido = '';
        $botonNc = '';

        /*
    |--------------------------------------------------------------------------
    | BOTÓN RÁPIDO FACTURA ELECTRÓNICA
    |--------------------------------------------------------------------------
    */
        if (
            $id_estado == 8 &&
            !empty($status)
        ) {

            //echo $status['id_status'];

            switch ($status['id_status']) {

                /*
                        |--------------------------------------------------------------------------
                        | DOCUMENTO NO ENVIADO
                        |--------------------------------------------------------------------------
                        */
                case 1:

                    $botonRapido = '

                <button
                    type="button"
                    class="btn btn-outline-dark btn-icon shadow-sm"
                    onclick="sendInvoice(' . $id_factura . ')"
                    title="Enviar DIAN"
                    style="
                        width:42px;
                        height:42px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                    ">

                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                <path d="M17 21l4 -4" />
                <path d="M3 7l9 6l9 -6" />
            </svg>

                </button>';

                    break;

                /*
            |--------------------------------------------------------------------------
            | DOCUMENTO ACEPTADO
            |--------------------------------------------------------------------------
            */
                case 2:

                    $botonRapido = '
<a
    href="' . $status['pdf_url'] . '"
    target="_blank"
    class="btn btn-outline-danger shadow-sm"
    title="Ver PDF"
    style="
        width:42px;
        height:42px;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:0;
        border-radius:8px;
    ">

    <img
        src="' . base_url() . '/Assets/img/pdf.png"
        alt="PDF"
        style="
            width:24px;
            height:24px;
            object-fit:contain;
        ">

</a>


';

                    break;

                /*
            |--------------------------------------------------------------------------
            | DOCUMENTO RECHAZADO
            |--------------------------------------------------------------------------
            */
                case 3:

                    $botonRapido = '

                <button
                    type="button"
                    class="btn btn-danger shadow-sm"
                    onclick="sendInvoice(' . $id_factura . ')"
                    title="Reenviar documento"
                    style="
                        width:42px;
                        height:42px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                    ">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2">

                        <path d="M5 12h14"></path>
                        <path d="M13 5l7 7l-7 7"></path>

                    </svg>

                </button>';

                    break;

                /*
            |--------------------------------------------------------------------------
            | ERROR DE ENVÍO
            |--------------------------------------------------------------------------
            */
                case 4:

                    $botonRapido = '

                <button
                    type="button"
                    class="btn btn-outline-dark shadow-sm btn-icon"
                    onclick="sendInvoiceDian(' . $id_factura . ')"
                    title="Reintentar envío"
                    style="
                        width:42px;
                        height:42px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                    ">

                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                <path d="M17 21l4 -4" />
                <path d="M3 7l9 6l9 -6" />
            </svg>

                </button>';

                    break;
            }
        }

        return '

    <div class="d-flex align-items-center gap-2">

        ' . $botonRapido . '

        <div class="dropdown">

            <button
                class="btn btn-light border shadow-sm"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                title="Acciones"
                style="
                    width:42px;
                    height:42px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                ">

                <svg xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    viewBox="0 0 24 24"
                    fill="currentColor">

                    <circle cx="12" cy="5" r="1.8"/>
                    <circle cx="12" cy="12" r="1.8"/>
                    <circle cx="12" cy="19" r="1.8"/>

                </svg>

            </button>

            <div
                class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2"
                style="min-width:270px;">

                <div class="d-flex flex-column gap-1">

                    ' . $acciones . '

                </div>

            </div>

        </div>

    </div>';
    }


    /*
    |--------------------------------------------------------------------------
    | ITEM MENU
    |--------------------------------------------------------------------------
    */

    private function itemMenu($color, $titulo, $descripcion, $onclick, $icono)
    {
        return '

        <a class="dropdown-item d-flex align-items-center gap-3  py-2 px-2"
            style="
                cursor:pointer;
                transition:all .2s ease;
            "
            onclick="' . $onclick . '">

            <div class="btn btn-outline-' . $color . ' btn-icon m-0 "
                style="
                    width:42px;
                    height:42px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    flex-shrink:0;
                ">

                ' . $icono . '

            </div>

            <div class="d-flex flex-column">

                <span class="fw-semibold text-dark"
                    style="
                        font-size:14px;
                        line-height:1.1;
                    ">

                    ' . $titulo . '

                </span>

                <small class="text-muted"
                    style="
                        font-size:12px;
                    ">

                    ' . $descripcion . '

                </small>

            </div>

        </a>
        ';
    }



    /*
    |--------------------------------------------------------------------------
    | BOTONES
    |--------------------------------------------------------------------------
    */

    private function btnEnviar($id)
    {
        return $this->itemMenu(
            'dark',
            'Enviar DIAN',
            'Documento pendiente',
            'sendInvoice(' . $id . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                <path d="M17 21l4 -4" />
                <path d="M3 7l9 6l9 -6" />
            </svg>
            '
        );
    }

    private function btnReenviar($id)
    {
        return $this->itemMenu(
            'danger',
            'Reenviar documento',
            'Documento rechazado',
            'sendInvoice(' . $id . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                <path d="M17 21l4 -4" />
                <path d="M3 7l9 6l9 -6" />
            </svg>
            '
        );
    }

    private function btnReintentar($id)
    {
        return $this->itemMenu(
            'dark',
            'Reintentar envío',
            'Error de comunicación',
            'sendInvoiceDian(' . $id . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                <path d="M17 21l4 -4" />
                <path d="M3 7l9 6l9 -6" />
            </svg>
            '
        );
    }

    private function btnImprimirElectronica($id)
    {
        return $this->itemMenu(
            'success',
            'Imprimir',
            'Factura electrónica',
            'imprimir_electronica(' . $id . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                <path d="M17 9V5a2 2 0 0 0 -2 -2H9a2 2 0 0 0 -2 2v4" />
                <rect x="7" y="13" width="10" height="8" rx="2"/>
            </svg>
            '
        );
    }

    private function btnImprimirDuplicado($id)
    {
        return $this->itemMenu(
            'success',
            'Imprimir copia',
            'Generar duplicado',
            'imprimir_duplicado_factura(' . $id . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                <path d="M17 9V5a2 2 0 0 0 -2 -2H9a2 2 0 0 0 -2 2v4" />
                <rect x="7" y="13" width="10" height="8" rx="2"/>
            </svg>
            '
        );
    }

    private function btnDetalleFE($id)
    {
        return $this->itemMenu(
            'secondary',
            'Ver detalle',
            'Consultar documento',
            'detalle_f_e(' . $id . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="2" />
                <path d="M22 12c-2.667 4.667 -6 7 -10 7S4.667 16.667 2 12C4.667 7.333 8 5 12 5s7.333 2.333 10 7" />
            </svg>
            '
        );
    }

    private function btnDetalleFactura($id)
    {
        return $this->itemMenu(
            'secondary',
            'Ver detalle',
            'Consultar productos y valores',
            'detalle_de_factura(' . $id . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="2" />
                <path d="M22 12c-2.667 4.667 -6 7 -10 7S4.667 16.667 2 12C4.667 7.333 8 5 12 5s7.333 2.333 10 7" />
            </svg>
            '
        );
    }

    private function btnEditar($id, $id_estado)
    {
        return $this->itemMenu(
            'warning',
            'Editar factura',
            'Modificar información',
            'editar_factura(' . $id . ', ' . $id_estado . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 6l3 3l-9 9h-3v-3z"/>
                <path d="M18 3l3 3"/>
            </svg>
            '
        );
    }

    private function btnEliminarFE($id)
    {
        return $this->itemMenu(
            'danger',
            'Eliminar documento',
            'Acción irreversible',
            'eliminar_f_e(' . $id . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="0" fill="currentColor">
                <path d="M20 6H4l1 14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2l1-14z" />
                <path d="M14 2a2 2 0 0 1 2 2H8a2 2 0 0 1 2-2h4z" />
            </svg>
            '
        );
    }

    private function btnPdf($url)
    {
        return '

        <a href="' . $url . '"
            target="_blank"
            class="dropdown-item d-flex align-items-center gap-3  py-2 px-2">

           <div class="btn btn-outline-danger btn-icon m-0"
                style="
                    width:42px;
                    height:42px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    flex-shrink:0;
                ">

                <img src="' . base_url() . '/Assets/img/pdf.png"
                    width="30"
                    height="30">

            </div>

            <div class="d-flex flex-column">

                <span class="fw-semibold text-dark"
                    style="
                        font-size:14px;
                        line-height:1.1;
                    ">

                    Descargar PDF

                </span>

                <small class="text-muted"
                    style="
                        font-size:12px;
                    ">

                    Abrir documento electrónico

                </small>

            </div>

        </a>
        ';
    }

    private function btnSaldo($id_factura, $id_estado)
    {
        return $this->itemMenu(
            'danger',
            'Ver saldo',
            'Consultar saldo pendiente',
            'ver_saldo(' . $id_factura . ', ' . $id_estado . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-dollar">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                <path d="M12 17v1m0 -8v1" />
            </svg>
            '
        );
    }

    private function btnAbono($id)
    {
        return $this->itemMenu(
            'primary',
            'Registrar pago',
            'Aplicar abono a crédito',
            'abono_credito(' . $id . ')',
            '
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cash-banknote" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <rect x="3" y="6" width="18" height="12" rx="2" />
                <circle cx="12" cy="12" r="2" />
                <path d="M6 12h.01M18 12h.01" />
            </svg>
            '
        );
    }

    private function notaCredito($id)
    {
        return $this->itemMenu(
            'secondary',
            'Registrar nota crédito',
            'Realizar nota crédito',
            'nota_credito(' . $id . ')',
            '
                  <svg xmlns="http://www.w3.org/2000/svg"
            class="icon icon-tabler icon-tabler-file-x"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round">

            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
            <path d="M17 21h-10a2 2 0 0 1-2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2"/>
            <path d="M10 12l4 4"/>
            <path d="M14 12l-4 4"/>

        </svg>
            '
        );
    }
}
