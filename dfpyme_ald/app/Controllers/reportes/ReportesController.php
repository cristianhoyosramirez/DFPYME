<?php

namespace App\Controllers\reportes;

use App\Controllers\BaseController;
use App\Libraries\data_table;
use App\Libraries\tipo_consulta;
use \DateTime;
use App\Libraries\estado_factura;

class ReportesController extends BaseController
{
    public $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function data_table_ventas()
    {
        //$valor_buscado = $_GET['search']['value'];
        $id_apertura = model('aperturaModel')->selectMax('id')->findAll();
        $apertura = $id_apertura[0]['id'];

        $sql_count = '';
        $sql_data = '';

        $table_map = [
            0 => 'id',
            1 => 'fecha',
            2 => 'nit_cliente',
            3 => 'nombrescliente',
            4 => 'documento',
            5 => 'total_documento',

        ];

        $sql_count = "SELECT 
                            COUNT(pagos.id) AS total
                    FROM
                    pagos where id_apertura=$apertura";

        $sql_data = "SELECT
                    id,
                    fecha,
                    documento,
                    total_documento,
                    id_factura,
                    id_estado,
                    nit_cliente,
                    id_estado,
                    id_factura
                FROM
                    pagos where id_apertura=$apertura";

        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR descripcionestado ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();
        $data = [];

        foreach ($datos as $detalle) {
            $sub_array = array();

            $costo = model('kardexModel')->selectSum('costo')->where('id_factura', $detalle['id_factura'])->findAll();
            $iva = model('kardexModel')->selectSum('iva')->where('id_factura', $detalle['id_factura'])->findAll();
            $inc = model('kardexModel')->selectSum('ico')->where('id_factura', $detalle['id_factura'])->findAll();

            if ($detalle['id_factura'] == 8) {
                $temp_documento = model('facturaElectronicaModel')->select('numero')->where('id', $detalle['id_factura'])->first();
                $documento = $temp_documento['numero'];
            }

            if ($detalle['id_factura'] != 8) {
                $documento = $detalle['documento'];
            }

            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] =  $nombre_cliente['nombrescliente'];
            // $sub_array[] = $detalle['documento'];
            $sub_array[] = $documento;
            $tipo_documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sub_array[] = $tipo_documento['descripcionestado'];
            $sub_array[] = number_format($detalle['total_documento'] - ($iva[0]['iva'] + $inc[0]['ico']), 0, ",", ".");
            $sub_array[] = "$ " . number_format($iva[0]['iva'], 0, ",", ".");
            // $sub_array[] = number_format($detalle['total_documento'] - ($iva[0]['iva'] + $inc[0]['ico']), 0, ",", ".");
            //$sub_array[] = number_format($iva[0]['iva'], 0, ",", ".");
            $sub_array[] = number_format($inc[0]['ico'], 0, ",", ".");
            $sub_array[] = number_format($detalle['total_documento'], 0, ",", ".");


            $data[] = $sub_array;
        }
        $total_venta = model('pagosModel')->selectSum('valor')->where('id_apertura', $apertura)->findAll();


        $iva = model('kardexModel')->get_iva_reportes($apertura);
        $inc = model('kardexModel')->get_inc_reportes($apertura);

