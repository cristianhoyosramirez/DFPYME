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

                        $acciones .= $this->btnEnviar($id_factura);
                        $acciones .= $this->btnImprimirElectronica($id_factura);
                        $acciones .= $this->btnDetalleFE($id_factura);
                        $acciones .= $this->btnEditar($id_factura);

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

                        $acciones .= $this->btnImprimirElectronica($id_factura);
                        $acciones .= $this->btnDetalleFE($id_factura);
                        $acciones .= $this->btnPdf($status['pdf_url']);

                        break;


                    /*
                    |--------------------------------------------------------------------------
                    | DOCUMENTO RECHAZADO
                    |--------------------------------------------------------------------------
                    */
                    case 3:

                        $acciones .= $this->btnReenviar($id_factura);
                        $acciones .= $this->btnImprimirElectronica($id_factura);
                        $acciones .= $this->btnDetalleFE($id_factura);

                        break;


                    /*
                    |--------------------------------------------------------------------------
                    | ERROR ENVÍO
                    |--------------------------------------------------------------------------
                    */
                    case 4:

                        $acciones .= $this->btnReintentar($id_factura);
                        $acciones .= $this->btnImprimirElectronica($id_factura);
                        $acciones .= $this->btnDetalleFE($id_factura);
                        $acciones .= $this->btnEditar($id_factura);

                        break;
                }

                /*
                |--------------------------------------------------------------------------
                | SALDO PENDIENTE
                |--------------------------------------------------------------------------
                */
                if ($saldo > 0) {

                    $acciones .= $this->btnSaldo($id_factura, $id_estado);

                    // NUEVO BOTÓN DE PAGO
                   
                }

                $sub_array[] = $acciones;

                break;



            /*
            |--------------------------------------------------------------------------
            | FACTURA CRÉDITO
            |--------------------------------------------------------------------------
            */
            case 2:

                $acciones = '';

                $acciones .= $this->btnImprimirDuplicado($id_factura);
                $acciones .= $this->btnDetalleFactura($id_factura);
                
                $acciones .= $this->btnEditar($id_factura);

                if ($saldo > 0) {
                    $acciones .= $this->btnSaldo($id_factura, $id_estado);
                }

                $sub_array[] = $acciones;

                break;



            /*
            |--------------------------------------------------------------------------
            | FACTURA CONTADO
            |--------------------------------------------------------------------------
            */
            case 1:
            case 6:

                $acciones = '';

                $acciones .= $this->btnImprimirDuplicado($id_factura);

                if ($saldo > 0) {

                    $acciones .= $this->btnSaldo($id_factura, $id_estado);

                    // NUEVO BOTÓN DE PAGO
                
                }

                $acciones .= $this->btnDetalleFactura($id_factura);
                $acciones .= $this->btnEditar($id_factura);

                $sub_array[] = $acciones;

                break;



            /*
            |--------------------------------------------------------------------------
            | PREFACTURA
            |--------------------------------------------------------------------------
            */
            case 7:

                $acciones = '';

                $acciones .= $this->btnImprimirDuplicado($id_factura);
                $acciones .= $this->btnDetalleFactura($id_factura);
               
                $acciones .= $this->btnEditar($id_factura);

                $sub_array[] = $acciones;

                break;
        }

        return $sub_array;
    }



    /*
    |--------------------------------------------------------------------------
    | BOTONES
    |--------------------------------------------------------------------------
    */

    private function btnEnviar($id)
    {
        return '
        <a class="btn btn-outline-dark btn-icon" title="Documento no enviado" onclick="sendInvoice(' . $id . ')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                <path d="M17 21l4 -4" />
                <path d="M3 7l9 6l9 -6" />
            </svg>
        </a>';
    }

    private function btnReenviar($id)
    {
        return '
        <a class="btn btn-outline-danger btn-icon" title="Reenviar documento rechazado" onclick="sendInvoice(' . $id . ')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                <path d="M17 21l4 -4" />
                <path d="M3 7l9 6l9 -6" />
            </svg>
        </a>';
    }

    private function btnReintentar($id)
    {
        return '
        <a class="btn btn-outline-dark btn-icon" title="Reintentar envío" onclick="sendInvoiceDian(' . $id . ')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                <path d="M17 21l4 -4" />
                <path d="M3 7l9 6l9 -6" />
            </svg>
        </a>';
    }

    private function btnImprimirElectronica($id)
    {
        return '
        <a class="btn btn-outline-success btn-icon" title="Imprimir" onclick="imprimir_electronica(' . $id . ')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                <path d="M17 9V5a2 2 0 0 0 -2 -2H9a2 2 0 0 0 -2 2v4" />
                <rect x="7" y="13" width="10" height="8" rx="2"/>
            </svg>
        </a>';
    }

    private function btnImprimirDuplicado($id)
    {
        return '
        <a class="btn btn-outline-success btn-icon" title="Imprimir copia" onclick="imprimir_duplicado_factura(' . $id . ')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                <path d="M17 9V5a2 2 0 0 0 -2 -2H9a2 2 0 0 0 -2 2v4" />
                <rect x="7" y="13" width="10" height="8" rx="2"/>
            </svg>
        </a>';
    }

    private function btnDetalleFE($id)
    {
        return '
        <a class="btn btn-outline-muted btn-icon" title="Ver detalle" onclick="detalle_f_e(' . $id . ')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="2" />
                <path d="M22 12c-2.667 4.667 -6 7 -10 7S4.667 16.667 2 12C4.667 7.333 8 5 12 5s7.333 2.333 10 7" />
            </svg>
        </a>';
    }

    private function btnDetalleFactura($id)
    {
        return '
        <a class="btn btn-outline-muted btn-icon" title="Ver detalle" onclick="detalle_de_factura(' . $id . ')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="2" />
                <path d="M22 12c-2.667 4.667 -6 7 -10 7S4.667 16.667 2 12C4.667 7.333 8 5 12 5s7.333 2.333 10 7" />
            </svg>
        </a>';
    }

    private function btnEditar($id)
    {
        return '
        <a class="btn btn-outline-yellow btn-icon" title="Editar factura" onclick="editar_factura(' . $id . ')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 6l3 3l-9 9h-3v-3z"/>
                <path d="M18 3l3 3"/>
            </svg>
        </a>';
    }

    private function btnEliminarFE($id)
    {
        return '
        <a class="btn btn-outline-danger btn-icon" title="Eliminar" onclick="eliminar_f_e(' . $id . ')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="0" fill="currentColor">
                <path d="M20 6H4l1 14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2l1-14z" />
                <path d="M14 2a2 2 0 0 1 2 2H8a2 2 0 0 1 2-2h4z" />
            </svg>
        </a>';
    }

    private function btnPdf($url)
    {
        return '
        <a href="' . $url . '" target="_blank">
            <img title="Descargar PDF" src="' . base_url() . '/Assets/img/pdf.png" width="40" height="40" />
        </a>';
    }

    private function btnSaldo($id_factura, $id_estado)
    {
        return '
        <a class="btn btn-outline-danger btn-icon" title="Ver saldo pendiente" onclick="ver_saldo(' . $id_factura . ', ' . $id_estado . ')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-dollar">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
                <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                <path d="M12 17v1m0 -8v1" />
            </svg>
        </a>';
    }

    private function btnAbono($id)
    {
        return '
        <a class="btn btn-outline-primary btn-icon" title="Realizar pago" onclick="abono_credito(' . $id . ')">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cash-banknote" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <rect x="3" y="6" width="18" height="12" rx="2" />
                <circle cx="12" cy="12" r="2" />
                <path d="M6 12h.01M18 12h.01" />
            </svg>
        </a>';
    }

  
}