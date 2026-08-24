<?php

namespace App\Controllers\Cartera;

use App\Controllers\BaseController;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class carteraContoller extends BaseController
{
    public function index()
    {
        $carteraVigente = model('pagosModel')->getCartera();
        $totalCartera = model('pagosModel')->getTotalCartera();
        return view('cartera/cartera', [
            'cartera' => $carteraVigente,
            'total' => $totalCartera[0]['total_documentos'],
            'cantidad_facturas' => $totalCartera[0]['cantidad_facturas']
        ]);
    }

    function datos_cartera()
    {

        $carteraVigente = model('pagosModel')->getCartera();
        $totalCartera = model('pagosModel')->getTotalCartera();

        return $this->response->setJSON([
            'success' => true,
            'datos' => view('cartera/datosCartera', [
                'cartera' => $carteraVigente
            ]),
            'cartera' => $carteraVigente,
            'total' => number_format($totalCartera[0]['total_documentos'], 0, ',', '.'),
            'cantidad_facturas' => number_format($totalCartera[0]['cantidad_facturas'], 0, ',', '.')
        ]);
    }

    public function buscarDocumento()
    {

        //$documento = $this->request->getPost('documento');

        //$carteraVigente = model('pagosModel')->getCartera();
        //$totalCartera = model('pagosModel')->getTotalCartera();

        $json = $this->request->getJSON(true); // true = arreglo asociativo

        $documento = $json['documento'];
        //$documento = '89049';

        $cartera = model('CarteraModel')->getDatosCartera($documento);

        //dd($cartera);
        $total_cartera = model('CarteraModel')->getSumaCartera($documento);
        $cantidad_cartera = model('CarteraModel')->getCantidadCartera($documento);
        $total_pagado = model('CarteraModel')->totalPagado($documento);



        if (empty($cartera)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se encontraron registros.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => view('cartera/datosCartera', [
                'cartera' => $cartera
            ]),
            'total' => number_format($total_cartera[0]['total'], 0, ',', '.'),
            'cantidad' => $cantidad_cartera['cantidad'],
            'valor_pagado' => number_format($total_pagado['total'], 0, ',', '.')
        ]);
    }

    public function buscarCliente()
    {

        //$documento = $this->request->getPost('documento');

        //$carteraVigente = model('pagosModel')->getCartera();
        //$totalCartera = model('pagosModel')->getTotalCartera();

        $json = $this->request->getJSON(true); // true = arreglo asociativo

        $id_cliente = $json['id_cliente'];
        //$id_cliente = 9;
        //$cliente = 'juan';


        $cartera = model('CarteraModel')->getDatosCarteraCliente($id_cliente);


        $total_cartera = model('CarteraModel')->getSumaCarteraCliente($id_cliente);



        $cantidad_cartera = model('CarteraModel')->getCantidadCarteraCliente($id_cliente);
        $total_pagado = model('CarteraModel')->totalPagadoCliente($id_cliente);



        if (empty($cartera)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se encontraron registros.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => view('cartera/datosCartera', [
                'cartera' => $cartera
            ]),
            'total' => number_format($total_cartera[0]['total'], 0, ',', '.'),
            'cantidad' => $cantidad_cartera['cantidad'],
            'valor_pagado' => number_format($total_pagado['total'], 0, ',', '.')
        ]);
    }

    /*  public function buscarCliente()
    {

        //$documento = $this->request->getPost('documento');

        //$carteraVigente = model('pagosModel')->getCartera();
        //$totalCartera = model('pagosModel')->getTotalCartera();

        //$json = $this->request->getJSON(true); // true = arreglo asociativo

       

       $buscar = trim($this->request->getPost('buscar')); 

        $cliente = $buscar;
        //$cliente = 'juan';


        $cartera = model('CarteraModel')->getDatosCarteraCliente($cliente);
        $total_cartera = model('CarteraModel')->getSumaCarteraCliente($cliente);

        $cantidad_cartera = model('CarteraModel')->getCantidadCarteraCliente($cliente);
        $total_pagado = model('CarteraModel')->totalPagadoCliente($cliente);



        if (empty($cartera)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se encontraron registros.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => view('cartera/datosCartera', [
                'cartera' => $cartera
            ]),
            'total' => number_format($total_cartera[0]['total'], 0, ',', '.'),
            'cantidad' => $cantidad_cartera['cantidad'],
            'valor_pagado' => number_format($total_pagado['total'], 0, ',', '.')
        ]);
    } */
    public function getCartera()
    {



        $json = $this->request->getJSON(true); // true = arreglo asociativo

        $estado = $json['estado'];
        $fecha_inicial = $json['fecha_inicial'];
        $fecha_final = $json['fecha_final'];
        $id_cliente = $json['id_cliente'];


        /*  $estado = 0;
        $fecha_inicial = "";
        $fecha_final = "";
        $id_cliente = ""; */


        $nit_cliente = null;

        if (!empty($id_cliente)) {
            $cliente = model('clientesModel')
                ->select('nitcliente')
                ->find($id_cliente);

            $nit_cliente = $cliente['nitcliente'] ?? null;
        }



        $cartera = model('CarteraModel')->getCartera($estado, $fecha_inicial, $fecha_final, $nit_cliente);



        //$cartera = model('CarteraModel')->getCarteraFechas($estado, $fecha_inicial, $fecha_final);
        $total_cartera = model('CarteraModel')->getSumaCarteraFechas($estado, $fecha_inicial, $fecha_final, $nit_cliente);


        $cantidad_cartera = model('CarteraModel')->getCantidadCarteraFechas($estado, $fecha_inicial, $fecha_final, $nit_cliente);
        $total_pagado = model('CarteraModel')->totalPagadoFechas($estado, $fecha_inicial, $fecha_final, $nit_cliente);



        if (empty($cartera)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se encontraron registros.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => view('cartera/datosCartera', [
                'cartera' => $cartera
            ]),
            'total' => number_format($total_cartera[0]['total'], 0, ',', '.'),
            'cantidad' => $cantidad_cartera['cantidad'],
            'valor_pagado' => number_format($total_pagado['total'], 0, ',', '.')
        ]);
    }

    function excel()
    {
        $datos_empresa = model('empresaModel')->datosEmpresa();
        $tipoFecha = $this->request->getPost('tipo_fecha');
        $estado = $this->request->getPost('EstadoCartera');
        $id_cliente = $this->request->getPost('id_cliente');

        $nit_cliente = null;

        if (!empty($id_cliente)) {
            $cliente = model('clientesModel')
                ->select('nitcliente')
                ->find($id_cliente);

            $nit_cliente = $cliente['nitcliente'] ?? null;
        }

        $fechaInicial = '';
        $fechaFinal   = '';

        switch ($tipoFecha) {

            case 'f': // Una fecha
                $fechaInicial = $this->request->getPost('fecha');
                $fechaFinal   = $fechaInicial;
                break;

            case 'pp': // Por período
                $fechaInicial = $this->request->getPost('fecha_inicial');
                $fechaFinal   = $this->request->getPost('fecha_final');
                break;

            case 't': // Todos los tiempos
            default:
                $fechaInicial = '';
                $fechaFinal   = '';
                break;
        }


        $cartera = model('CarteraModel')->getCartera($estado, $fechaInicial, $fechaFinal, $nit_cliente);

        $total_cartera = model('CarteraModel')->getSumaCarteraFechas($estado, $fechaInicial, $fechaFinal, $nit_cliente);

        $cantidad_cartera = model('CarteraModel')->getCantidadCarteraFechas($estado, $fechaInicial, $fechaFinal, $nit_cliente);
        $total_pagado = model('CarteraModel')->totalPagadoFechas($estado, $fechaInicial, $fechaFinal, $nit_cliente);


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getDefaultStyle()->getFont()->setName('Aptos Narrow')->setSize(11);
        $file_name = 'Reporte de cartera ' . $fechaInicial . ' al ' . $fechaFinal . '.xlsx';

        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => '000000'], // Fuente en color negro
                'name' => 'Aptos Narrow',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'F2F2F2'], // Fondo gris más claro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], // Bordes negros
                ],
            ],
        ];



        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A2', $datos_empresa[0]['nombrecomercialempresa']);
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:G2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A3', 'NIT: ' . $datos_empresa[0]['nitempresa']);
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:G3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue(
            'A4',
            'Dirección: ' . $datos_empresa[0]['direccionempresa'] . " " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento']
        );
        $sheet->mergeCells('A4:G4');
        $sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:G4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A5', 'REPORTE  DE VENTA');
        $sheet->mergeCells('A5:G5');

        // Aplicar alineación centrada
        $sheet->getStyle('A5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('A6', "Fecha inicial");
        $sheet->setCellValue('B6', $fechaInicial);

        $sheet->setCellValue('D6', "Fecha final");
        $sheet->setCellValue('E6', $fechaFinal);

        $sheet->setCellValue('A8', 'Fecha');
        $sheet->setCellValue('B8', 'NIT');
        $sheet->setCellValue('C8', 'Cliente');
        $sheet->setCellValue('D8', 'Documento');
        $sheet->setCellValue('E8', 'Valor factura');
        $sheet->setCellValue('F8', 'Valor pagado');
        $sheet->setCellValue('G8', 'Saldo');
        $sheet->setCellValue('H8', 'Tipo documento');

        // Estilo del encabezado
        $sheet->getStyle('A8:H8')->getFont()->setBold(true);


        $fila = 9; // Fila donde empiezan los datos
        $totalSaldo = 0;

        foreach ($cartera as $detalle) {

            $sheet->setCellValue('A' . $fila, $detalle['fecha']);
            $sheet->setCellValue('B' . $fila, $detalle['nit_cliente']);
            $sheet->setCellValue('C' . $fila, $detalle['nombrescliente']);
            $sheet->setCellValue('D' . $fila, $detalle['documento']);
            $sheet->setCellValue('E' . $fila, $detalle['total_documento']);
            $sheet->setCellValue('F' . $fila, $detalle['abonado']);
            $sheet->setCellValue('G' . $fila, $detalle['saldo']);
            $sheet->setCellValue('H' . $fila, $detalle['descripcionestado']);

            $totalSaldo += $detalle['saldo'];

            $fila++;
        }

   $sheet->setCellValue('F' . $fila, 'TOTAL');
$sheet->setCellValue('G' . $fila, $totalSaldo);

$sheet->getStyle('F' . $fila . ':G' . $fila)
      ->getFont()
      ->setBold(true);

$sheet->getStyle('E9:G' . $fila)
      ->getNumberFormat()
      ->setFormatCode('#,##0');





        $writer = new Xlsx($spreadsheet);
        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:' . filesize($file_name));
        flush();
        readfile($file_name);
        exit;
    }
}