        $costo = model('pagosModel')->total_costo($apertura);
        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
            'total_venta' => "$ " . number_format($total_venta[0]['valor'], 0, ",", "."),
            'impuestos' => view('impuestos/impuestos', [
                'iva' => $iva,
                'inc' => $inc,
                'apertura' => $apertura,
                'venta_total' => $total_venta[0]['valor']
            ])

        ];

        echo  json_encode($json_data);
    }

    public function reporte_de_ventas_fecha()
    {
        $fecha_inicial = $this->request->getGet('fecha_inicial');
        $fecha_final = $this->request->getGet('fecha_final');

        if (empty($fecha_inicial) and empty($fecha_final)) { //DEsde el inicio 
            $temp_fecha_inicial = model('pagosModel')->selectMin('fecha')->first();
            $temp_fecha_final = model('pagosModel')->selectMax('fecha')->first();
            $fecha_inicial = $temp_fecha_inicial['fecha'];
            $fecha_final = $temp_fecha_final['fecha'];
        }

        if (!empty($fecha_inicial) and empty($fecha_final)) {
            $fecha_final = $fecha_inicial;
        }
        if (!empty($fecha_inicial) and !empty($fecha_final)) {
            $fecha_inicial = $fecha_inicial;
            $fecha_final = $fecha_final;
        }



        $sql_count = '';
        $sql_data = '';

        $table_map = [
            0 => 'id',
            1 => 'fecha',
            2 => 'nit_cliente',
            3 => 'nombrescliente',
            4 => 'documento',
            5 => 'total_documento',

        ];

        $sql_count = "SELECT 
                            COUNT(pagos.id) AS total
                    FROM
                    pagos where  fecha between '$fecha_inicial' and ' $fecha_final'";

        $sql_data = "SELECT
                    id,
                    fecha,
                    documento,
                    total_documento,
                    id_factura,
                    id_estado,
                    nit_cliente,
                    id_estado,
                    id_factura
                FROM
                    pagos  where fecha between '$fecha_inicial' and ' $fecha_final'";

        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR descripcionestado ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR factura_venta.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR numerofactura_venta ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();
        $data = [];

        foreach ($datos as $detalle) {
            $sub_array = array();

            $costo = model('kardexModel')->selectSum('costo')->where('id_factura', $detalle['id_factura'])->findAll();
            $iva = model('kardexModel')->selectSum('iva')->where('id_factura', $detalle['id_factura'])->findAll();
            $inc = model('kardexModel')->selectSum('ico')->where('id_factura', $detalle['id_factura'])->findAll();

            if ($detalle['id_factura'] == 8) {
                $temp_documento = model('facturaElectronicaModel')->select('numero')->where('id', $detalle['id_factura'])->first();
                $documento = $temp_documento['numero'];
            }

            if ($detalle['id_factura'] != 8) {
                $documento = $detalle['documento'];
            }

            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $detalle['nit_cliente'])->first();
            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] =  $nombre_cliente['nombrescliente'];
            // $sub_array[] = $detalle['documento'];
            $sub_array[] = $documento;
            $tipo_documento = model('estadoModel')->select('descripcionestado')->where('idestado', $detalle['id_estado'])->first();

            $sub_array[] = $tipo_documento['descripcionestado'];
            $sub_array[] = number_format($detalle['total_documento'] - ($iva[0]['iva'] + $inc[0]['ico']), 0, ",", ".");
            $sub_array[] = number_format($iva[0]['iva'], 0, ",", ".");
            // $sub_array[] = number_format($detalle['total_documento'] - ($iva[0]['iva'] + $inc[0]['ico']), 0, ",", ".");
            //$sub_array[] = number_format($iva[0]['iva'], 0, ",", ".");
            $sub_array[] = number_format($inc[0]['ico'], 0, ",", ".");
            $sub_array[] = number_format($detalle['total_documento'], 0, ",", ".");


            $data[] = $sub_array;
        }
        $total_venta = model('pagosModel')->total_venta_costo($fecha_inicial, $fecha_final);


        $iva = model('kardexModel')->get_iva_reportes_fecha($fecha_inicial, $fecha_final);
        $inc = model('kardexModel')->get_ico_reportes_fecha($fecha_inicial, $fecha_final);
        $venta_total = model('pagosModel')->total_venta_costo($fecha_inicial, $fecha_final);

        //costo = model('pagosModel')->total_costo($apertura);
        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
            'total_venta' => "$ " . number_format($total_venta[0]['total'], 0, ",", "."),
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
            'impuestos' => view('impuestos/impuestos_fecha', [
                'iva' => $iva,
                'inc' => $inc,
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
                'venta_total' => $venta_total[0]['total']
            ])
        ];

        echo  json_encode($json_data);
    }


    function sendDian()
    {
        $id_factura = $this->request->getPost('id_factura');
        //$id_factura = 951;

        $nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $id_factura)->first();

        $email = model('clientesModel')->select('emailcliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $transaccion_id = model('facturaElectronicaModel')->select('transaccion_id')->where('id', $id_factura)->first();

        $auto_token = model('credencialesWebServerModel')->select('auth_token')->first();

        $request_body = array(
            "send_dian" => true,
            "send_email" => true,
            "email" => $email['emailcliente']
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dataico.com/direct/dataico_api/v2/invoices/' . $transaccion_id['transaccion_id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => json_encode($request_body),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'auth-token: ' . $auto_token['auth_token']
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }


    function retrasmistir()
    {

        $id_factura = $this->request->getPost('id_fact');
        //$id_factura = 745;

        //Token 
        $temp_token = model('credencialesWebServerModel')->select('auth_token')->first();
        $auth_token = $temp_token['auth_token'];

        // token

        // UUID
        $temp_uui = model('facturaElectronicaModel')->select('transaccion_id')->where('id', $id_factura)->first();
        $uuid = $temp_uui['transaccion_id'];


        //UUID

        //Email 
        $temp_nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $id_factura)->first();
        $nit_cliente = $temp_nit_cliente['nit_cliente'];

        $temp_email = model('clientesModel')->select('emailcliente')->where('nitcliente', $nit_cliente)->first();
        $email = $temp_email['emailcliente'];



        $curl = curl_init();

        curl_setopt_array($curl, array(
            //URLOPT_URL => 'https://api.dataico.com/direct/dataico_api/v2/invoices/01917fc9-9f67-89c9-b7e5-75a873d4d679',
            CURLOPT_URL => "https://api.dataico.com/direct/dataico_api/v2/invoices/" . $uuid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => '{

   "actions": {

       "send_dian": true,

       "send_email": true,

       "email": "$email"

}

}',
            CURLOPT_HTTPHEADER => array(
                'Content-type: application/json',
                //'Auth-token: a2a9e4f80af0247afca0361a1dc7598b',
                "Auth-token: $auth_token",
                'Cookie: AWSALBAPP-0=_remove_; AWSALBAPP-1=_remove_; AWSALBAPP-2=_remove_; AWSALBAPP-3=_remove_; AWSALBTG=uMZLBK0WU1PwqbynnHdko4gx+9nQg8DtBGFWnLxkXe8Pp1rojcX4mD0eZkSXIhO4c8Xu/HkZIVSMldORtc+68wrPva0RmlTFv04i8Esll/36I4e2Hem/XxBVX9gP9wCk6c0saPYN7WisNLXasHRjbMAS4CvGWJpYuFlTELKDwUb/0Pjn280=; AWSALBTGCORS=uMZLBK0WU1PwqbynnHdko4gx+9nQg8DtBGFWnLxkXe8Pp1rojcX4mD0eZkSXIhO4c8Xu/HkZIVSMldORtc+68wrPva0RmlTFv04i8Esll/36I4e2Hem/XxBVX9gP9wCk6c0saPYN7WisNLXasHRjbMAS4CvGWJpYuFlTELKDwUb/0Pjn280='
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        //echo $response;

        if ($err) {
            //echo "cURL Error #:" . $err;

            $returnData = array(
                "resultado" => 2,  // Se actulizo el registro 

            );
            echo  json_encode($returnData);
        } else {
            // Decodifica el JSON a un array asociativo
            $responseData = json_decode($response, true);

            //echo $responseData['invoice']['payment_means_type'];


            $id_status = "";

            $responseData = json_decode($response, true);

            //echo $responseData['qrcode'];

            //$pattern = '/QRCode=([^\s]+)/';
            $pattern = '/QRCode=([^?]+)\?documentkey=([^\s]+)/';
            $matches = array();

            if (preg_match($pattern, $responseData['qrcode'], $matches)) {

                $baseUrl = $matches[1];
                $documentKey = $matches[2];
                //echo "QR Code URL: " . $qrCodeUrl;
                $completeUrl = $baseUrl  . $documentKey;
                //echo "QR Code URL completa: "."?documentkey=". $completeUrl;
                // echo  $baseUrl . "?documentkey=" . $documentKey;  ESTA ES LA VALIDA 
            } else {
                echo "QRCode no encontrado.";
            }


            //exit();

            //dd($responseData);

            if ($responseData['dian_status'] == 'DIAN_ACEPTADO') {
                $id_status = 2;

                $data = [
                    'id_status' => $id_status,
                    'transaccion_id' => $responseData['uuid'],
                    'qrcode' => $baseUrl . "?documentkey=" . $documentKey,
                    //'qrcode' => $responseData['qrcode'],
                    'cufe' => $responseData['cufe'],
                    'pdf_url' => $responseData['pdf_url']
                ];

                $model = model('facturaElectronicaModel');
                $factura = $model->set($data);
                $factura = $model->where('id', $id_factura);
                $factura = $model->update();
            }



            $returnData = array(
                "resultado" => 1,  // Se actulizo el registro 

            );
            echo  json_encode($returnData);
        }
    }




    function estado_dian()
    {
        $valor_buscado = $_GET['search']['value'];
        $estado_dian = $this->request->getGet('estado_dian');
        //$estado_dian = 1;

        $sql_count = '';
        $sql_data = '';

        $table_map = [
            0 => 'id',
            1 => 'fecha',
            2 => 'nit_cliente',
            3 => 'nombrescliente',
            4 => 'documento',
            5 => 'total_documento',

        ];

        $sql_count = "SELECT 
                        COUNT(documento_electronico.id) AS total
                        FROM
                        documento_electronico 
                        INNER JOIN cliente ON documento_electronico.nit_cliente = cliente.nitcliente
                        where id_status= $estado_dian";

        $sql_data = "SELECT documento_electronico.id,
        fecha,
        nit_cliente,
        cliente.nombrescliente,
        numero AS documento,
        neto AS total_documento
        FROM documento_electronico
        INNER JOIN cliente ON documento_electronico.nit_cliente = cliente.nitcliente
        WHERE id_status= $estado_dian";



        $condition = "";

        if (!empty($valor_buscado)) {
            $condition .= " AND cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";

            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";

            $condition .= " OR numero ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count .= $condition;
        $sql_data .= $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();

        $data = [];

        $accion = new data_table();



        foreach ($datos as $detalle) {
            $sub_array = array();



            $sub_array[] = $detalle['fecha'];
            $sub_array[] = $detalle['nit_cliente'];
            $sub_array[] = $detalle['nombrescliente'];
            $sub_array[] = $detalle['documento'];
            $sub_array[] = number_format($detalle['total_documento'], 0, ",", ".");

            $saldo = model('pagosModel')->getSaldo($detalle['id']);
            $sub_array[] = $saldo[0]['saldo'];

            $sub_array[] = "FACTURA ELECTRONICA";
            $acciones = $accion->row_data_table(8, $detalle['id']);

            $sub_array[] = $acciones;


            $data[] = $sub_array;
        }


        $total_venta = model('facturaElectronicaModel')->selectSum('neto')->where('id_status', $estado_dian)->findAll();


        $dian_aceptado = model('facturaElectronicaModel')->selectCount('id')->where('id_status', 2)->findAll();
        $dian_no_enviado = model('facturaElectronicaModel')->selectCount('id')->where('id_status', 1)->findAll();
        $dian_rechazado = model('facturaElectronicaModel')->selectCount('id')->where('id_status', 3)->findAll();
        $dian_error = model('facturaElectronicaModel')->selectCount('id')->where('id_status', 4)->findAll();

        $json_data = [
            'draw' => intval($this->request->getGEt(index: 'draw')),
            'recordsTotal' => $total_count->total,
            'recordsFiltered' => $total_count->total,
            'data' => $data,
            'total' => "$ " . number_format($total_venta[0]['neto'], 0, ",", "."),
            'dian_aceptado' => $dian_aceptado[0]['id'],
            'dian_no_enviado' => $dian_no_enviado[0]['id'],
            'dian_rechazado' => $dian_rechazado[0]['id'],
            'dian_error' => $dian_error[0]['id'],

        ];

        echo  json_encode($json_data);
    }

    function actualizar_pagos()
    {

        $efectivo = $this->request->getPost('efectivo_factura');
        $transferencia = $this->request->getPost('transferencia_factura');

        $efectivo = str_replace('.', '', $efectivo);
        $transferencia = str_replace('.', '', $transferencia);

        $id = $this->request->getPost('id');

        $pagos = [
            'efectivo' => $efectivo,
            'transferencia' => $transferencia,
            'total_pago' => $efectivo + $transferencia,
            'recibido_efectivo' => $efectivo,
            'recibido_transferencia' => $transferencia,
        ];
        $pagos = model('pagosModel')->set($pagos)->where('id', $id)->update();

        if ($pagos) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');
            return redirect()->to(base_url('consultas_y_reportes/consultas_caja'))->with('mensaje', 'Actualización correcta ');
        }
    }

    function datos_pagos()
    {
        $id = $this->request->getPost('id');
        $pagos = model('pagosModel')->pagos($id);

        $returnData = array(
            "resultado" => 1,
            "efectivo" => number_format($pagos[0]['efectivo'], 0, ",", "."),
            "transferencia" => number_format($pagos[0]['transferencia'], 0, ",", "."),
            "id" => $id,

        );
        echo  json_encode($returnData);
    }

    function ver_productos_eliminanados()
    {
        $productos_eliminados = model('productoModel')->get_productos_borrados();

        $returnData = array(
            "resultado" => 1, //Falta plata  
            "productos" => view('producto/eliminados', [
                'productos' => $productos_eliminados
            ]), //Falta plata  
        );
        echo  json_encode($returnData);
    }

    function activar_producto()
    {
        $codigo_interno = $this->request->getPost('codigo');

        $actualizar = model('productoModel')->set('estadoproducto', 'true')->where('codigointernoproducto', $codigo_interno)->update();

        if ($actualizar) {
            $returnData = array(
                "resultado" => 1, //Falta plata  
            );
            echo  json_encode($returnData);
        }
    }

    function total_ventas_electronicas()
    {

        $id_apertura = model('aperturaRegistroModel')->select('numero')->first();


        $total_ventas_electronicas = model('pagosModel')->get_total_ventas_electronicas($id_apertura['numero']);

        $returnData = array(
            "resultado" => 1, //Falta plata 
            'ventas_electronicas' => "$ " . number_format($total_ventas_electronicas[0]['total_electronicas'], 0, ",", ".")
        );
        echo  json_encode($returnData);
    }

    function comprobar_fechas()
    {
        $id_doc = $this->request->getPost('iddoc');
        //$id_doc = 5727;
        $temp_fecha = model('facturaElectronicaModel')->select('fecha')->where('id', $id_doc)->first();
        $documento = model('facturaElectronicaModel')->select('numero')->where('id', $id_doc)->first();


        $fecha = $temp_fecha['fecha'];
        // Obtener la fecha actual
        $fecha_actual = date('Y-m-d');
        // Comparar la fecha recuperada con la fecha actual
        if ($fecha === $fecha_actual) {
            // La fecha es igual a la fecha actual  y se trasmite sin problema 
            $transaccion_id = new estado_factura();
            $uudi = $transaccion_id->getUuid($id_doc);
            if (empty($uudi)) {
                $returnData = array(  //La factura no tiene uuid y le fecha es la del dia y no esta en el proveedor tecnológico lo cual indica que se puede retrasmitr desde el pc local 
                    "resultado" => 0,
                    "id_doc" => $id_doc,
                    "numero" => $documento['numero']

                );
                echo  json_encode($returnData);
            }

            if (!empty($uudi)) {

                $returnData = array(  //La factura tiene uuid y le fecha es la del dia y esta en el proveedor tecnológico lo cual indica que se puede retrasmitr desde dataico 
                    "resultado" => 1,
                    "id_doc" => $id_doc,
                    "numero" => $documento['numero']

                );
                echo  json_encode($returnData);
            }
        }
        if ($fecha < $fecha_actual) {

            $transaccion_id = new estado_factura();
            $uudi = $transaccion_id->getUuid($id_doc);

            $numero = $documento['numero'];


            if (!empty($uudi)) {  //Tiene id transaccion y se debe actualizar la fecha a la actual 
                $returnData = array(
                    "resultado" => 2,
                    'numero' => $numero,
                    'id_doc' => $id_doc
                );
                echo  json_encode($returnData);
            }
            if (empty($uudi)) {  //No tiene id transaccion y se debe actualizar la fecha a la actual para generar la trasmision a la DIAN 
                $returnData = array(
                    "resultado" => 3,
                    'numero' => $numero,
                    'id_doc' => $id_doc
                );
                echo  json_encode($returnData);
            }
        }
    }

    function actualizar_fechas()
    {
        $id_doc = $this->request->getPost('id_doc');
        $data = [
            'fecha' => date('Y-m-d'),
            'fecha_limite' => date('Y-m-d'),
            'fecha_pago' => date('Y-m-d'),

        ];

        $update = model('facturaElectronicaModel')
            ->set($data)
            ->where('id', $id_doc)
            ->update();

        if ($update) {
            $pagos = [
                'fecha' => date('Y-m-d')

            ];

            $actualizar = model('pagosModel')
                ->set($pagos)
                ->where('id_factura', $id_doc)
                ->update();

            $temp_uui = model('facturaElectronicaModel')->select('transaccion_id')->where('id', $id_doc)->first();
            $uuid = $temp_uui['transaccion_id'];

            if (!empty($uuid)) {
                $returnData = array(
                    "resultado" => 1,
                    "id_doc" => $id_doc

                );
                echo  json_encode($returnData);
            }
            if (empty($uuid)) {
                $returnData = array(
                    "resultado" => 2,
                    "id_doc" => $id_doc

                );
                echo  json_encode($returnData);
            }
        }
    }
}
