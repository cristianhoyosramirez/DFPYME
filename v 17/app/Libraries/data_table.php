<?php

namespace App\Libraries;

class data_table
{
    public function row_data_table($id_estado, $id_factura)
    {
        $sub_array = [];

        if ($id_estado == 8) {
            $status = model('facturaElectronicaModel')->select('id_status')->where('id', $id_factura)->first();

            if ($status['id_status'] == 1) {
                // Documento NO enviado
                $sub_array[] = '
                    <a class="btn btn-outline-dark btn-icon" title="Documento no enviado" onclick="sendInvoice(' . $id_factura . ')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                            <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M17 21l4 -4" />
                            <path d="M3 7l9 6l9 -6" />
                        </svg>
                    </a>
                    <a class="btn btn-outline-success btn-icon" title="Imprimir copia" onclick="imprimir_electronica(' . $id_factura . ')">
                        <!-- Ícono de impresora -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                            <path d="M17 9V5a2 2 0 0 0 -2 -2H9a2 2 0 0 0 -2 2v4" />
                            <rect x="7" y="13" width="10" height="8" rx="2"/>
                        </svg>
                    </a>
                    <a class="btn btn-outline-muted btn-icon" title="Ver detalle" onclick="detalle_f_e(' . $id_factura . ')">
                        <!-- Ícono de ojo -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="2" />
                            <path d="M22 12c-2.667 4.667 -6 7 -10 7S4.667 16.667 2 12C4.667 7.333 8 5 12 5s7.333 2.333 10 7" />
                        </svg>
                    </a>
                    <a class="btn btn-outline-yellow btn-icon" title="Editar factura" onclick="editar_factura(' . $id_factura . ')">
                        <!-- Ícono de editar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 6l3 3l-9 9h-3v-3z"/>
                            <path d="M18 3l3 3"/>
                        </svg>
                    </a>
                    <a class="btn btn-outline-danger btn-icon" title="Eliminar" onclick="eliminar_f_e(' . $id_factura . ')">
                        <!-- Ícono de papelera -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="0" fill="currentColor">
                            <path d="M20 6H4l1 14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2l1-14z" />
                            <path d="M14 2a2 2 0 0 1 2 2H8a2 2 0 0 1 2-2h4z" />
                        </svg>
                    </a>
                ';
            }

            if ($status['id_status'] == 2) {
                // Documento ACEPTADO por DIAN
                $pdf_url = model('facturaElectronicaModel')->select('pdf_url')->where('id', $id_factura)->first();

                $sub_array[] = '
                    <a class="btn btn-outline-success btn-icon" title="Imprimir" onclick="imprimir_electronica(' . $id_factura . ')"> <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                            <path d="M17 9V5a2 2 0 0 0 -2 -2H9a2 2 0 0 0 -2 2v4" />
                            <rect x="7" y="13" width="10" height="8" rx="2"/>
                        </svg> </a>
                    <a class="btn btn-outline-muted btn-icon" title="Ver detalle" onclick="detalle_f_e(' . $id_factura . ')">  <!-- Ícono de ojo -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="2" />
                            <path d="M22 12c-2.667 4.667 -6 7 -10 7S4.667 16.667 2 12C4.667 7.333 8 5 12 5s7.333 2.333 10 7" />
                        </svg></a>
                    <a href="' . $pdf_url['pdf_url'] . '" target="_blank">
                        <img title="Descargar PDF" src="' . base_url() . '/Assets/img/pdf.png" width="40" height="40" />
                    </a>
                ';
            }

            if ($status['id_status'] == 3) {
                // Documento RECHAZADO por DIAN
                $sub_array[] = '
                    <a class="btn btn-outline-danger btn-icon" title="Reenviar documento rechazado" onclick="sendInvoice(' . $id_factura . ')"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 19h-7a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5" />
                            <path d="M19 19m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M17 21l4 -4" />
                            <path d="M3 7l9 6l9 -6" />
                        </svg> </a>
                    <a class="btn btn-outline-success btn-icon" title="Imprimir" onclick="imprimir_electronica(' . $id_factura . ')"> ... </a>
                    <a class="btn btn-outline-muted btn-icon" title="Ver detalle" onclick="detalle_f_e(' . $id_factura . ')"> ... </a>
                ';
            }

            if ($status['id_status'] == 4) {
                // Documento con ERROR en DIAN
                $sub_array[] = '
                    <a class="btn btn-outline-orange btn-icon" title="Reintentar envío" onclick="sendInvoiceDian(' . $id_factura . ')"> <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                         <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
	<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg></a>
                    <a class="btn btn-outline-success btn-icon" title="Imprimir" onclick="imprimir_electronica(' . $id_factura . ')"> 
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                            <path d="M17 9V5a2 2 0 0 0 -2 -2H9a2 2 0 0 0 -2 2v4" />
                            <rect x="7" y="13" width="10" height="8" rx="2"/>
                        </svg>
                         </a>
                    <a class="btn btn-outline-muted btn-icon" title="Ver detalle" onclick="detalle_f_e(' . $id_factura . ')">  <!-- Ícono de ojo -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="2" />
                            <path d="M22 12c-2.667 4.667 -6 7 -10 7S4.667 16.667 2 12C4.667 7.333 8 5 12 5s7.333 2.333 10 7" />
                        </svg> </a>
                        

                           <a class="btn btn-outline-yellow btn-icon" title="Editar factura" onclick="editar_factura(' . $id_factura . ')">
                        <!-- Ícono de editar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 6l3 3l-9 9h-3v-3z"/>
                            <path d="M18 3l3 3"/>
                        </svg>
                    </a>

                ';
            }
        }

        // A crédito
        if ($id_estado == 2) {
            $sub_array[] = '
                <a class="btn btn-outline-success btn-icon" title="Imprimir copia" onclick="imprimir_duplicado_factura(' . $id_factura . ')"> ... </a>
                <a class="btn btn-outline-muted btn-icon" title="Ver detalle" onclick="detalle_de_factura(' . $id_factura . ')"> ... </a>
                <a class="btn btn-outline-primary btn-icon" title="Realizar pago" onclick="abono_credito(' . $id_factura . ')"> ... </a>
                <a class="btn btn-outline-yellow btn-icon" title="Editar factura" onclick="editar_factura(' . $id_factura . ')"> ... </a>
            ';
        }

        // Contado
        if ($id_estado == 1 || $id_estado == 6) {
            $sub_array[] = '
                <a class="btn btn-outline-success btn-icon" title="Imprimir" onclick="imprimir_duplicado_factura(' . $id_factura . ')"> 
                
                 <!-- Ícono de impresora -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                            <path d="M17 9V5a2 2 0 0 0 -2 -2H9a2 2 0 0 0 -2 2v4" />
                            <rect x="7" y="13" width="10" height="8" rx="2"/>
                        </svg>

                </a>
                <a class="btn btn-outline-muted btn-icon" title="Ver detalle" onclick="detalle_de_factura(' . $id_factura . ')"> 
                
                       <!-- Ícono de ojo -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="2" />
                            <path d="M22 12c-2.667 4.667 -6 7 -10 7S4.667 16.667 2 12C4.667 7.333 8 5 12 5s7.333 2.333 10 7" />
                        </svg>

                </a>
                <a class="btn btn-outline-yellow btn-icon" title="Editar factura" onclick="editar_factura(' . $id_factura . ')"> 
                
                 <!-- Ícono de editar -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 6l3 3l-9 9h-3v-3z"/>
                            <path d="M18 3l3 3"/>
                        </svg>

                </a>
            ';
        }

        // Pre-factura
        if ($id_estado == 7) {
            $sub_array[] = '
                <a class="btn btn-outline-success btn-icon" title="Imprimir" onclick="imprimir_duplicado_factura(' . $id_factura . ')"> 
                
                 <!-- Ícono de impresora -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2H5a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"/>
                            <path d="M17 9V5a2 2 0 0 0 -2 -2H9a2 2 0 0 0 -2 2v4" />
                            <rect x="7" y="13" width="10" height="8" rx="2"/>
                        </svg>

                </a>
                <a class="btn btn-outline-muted btn-icon" title="Ver detalle" onclick="detalle_de_factura(' . $id_factura . ')">        <!-- Ícono de ojo -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="2" />
                            <path d="M22 12c-2.667 4.667 -6 7 -10 7S4.667 16.667 2 12C4.667 7.333 8 5 12 5s7.333 2.333 10 7" />
                        </svg> </a>
                <a class="btn btn-outline-warning btn-icon" title="Pasar a factura" onclick="pasar_a_factura(' . $id_factura . ')"> ... </a>
                <a class="btn btn-outline-yellow btn-icon" title="Editar pre-factura" onclick="editar_factura(' . $id_factura . ')"> ... </a>
            ';
        }

        return $sub_array;
    }
}
