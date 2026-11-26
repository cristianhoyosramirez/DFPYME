<?php

namespace App\Controllers\consultas_y_reportes;

require APPPATH . "Controllers/mike42/autoload.php";

use App\Controllers\BaseController;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

use \DateTime;
use \DateTimeZone;


class AbonosController extends BaseController
{
    public function saldo_factura()
    {
        $data = $this->request->getJSON(true);

        $id_factura = $data['id_factura'] ?? null;
        $id_estado  = $data['id_estado'] ?? null;

        /*     $pago = model('pagosModel')
            ->select('saldo, valor, documento,fecha')
            ->where('id_factura', $id_factura)
            ->where('id_estado', $id_estado)
            ->first(); */


        $pago = model('pagosModel')->getSaldoFactura($id_factura, $id_estado);


        $totalPagado = model('FacturaFormaPagoModel')
            ->select('COALESCE(SUM(valor_pago), 0) AS total_pagado')
            ->where('id_factura', $id_factura)
            ->first()['total_pagado'];

        return $this->response->setJSON([
            "resultado" => 1,
            "saldo"     => number_format($pago[0]['saldo'] ?? 0, 0, ",", "."),
            "valor"     => number_format($pago[0]['total_documento'] ?? 0, 0, ",", "."),
            "documento" => $pago[0]['documento'] ?? '',
            'fecha'     => $pago[0]['fecha'] ?? '',
            'cliente'     => $pago[0]['nombrescliente'] ?? '',
            'tota_pagado' => number_format($totalPagado ?? 0, 0, ",", "."),
            'pendiente_de_pago' => $pago[0]['saldo'],
            'id_factura' => $id_factura,
            'id_estado' => $id_estado
        ]);
    }

    function actualizar_saldo()
    {

        $efectivo = $this->request->getPost('efectivo');
        $transaccion = $this->request->getPost('transaccion');
        $id_factura = $this->request->getPost('id_factura');
        $abono = $this->request->getPost('abono');
        // $saldo = $this->request->getPost('saldo');
        $saldo = model('pagosModel')->select('saldo')->where('id_factura', $id_factura)->first();

        $id_usuario = $this->request->getPost('id_usuario');


        $resultado = $efectivo + $transaccion;

        $saldo_actualizado = $saldo['saldo'] - $abono;

        $data = [
            'saldo' => $saldo_actualizado,
        ];
        $model = model('pagosModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('id_factura', $id_factura);
        $actualizar = $model->update();


        // $numero_factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura)->first();
        $numero_factura = model('pagosModel')->select('documento')->where('id_factura', $id_factura)->first();

        if ($actualizar) {

            $fecha = DateTime::createFromFormat('U.u', microtime(TRUE));
            $fecha->setTimeZone(new DateTimeZone('America/Bogota'));
            $fecha_y_hora = $fecha->format('Y-m-d H:i:s.u');

            $factura_forma_pago_efectivo = [

                'numerofactura_venta' => $numero_factura['documento'],
                'idusuario' => $id_usuario,
                'idcaja' => 1,
                'idforma_pago' => 1,
                'fechafactura_forma_pago' => date('Y-m-d'),
                'hora' => date("H:i:s"),
                'valorfactura_forma_pago' => $efectivo,
                'idturno' => 1,
                'valor_pago' => $efectivo,
                'id_factura' => $id_factura,
                'fecha_y_hora_forma_pago' => $fecha_y_hora
            ];

            $factura_forma_pago_transaccion = [
                'numerofactura_venta' => $numero_factura['documento'],
                'idusuario' => $id_usuario,
                'idcaja' => 1,
                'idforma_pago' => 4,
                'fechafactura_forma_pago' => date('Y-m-d'),
                'hora' => date("H:i:s"),
                'valorfactura_forma_pago' => $transaccion,
                'idturno' => 1,
                'valor_pago' => $transaccion,
                'id_factura' => $id_factura,
                'fecha_y_hora_forma_pago' => $fecha_y_hora
            ];



            if ($efectivo > 0) {

                $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_efectivo);

                $consecutivo_ingreso = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 32)->first();

                $nit_cliente = model('facturaVentaModel')->select('nitcliente')->where('id', $id_factura)->first();

                $ingreso = [
                    'numero' => $consecutivo_ingreso['numeroconsecutivo'],
                    'concepto' => 'ABONO A FACTURA NUMERO ' . $numero_factura['documento'],
                    'tipo' => 1,
                    'id_relacion' => 0,
                    'fecha' => date('Y-m-d'),
                    'valor' => $abono,
                    'estado' => 'TRUE',
                    'saldo' => $saldo_actualizado,
                    'nitcliente' => $nit_cliente['nitcliente'],
                    'idcaja' => 1,
                    'idusuario' => $id_usuario

                ];

                $insert_ingreso = model('ingresoModel')->insert($ingreso);
                $id_ingreso = model('ingresoModel')->where('idusuario', $id_usuario)->insertID;
                $ingreso_efectivo = [
                    'idingreso' => $id_ingreso,
                    'idformapago' => 1,
                    'valor' => $efectivo,
                ];


                $insertar_forma_pago = model('ingresoFormaPagoModel')->insert($ingreso_efectivo);

                $data = [
                    'numeroconsecutivo' => $consecutivo_ingreso['numeroconsecutivo'] + 1,
                ];
                $model = model('consecutivosModel');
                $actualizar = $model->set($data);
                $actualizar = $model->where('idconsecutivos', 32);
                $actualizar = $model->update();


                $returnData = array(

                    "resultado" => 1,
                    "id_ingreso" => $id_ingreso
                );
                echo  json_encode($returnData);
            }

            if ($transaccion > 0) {

                $insert_transaccion = model('facturaFormaPagoModel')->insert($factura_forma_pago_transaccion);

                $returnData = array(

                    "resultado" => 1
                );
                echo  json_encode($returnData);
            }


            if ($transaccion > 0 and $efectivo > 0) {
                $insert_efectivo = model('facturaFormaPagoModel')->insert($factura_forma_pago_efectivo);
                $insert_transaccion = model('facturaFormaPagoModel')->insert($factura_forma_pago_transaccion);
                $returnData = array(

                    "resultado" => 1
                );
                echo  json_encode($returnData);
            }
        }
    }


    function imprimir_ingreso()
    {
        $id_ingreso = $_REQUEST['id_ingreso'];
        $datos_empresa = model('empresaModel')->datosEmpresa();
        $connector = new WindowsPrintConnector('FACTURACION');
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("FECHA:" . date('Y-m-d') . "\n");
        $id_usuario = model('ingresoModel')->select('idusuario')->where('id', $id_ingreso)->first();
        $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario)->first();
        $printer->text("CAJERO(A):" . $nombre_usuario['nombresusuario_sistema'] . "\n");
        $numero_ingreso = model('ingresoModel')->select('numero')->where('id', $id_ingreso)->first();
        $printer->text("COMPROBANTE DE INGRESO N°" . $numero_ingreso['numero'] . "\n");
        $printer->text("--------------------------------------------\n");
        $printer->text("\n");
        $nit_tercero = model('ingresoModel')->select('nitcliente')->where('id', $id_ingreso)->first();
        $nombre_tercero = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_tercero['nitcliente'])->first();
        $printer->text("RECIBIDO DE :" . $nombre_tercero['nombrescliente'] . "\n");
        $printer->text("NIT O CC :" . number_format($nit_tercero['nitcliente'], 0, ",", ".") . "\n");

        $printer->text("\n");
        $printer->text("\n");
        $concepto_ingreso = model('ingresoModel')->select('concepto')->where('id', $id_ingreso)->first();
        $printer->text($concepto_ingreso['concepto'] . "\n");


        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $total = model('ingresoModel')->select('valor')->where('id', $id_ingreso)->first();
        $printer->text("TOTAL ABONO :     " . "$" . number_format($total['valor'], 0, ",", ".") . "\n");
        $efectivo = model('ingresoFormaPagoModel')->select('valor')->where('idingreso', $id_ingreso)->first();
        $printer->text("PAGO CON :  " . "$" . number_format($efectivo['valor'], 0, ",", ".") . "\n");
        $saldo = model('ingresoModel')->select('saldo')->where('id', $id_ingreso)->first();

        $printer->text("SALDO CARTERA:     " . "$" . number_format($saldo['saldo'], 0, ",", ".") . "\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("FIRMA      ----------------------------------- \n\n");
        $printer->text("C.C O NIT: ----------------------------------- \n\n");
        $printer->text("FECHA:     ----------------------------------- \n\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();
    }


    function excel_mov()
    {

        $movimientos = model('TempMovModel')->findAll();

        //dd($movimientos);


        $file_name = 'Movimiento producto.xlsx';

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();


        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '4F81BD'], // Color de fondo (puedes cambiarlo)
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        // Aplicar estilo a las celdas A1:J1
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        $sheet->setCellValue('A1', 'Fecha');

        $sheet->setCellValue('B1', 'Hora');

        $sheet->setCellValue('C1', 'Movimiento');

        $sheet->setCellValue('D1', 'Producto');

        $sheet->setCellValue('E1', 'Cantidad inicial ');
        $sheet->setCellValue('F1', 'Cantidad movimiento ');
        $sheet->setCellValue('G1', 'Cantidad final ');
        $sheet->setCellValue('H1', 'Documento ');
        $sheet->setCellValue('I1', 'Usuario ');
        $sheet->setCellValue('J1', 'Notal ');

        $count = 2;


        foreach ($movimientos as $row) {
            $sheet->setCellValue('A' . $count, $row['fecha']);

            $sheet->setCellValue('B' . $count, $row['hora']);

            $sheet->setCellValue('C' . $count, $row['movimiento']);

            $sheet->setCellValue('D' . $count, $row['producto']);
            $sheet->setCellValue('E' . $count, $row['cantidad_inicial']);
            $sheet->setCellValue('F' . $count, $row['cantidad_movi']);
            $sheet->setCellValue('G' . $count, $row['cantidad_final']);
            $sheet->setCellValue('H' . $count, $row['documento']);
            $sheet->setCellValue('I' . $count, $row['usuario']);
            $sheet->setCellValue('J' . $count, $row['nota']);

            $count++;
        }

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

    function impuestos()
    {
        return view('impuestos/reporte_impuestos');
    }


    public function reporte_impuestos()
    {
        try {

            // =========================================================
            // 1. OBTENER DATOS
            // =========================================================

            $movimientos = model('ReporteImpuestosModel')->getValores();

            $fecha_inicial = model('ReporteImpuestosModel')
                ->selectMin('fecha')
                ->first();

            $fecha_final = model('ReporteImpuestosModel')
                ->selectMax('fecha')
                ->first();

            $ids = model('facturaElectronicaModel')->id_inicial_final(
                $fecha_inicial['fecha'],
                $fecha_final['fecha']
            );

            $primer_factura = model('facturaElectronicaModel')
                ->select('numero')
                ->where('id', $ids[0]['id_minimo'])
                ->first();

            $ultima_factura = model('facturaElectronicaModel')
                ->select('numero')
                ->where('id', $ids[0]['id_maximo'])
                ->first();

            $total_IVA = model('kardexModel')->get_iva_reporte(
                $fecha_inicial['fecha'],
                $fecha_final['fecha']
            );

            $total_INC = model('kardexModel')->get_ico_reporte(
                $fecha_inicial['fecha'],
                $fecha_final['fecha']
            );

            $total_venta = model('facturaElectronicaModel')->total_venta(
                $fecha_inicial['fecha'],
                $fecha_final['fecha']
            );

            $datos_empresa = model('empresaModel')->datosEmpresa();


            // =========================================================
            // 2. CONVERTIR VALORES A NÚMEROS REALES
            // =========================================================

            $numeroExcel = function ($valor) {

                if ($valor === null || $valor === '') {
                    return 0;
                }

                // Si viene como número desde la base de datos
                if (is_int($valor) || is_float($valor)) {
                    return $valor;
                }

                $valor = trim((string) $valor);

                if ($valor === '') {
                    return 0;
                }

                // Formato colombiano:
                // 1.020.500,50 -> 1020500.50
                if (strpos($valor, ',') !== false) {

                    $valor = str_replace('.', '', $valor);
                    $valor = str_replace(',', '.', $valor);

                    return (float) $valor;
                }

                // Formato de miles:
                // 472.685 -> 472685
                // 1.020.500 -> 1020500
                if (preg_match('/^-?\d{1,3}(\.\d{3})+$/', $valor)) {

                    $valor = str_replace('.', '', $valor);

                    return (float) $valor;
                }

                // Número normal:
                // 472685
                // 472685.50
                if (is_numeric($valor)) {
                    return (float) $valor;
                }

                return 0;
            };


            // =========================================================
            // 3. CREAR ARCHIVO EXCEL
            // =========================================================

            $file_name = 'Reporte_Impuestos_' .
                $fecha_inicial['fecha'] . '_al_' .
                $fecha_final['fecha'] . '.xlsx';

            $spreadsheet = new Spreadsheet();

            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setTitle('Reporte de impuestos');


            // =========================================================
            // 4. ESTILOS
            // =========================================================

            $colorAzul = '1F2937';
            $colorAzulClaro = '2563EB';
            $colorGris = 'F3F4F6';
            $colorBorde = 'D1D5DB';
            $colorBlanco = 'FFFFFF';

            // Encabezado de la tabla
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => [
                        'rgb' => $colorBlanco
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => $colorAzul
                    ],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => [
                            'rgb' => $colorAzul
                        ],
                    ],
                ],
            ];


            // =========================================================
            // 5. INFORMACIÓN DE LA EMPRESA
            // =========================================================

            $sheet->setCellValue(
                'A1',
                $datos_empresa[0]['nombrejuridicoempresa']
            );

            $sheet->mergeCells('A1:O1');

            $sheet->setCellValue(
                'A2',
                $datos_empresa[0]['nombrecomercialempresa']
            );

            $sheet->mergeCells('A2:O2');

            $sheet->setCellValue(
                'A3',
                'NIT: ' . $datos_empresa[0]['nitempresa']
            );

            $sheet->mergeCells('A3:O3');

            $sheet->setCellValue(
                'A4',
                'Dirección: ' .
                    $datos_empresa[0]['direccionempresa'] . ' ' .
                    $datos_empresa[0]['nombreciudad'] . ' ' .
                    $datos_empresa[0]['nombredepartamento']
            );

            $sheet->mergeCells('A4:O4');

            $sheet->setCellValue('A5', 'REPORTE DE IMPUESTOS');

            $sheet->mergeCells('A5:O5');


            // Estilo información empresa
            $sheet->getStyle('A1:O4')->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 10,
                    'color' => [
                        'rgb' => '374151'
                    ],
                ],
            ]);

            // Nombre jurídico
            $sheet->getStyle('A1:O1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => [
                        'rgb' => $colorAzul
                    ],
                ],
            ]);

            // Nombre comercial
            $sheet->getStyle('A2:O2')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => [
                        'rgb' => $colorAzulClaro
                    ],
                ],
            ]);

            // Título reporte
            $sheet->getStyle('A5:O5')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => [
                        'rgb' => $colorAzulClaro
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            $sheet->getRowDimension(5)->setRowHeight(30);


            // =========================================================
            // 6. INFORMACIÓN DEL RANGO Y FACTURAS
            // =========================================================

            $sheet->setCellValue('A6', 'Fecha inicial');
            $sheet->setCellValue('B6', $fecha_inicial['fecha']);

            $sheet->setCellValue('D6', 'Fecha final');
            $sheet->setCellValue('E6', $fecha_final['fecha']);

            $sheet->setCellValue('A7', 'Factura inicial');
            $sheet->setCellValue('B7', $primer_factura['numero']);

            $sheet->setCellValue('D7', 'Factura final');
            $sheet->setCellValue('E7', $ultima_factura['numero']);


            // Estilo etiquetas
            $sheet->getStyle('A6:A7')->getFont()->setBold(true);
            $sheet->getStyle('D6:D7')->getFont()->setBold(true);

            $sheet->getStyle('A6:E7')->applyFromArray([
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);


            // =========================================================
            // 7. ENCABEZADOS DE LA TABLA
            // =========================================================

            $encabezados = [
                'A10' => 'Día proceso',
                'B10' => 'Fecha',
                'C10' => 'Base INC 0',
                'D10' => 'INC 0',
                'E10' => 'Base INC 8',
                'F10' => 'INC 8',
                'G10' => 'Base IVA 0',
                'H10' => 'IVA 0',
                'I10' => 'Base IVA 5',
                'J10' => 'IVA 5',
                'K10' => 'Base IVA 19',
                'L10' => 'IVA 19',
                'M10' => 'Total INC',
                'N10' => 'Total IVA',
                'O10' => 'Total venta',
            ];

            foreach ($encabezados as $celda => $texto) {
                $sheet->setCellValue($celda, $texto);
            }

            $sheet->getStyle('A10:O10')->applyFromArray($headerStyle);

            $sheet->getRowDimension(10)->setRowHeight(35);


            // =========================================================
            // 8. REGISTROS DEL REPORTE
            // =========================================================

            $count = 11;

            foreach ($movimientos as $row) {

                $sheet->setCellValue(
                    'A' . $count,
                    (int) $row['dia_proceso']
                );

                $sheet->setCellValue(
                    'B' . $count,
                    $row['fecha']
                );

                // Todos los valores se guardan como números
                $sheet->setCellValue(
                    'C' . $count,
                    $numeroExcel($row['base_inc_0'])
                );

                $sheet->setCellValue(
                    'D' . $count,
                    $numeroExcel($row['inc_0'])
                );

                $sheet->setCellValue(
                    'E' . $count,
                    $numeroExcel($row['base_inc_8'])
                );

                $sheet->setCellValue(
                    'F' . $count,
                    $numeroExcel($row['inc_8'])
                );

                $sheet->setCellValue(
                    'G' . $count,
                    $numeroExcel($row['base_iva_0'])
                );

                $sheet->setCellValue(
                    'H' . $count,
                    $numeroExcel($row['iva_0'])
                );

                $sheet->setCellValue(
                    'I' . $count,
                    $numeroExcel($row['base_iva_5'])
                );

                $sheet->setCellValue(
                    'J' . $count,
                    $numeroExcel($row['iva_5'])
                );

                $sheet->setCellValue(
                    'K' . $count,
                    $numeroExcel($row['base_iva_19'])
                );

                $sheet->setCellValue(
                    'L' . $count,
                    $numeroExcel($row['iva_19'])
                );

                $sheet->setCellValue(
                    'M' . $count,
                    $numeroExcel($row['total_inc'])
                );

                $sheet->setCellValue(
                    'N' . $count,
                    $numeroExcel($row['total_iva'])
                );

                $sheet->setCellValue(
                    'O' . $count,
                    $numeroExcel($row['total_venta'])
                );

                $count++;
            }


            // =========================================================
            // 9. FILA DE TOTALES AUTOMÁTICOS
            // =========================================================

            $ultimaFila = $count - 1;

            $filaTotal = $count;

            $sheet->setCellValue(
                'A' . $filaTotal,
                'TOTAL'
            );

            // Sumar todas las columnas numéricas
            foreach (range('C', 'O') as $columna) {

                if ($ultimaFila >= 11) {

                    $sheet->setCellValue(
                        $columna . $filaTotal,
                        '=SUM(' . $columna . '11:' .
                            $columna . $ultimaFila . ')'
                    );
                } else {

                    $sheet->setCellValue(
                        $columna . $filaTotal,
                        0
                    );
                }
            }


            // Estilo fila total
            $sheet->getStyle(
                'A' . $filaTotal . ':O' . $filaTotal
            )->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => [
                        'rgb' => $colorBlanco
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => $colorAzul
                    ],
                ],
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => [
                            'rgb' => $colorAzulClaro
                        ],
                    ],
                ],
            ]);

            $sheet->getRowDimension($filaTotal)->setRowHeight(25);


            // =========================================================
            // 10. TOTALES GENERALES
            // =========================================================

            $filaResumen = $filaTotal + 2;

            // Total ventas
            $sheet->setCellValue(
                'N' . $filaResumen,
                'TOTAL VENTAS'
            );

            $sheet->setCellValue(
                'O' . $filaResumen,
                $numeroExcel($total_venta[0]['total_venta'])
            );


            // Total IVA
            $sheet->setCellValue(
                'N' . ($filaResumen + 1),
                'TOTAL IVA'
            );

            $sheet->setCellValue(
                'O' . ($filaResumen + 1),
                $numeroExcel($total_IVA[0]['iva'])
            );


            // Total INC
            $sheet->setCellValue(
                'N' . ($filaResumen + 2),
                'TOTAL INC'
            );

            $sheet->setCellValue(
                'O' . ($filaResumen + 2),
                $numeroExcel($total_INC[0]['ico'])
            );


            // Estilo resumen
            $sheet->getStyle(
                'N' . $filaResumen . ':O' . ($filaResumen + 2)
            )->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => [
                        'rgb' => $colorAzulClaro
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => [
                            'rgb' => $colorBorde
                        ],
                    ],
                ],
            ]);


            // =========================================================
            // 11. FORMATO NUMÉRICO
            // =========================================================

            // Formato de miles sin decimales
            $formatoNumero = '#,##0;[Red]-#,##0;0';

            // Formato numérico de todas las columnas
            $sheet->getStyle(
                'C11:O' . $filaTotal
            )->getNumberFormat()->setFormatCode($formatoNumero);

            // Formato numérico resumen
            $sheet->getStyle(
                'O' . $filaResumen . ':O' . ($filaResumen + 2)
            )->getNumberFormat()->setFormatCode($formatoNumero);


            // =========================================================
            // 12. ALINEACIÓN Y PRESENTACIÓN
            // =========================================================

            // Alinear valores numéricos a la derecha
            $sheet->getStyle(
                'C11:O' . $filaTotal
            )->getAlignment()->setHorizontal(
                Alignment::HORIZONTAL_RIGHT
            );

            // Centrar día y fecha
            $sheet->getStyle(
                'A11:B' . $ultimaFila
            )->getAlignment()->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );

            // Fecha
            $sheet->getStyle(
                'B11:B' . $ultimaFila
            )->getNumberFormat()->setFormatCode('yyyy-mm-dd');


            // Bordes suaves en los datos
            if ($ultimaFila >= 11) {

                $sheet->getStyle(
                    'A11:O' . $ultimaFila
                )->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR,
                            'color' => [
                                'rgb' => $colorBorde
                            ],
                        ],
                    ],
                ]);

                // Filas alternadas para facilitar lectura
                for ($fila = 11; $fila <= $ultimaFila; $fila++) {

                    if ($fila % 2 == 0) {

                        $sheet->getStyle(
                            'A' . $fila . ':O' . $fila
                        )->getFill()->setFillType(
                            \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
                        );

                        $sheet->getStyle(
                            'A' . $fila . ':O' . $fila
                        )->getFill()->getStartColor()->setRGB($colorGris);
                    }
                }
            }


            // =========================================================
            // 13. FILTROS Y ENCABEZADO FIJO
            // =========================================================

            if ($ultimaFila >= 11) {
                $sheet->setAutoFilter('A10:O' . $ultimaFila);
            }

            // Congelar encabezado y primeras columnas
            $sheet->freezePane('C11');


            // =========================================================
            // 14. AJUSTAR ANCHO DE COLUMNAS
            // =========================================================

            foreach (range('A', 'O') as $columna) {

                $sheet->getColumnDimension($columna)->setAutoSize(true);
            }

            // Anchos mínimos recomendados
            $sheet->getColumnDimension('A')->setWidth(13);
            $sheet->getColumnDimension('B')->setWidth(15);

            // Evitar columnas demasiado estrechas
            foreach (range('C', 'O') as $columna) {

                $sheet->getColumnDimension($columna)->setWidth(
                    max(
                        14,
                        $sheet->getColumnDimension($columna)->getWidth()
                    )
                );
            }


            // =========================================================
            // 15. CONFIGURACIÓN DE IMPRESIÓN
            // =========================================================

            $sheet->getPageSetup()
                ->setOrientation(
                    \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
                );

            $sheet->getPageSetup()
                ->setPaperSize(
                    \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER
                );

            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(10, 10);


            // =========================================================
            // 16. GENERAR Y DESCARGAR EXCEL
            // =========================================================

            $writer = new Xlsx($spreadsheet);

            // Calcular fórmulas al abrir el archivo
            $spreadsheet->getCalculationEngine();

            $writer->setPreCalculateFormulas(true);

            $writer->save($file_name);

            if (ob_get_length()) {
                ob_end_clean();
            }

            header(
                'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            );

            header(
                'Content-Disposition: attachment; filename="' .
                    basename($file_name) . '"'
            );

            header('Cache-Control: max-age=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_name));

            readfile($file_name);

            unlink($file_name);

            exit;
        } catch (\Throwable $e) {

            log_message(
                'error',
                'Error reporte_impuestos: ' . $e->getMessage()
            );

            return $this->response->setJSON([
                'status' => false,
                'mensaje' => 'Error al generar el reporte de impuestos: ' .
                    $e->getMessage()
            ]);
        }
    }


    function cruce_inventario()
    {

        $conteo_manual = model('inventarioModel')->cruce_inventario();
        $inventario = model('inventarioModel')->inventario();
        $productos = model('productoModel')->ProductoInventario();


        return view('inventarios/cruceInventarios', [
            'conteo_manual' => $conteo_manual,
            'inventario_sistema' => $inventario,
            'productos' => $productos
        ]);
    }

    function reporte_cruce_inventarios()
    {


        $inventario = model('inventarioModel')->findAll();
        $cruce = model('inventarioModel')->cruce_inventario();

        $datos_empresa = model('empresaModel')->datosEmpresa();
        $file_name = 'Cruce de inventarios  .xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '000000'], // Cambiado a negro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        $sheet->getStyle('A5')->applyFromArray([
            'font' => [
                'bold' => true, // Texto en negrita
                'size' => 14,  // Tamaño de fuente
                'color' => ['rgb' => '6297D5'], // Color azul pastel
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Alineación horizontal centrada
                'vertical' => Alignment::VERTICAL_CENTER,     // Alineación vertical centrada
            ],
        ]);

        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);


        /*  $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); */

        $sheet->mergeCells('A1:G1');

        // Centrar el texto horizontal y verticalmente
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Eliminar bordes en el rango
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);


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

        $sheet->setCellValue('A5', 'CRUCE DE INVENTARIOS ');
        $sheet->mergeCells('A5:G5');



        $sheet->getStyle('A7:G7')->applyFromArray($headerStyle);
        $sheet->setCellValue('A7', 'Código ');
        $sheet->setCellValue('B7', 'Producto');
        $sheet->setCellValue('C7', 'Cantidad conteo');
        $sheet->setCellValue('D7', 'Cantidad sistema ');
        $sheet->setCellValue('E7', 'Diferencia inventario  ');
        $sheet->setCellValue('F7', 'Valor costo ');
        $sheet->setCellValue('G7', 'Valor venta ');

        $count = 8;

        foreach ($inventario as $row) {

            $producto = model('inventarioModel')->conteo_manual($row['codigointernoproducto']);

            /*  $sheet->setCellValue('A' . $count, $row['codigointernoproducto']);
            $nombreProducto=model('productoModel')->select('nombreproducto')->where('codigointernoproducto',$row['codigointernoproducto'])->first();
            $sheet->setCellValue('B' . $count, $nombreProducto['nombreproducto']);
            $sheet->setCellValueExplicit('C' . $count, $row['cantidad_inventario_fisico'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $count, $row['cantidad_inventario'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $count, $row['diferencia'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $count, $row['valor_costo'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $count, $row['valor_venta'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $count++; */

            if (!empty($producto)) {

                $sheet->setCellValue('A' . $count, $producto[0]['codigointernoproducto']);
                $sheet->setCellValue('B' . $count, $producto[0]['nombreproducto']);
                $sheet->setCellValueExplicit('C' . $count, $producto[0]['cantidad_inventario_fisico'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D' . $count, $producto[0]['cantidad_inventario'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E' . $count, $producto[0]['diferencia'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('F' . $count, number_format($producto[0]['valor_costo'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('G' . $count, number_format($producto[0]['valor_venta'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $count++;
            }
        }

        //$total_venta=model('inventarioModel')->selectSum()

        /* $sheet->setCellValue('N' . ($count + 1), 'Total IVA'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 1), number_format($total_IVA[0]['iva'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Agregamos "Total ICO"
        $sheet->setCellValue('N' . ($count + 2), 'Total INC'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 2), number_format($total_INC[0]['ico'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); */

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
    function reporte_sobrantes()
    {

        //$cruce = model('inventarioModel')->sobrantes();

        $inventario = model('inventarioModel')->findAll();
        $cruce = model('inventarioModel')->cruce_inventario();

        $datos_empresa = model('empresaModel')->datosEmpresa();
        $file_name = 'Productos sobrantes.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '000000'], // Cambiado a negro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        $sheet->getStyle('A5')->applyFromArray([
            'font' => [
                'bold' => true, // Texto en negrita
                'size' => 14,  // Tamaño de fuente
                'color' => ['rgb' => '6297D5'], // Color azul pastel
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Alineación horizontal centrada
                'vertical' => Alignment::VERTICAL_CENTER,     // Alineación vertical centrada
            ],
        ]);

        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);


        /*  $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); */

        $sheet->mergeCells('A1:G1');

        // Centrar el texto horizontal y verticalmente
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Eliminar bordes en el rango
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);


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

        $sheet->setCellValue('A5', 'CRUCE DE INVENTARIOS ');
        $sheet->mergeCells('A5:G5');


        $sheet->setCellValue('A6', 'PRODUCTOS SOBRANTES');
        $sheet->mergeCells('A6:G6');



        $sheet->getStyle('A7:G7')->applyFromArray($headerStyle);
        $sheet->setCellValue('A7', 'Código ');
        $sheet->setCellValue('B7', 'Producto');
        $sheet->setCellValue('C7', 'Cantidad conteo');
        $sheet->setCellValue('D7', 'Cantidad sistema ');
        $sheet->setCellValue('E7', 'Diferencia inventario  ');
        $sheet->setCellValue('F7', 'Valor costo ');
        $sheet->setCellValue('G7', 'Valor venta ');

        $count = 8;

        foreach ($inventario as $row) {

            $producto = model('inventarioModel')->conteo_manual($row['codigointernoproducto']);

            if (!empty($producto)) {
                if ($producto[0]['diferencia'] > 0) {
                    $sheet->setCellValue('A' . $count, $producto[0]['codigointernoproducto']);
                    $sheet->setCellValue('B' . $count, $producto[0]['nombreproducto']);
                    $sheet->setCellValueExplicit('C' . $count, $producto[0]['cantidad_inventario_fisico'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $count, $producto[0]['cantidad_inventario'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('E' . $count, $producto[0]['diferencia'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('F' . $count, number_format($producto[0]['valor_costo'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('G' . $count, number_format($producto[0]['valor_venta'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $count++;
                }
            }
        }
        //$total_venta=model('inventarioModel')->selectSum()

        /* $sheet->setCellValue('N' . ($count + 1), 'Total IVA'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 1), number_format($total_IVA[0]['iva'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Agregamos "Total ICO"
        $sheet->setCellValue('N' . ($count + 2), 'Total INC'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 2), number_format($total_INC[0]['ico'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); */

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
    function reporte_faltantes()
    {

        //$cruce = model('inventarioModel')->sobrantes();

        $inventario = model('inventarioModel')->findAll();
        $cruce = model('inventarioModel')->cruce_inventario();

        $datos_empresa = model('empresaModel')->datosEmpresa();
        $file_name = 'Productos faltantes.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '000000'], // Cambiado a negro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        $sheet->getStyle('A5')->applyFromArray([
            'font' => [
                'bold' => true, // Texto en negrita
                'size' => 14,  // Tamaño de fuente
                'color' => ['rgb' => '6297D5'], // Color azul pastel
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Alineación horizontal centrada
                'vertical' => Alignment::VERTICAL_CENTER,     // Alineación vertical centrada
            ],
        ]);

        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);


        /*  $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER); */

        $sheet->mergeCells('A1:G1');

        // Centrar el texto horizontal y verticalmente
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Eliminar bordes en el rango
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);


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

        $sheet->setCellValue('A5', 'CRUCE DE INVENTARIOS ');
        $sheet->mergeCells('A5:G5');


        $sheet->setCellValue('A6', 'PRODUCTOS SOBRANTES');
        $sheet->mergeCells('A6:G6');



        $sheet->getStyle('A7:G7')->applyFromArray($headerStyle);
        $sheet->setCellValue('A7', 'Código ');
        $sheet->setCellValue('B7', 'Producto');
        $sheet->setCellValue('C7', 'Cantidad conteo');
        $sheet->setCellValue('D7', 'Cantidad sistema ');
        $sheet->setCellValue('E7', 'Diferencia inventario  ');
        $sheet->setCellValue('F7', 'Valor costo ');
        $sheet->setCellValue('G7', 'Valor venta ');

        $count = 8;

        foreach ($inventario as $row) {

            $producto = model('inventarioModel')->conteo_manual($row['codigointernoproducto']);

            if (!empty($producto)) {
                if ($producto[0]['diferencia'] < 0) {
                    $sheet->setCellValue('A' . $count, $producto[0]['codigointernoproducto']);
                    $sheet->setCellValue('B' . $count, $producto[0]['nombreproducto']);
                    $sheet->setCellValueExplicit('C' . $count, $producto[0]['cantidad_inventario_fisico'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('D' . $count, $producto[0]['cantidad_inventario'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('E' . $count, $producto[0]['diferencia'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('F' . $count, number_format($producto[0]['valor_costo'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('G' . $count, number_format($producto[0]['valor_venta'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $count++;
                }
            }
        }
        //$total_venta=model('inventarioModel')->selectSum()

        /* $sheet->setCellValue('N' . ($count + 1), 'Total IVA'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 1), number_format($total_IVA[0]['iva'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

        // Agregamos "Total ICO"
        $sheet->setCellValue('N' . ($count + 2), 'Total INC'); // Título en la columna N (siguiente fila)
        $sheet->setCellValueExplicit('O' . ($count + 2), number_format($total_INC[0]['ico'], 0, ",", "."), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING); */

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

    function productos_inventario()
    {
        $productos = model('productoModel')->getInventario();

        return $this->response->setJSON([
            'success' => true,
            'productos' => view('ventas/productos', [
                'productos' => $productos
            ])
        ]);
    }


    function Inventario()
    {
        $inventario = model('inventarioModel')->findAll();
        $cruce = model('inventarioModel')->cruce_inventario();
        $datos_empresa = model('empresaModel')->datosEmpresa();
        $file_name = 'Inventario.xlsx';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Establecer el estilo de las celdas del encabezado
        $headerStyle = [
            'font' => [
                'bold' => true,           // Negrita
                'size' => 12,             // Tamaño de fuente
                'color' => ['argb' => 'FFFFFF'], // Color de fuente blanco
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Alineación centrada
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,   // Alineación centrada vertical
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, // Relleno sólido
                'startColor' => ['argb' => '000000'], // Cambiado a negro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // Bordes finos
                    'color' => ['argb' => '000000'], // Color de borde negro
                ],
            ],
        ];

        $sheet->setCellValue('A1', $datos_empresa[0]['nombrejuridicoempresa']);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE);

        // Agregar datos de la empresa y encabezados de inventario
        $sheet->setCellValue('A2', $datos_empresa[0]['nombrecomercialempresa']);
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:G2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A3', 'NIT: ' . $datos_empresa[0]['nitempresa']);
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:G3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A4', 'Dirección: ' . $datos_empresa[0]['direccionempresa'] . " " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento']);
        $sheet->mergeCells('A4:G4');
        $sheet->getStyle('A4:G4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:G4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Título del reporte
        $sheet->setCellValue('A5', 'INVENTARIOS GENERAL');
        $sheet->mergeCells('A5:G5');
        $sheet->getStyle('A7:F7')->applyFromArray($headerStyle);

        // Encabezados de columnas
        $sheet->setCellValue('A7', 'Categoria ');
        $sheet->setCellValue('B7', 'Código');
        $sheet->setCellValue('C7', 'Producto');
        $sheet->setCellValue('D7', 'Cantidad  ');
        $sheet->setCellValue('E7', 'Costo unidad ');
        $sheet->setCellValue('F7', 'Costo total ');

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Poner los datos del inventario
        $productos = model('productoModel')->ExcelInventario();
        // dd($productos);
        $count = 8;
        foreach ($productos as $item) {
            $sheet->setCellValue('A' . $count, $item['nombrecategoria']);
            $sheet->setCellValue('B' . $count, $item['codigointernoproducto']);
            $sheet->setCellValue('C' . $count, $item['nombreproducto']);
            $sheet->setCellValue('D' . $count, $item['cantidad_inventario']);
            $sheet->setCellValue('E' . $count, $item['costo_unitario']); // Diferencia
            $sheet->setCellValue('F' . $count, $item['costo_producto']);
            $count++;
        }

        // Ajustar el ancho de las columnas automáticamente

        $costoTotal = model('productoModel')->TotalInv();

        $unidades = model('inventarioModel')
            ->selectSum('cantidad_inventario')
            ->where('cantidad_inventario >', 0)
            ->findAll();

        $sheet->setCellValue('E' . $count, 'Costo Total:');
        $sheet->setCellValue('F' . $count, $costoTotal[0]['costo_total']); // Imprime el costo total
        $count++;

        $sheet->setCellValue('E' . $count, 'Total Unidades:');
        $sheet->setCellValue('F' . $count, $unidades[0]['cantidad_inventario']); // Imprime el total de unidades
        $count++;

        // Ajustar el ancho de las columnas automáticamente
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Guardar y forzar la descarga del archivo
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

    function closeModal()
    {

        $conteo_manual = model('inventarioModel')->cruce_inventario();
        $inventario = model('inventarioModel')->inventario();
        $productos = model('productoModel')->ProductoInventario();

        return $this->response->setJSON([
            'success' => true,
            'productos' => view('ventas/ingresoInventario', [
                'conteo_manual' => $conteo_manual,
                'inventario_sistema' => $inventario,
                'productos' => $productos
            ])
        ]);
    }

    public function abonar()
    {
        $request = service('request');
        $data = $request->getJSON(true);

        $id_factura  = $data['id_factura'] ?? null;
        $id_estado   = $data['id_estado'] ?? null;
        $efectivo    = (float) ($data['efectivo'] ?? 0);
        $transaccion = (float) ($data['transaccion'] ?? 0);
        $id_usuario  = $data['id_usuario'] ?? null;

        $clase_pago = !empty($data['clase_pago'])
            ? $data['clase_pago']
            : 0;

        $modelPago  = model('pagosModel');
        $modelForma = model('facturaFormaPagoModel');

        /*
    |--------------------------------------------------------------------------
    | VALIDAR FACTURA
    |--------------------------------------------------------------------------
    */
        $datos_factura = $modelPago
            ->select('saldo, documento')
            ->where('id_factura', $id_factura)
            ->where('id_estado', $id_estado)
            ->first();

        if (!$datos_factura) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Factura no encontrada'
            ]);
        }

        $saldoActual = (float) $datos_factura['saldo'];

        /*
    |--------------------------------------------------------------------------
    | VALIDAR APERTURA
    |--------------------------------------------------------------------------
    */
        $id_apertura = model('aperturaRegistroModel')
            ->select('numero')
            ->first();

        if (empty($id_apertura)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No hay apertura de caja'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | TOTAL PAGADO
    |--------------------------------------------------------------------------
    */
        $totalPagado = $efectivo + $transaccion;

        /*
    |--------------------------------------------------------------------------
    | VALIDAR VALOR
    |--------------------------------------------------------------------------
    */
        if ($totalPagado <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Debe ingresar un valor mayor a cero'
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | VALIDAR QUE EL ABONO NO SUPERE EL SALDO
    |--------------------------------------------------------------------------
    */
        if ($totalPagado > $saldoActual) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'El valor del abono no puede ser mayor al saldo pendiente. Saldo actual: $' . number_format($saldoActual, 0, ',', '.')
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | INSERTAR PAGO
    |--------------------------------------------------------------------------
    */
        $modelForma->insert([
            'numerofactura_venta'     => $datos_factura['documento'],
            'idusuario'               => $id_usuario,
            'idcaja'                  => 1,
            'idforma_pago'            => 1,
            'fechafactura_forma_pago' => date('Y-m-d'),
            'hora'                    => date('H:i:s'),
            'valorfactura_forma_pago' => $saldoActual,
            'idturno'                 => 1,
            'valor_pago'              => $totalPagado,
            'id_factura'              => $id_factura,
            'fecha_y_hora_forma_pago' => date('Y-m-d H:i:s'),
            'id_estado'               => $id_estado,
            'id_clase_pago'           => $clase_pago,
            'id_apertura'             => $id_apertura['numero'],
            'efectivo'                => $efectivo,
            'electronico'             => $transaccion,
            'tipo_movimiento'         => 2
        ]);

        /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR SALDO
    |--------------------------------------------------------------------------
    */
        $nuevoSaldo = $saldoActual - $totalPagado;

        $modelPago->set('saldo', $nuevoSaldo)
            ->where('id_factura', $id_factura)
            ->where('id_estado', $id_estado)
            ->update();

        /*
    |--------------------------------------------------------------------------
    | OBTENER ÚLTIMO ID
    |--------------------------------------------------------------------------
    */
        $id_transaccion = $modelForma->getInsertID();

        return $this->response->setJSON([
            'success'        => true,
            'nuevo_saldo'    => $nuevoSaldo,
            'id_factura'     => $id_factura,
            'id_estado'      => $id_estado,
            'id_transaccion' => $id_transaccion
        ]);
    }

    /*   public function editarAbonar()
    {
        try {

            $data = $this->request->getJSON(true);

            $efectivo = str_replace('.', '', $data['efectivo'] ?? 0);
            $transferencia = str_replace('.', '', $data['transferencia'] ?? 0);
            $id = $data['id'];

            $datos = model('facturaFormaPagoModel')->select('id_factura,id_estado')->where('idfactura_forma_pago', $id)->first();
            $saldo = model('pagosModel')->select('saldo')->where('id_factura', $datos['id_factura'])
                ->where('id_estado', $datos['id_estado'])->first();



            $efectivo = (int) $efectivo;
            $transferencia = (int) $transferencia;

            $total_pagado = $efectivo + $transferencia;


            if ($total_pagado > $saldo['saldo']) {
                return $this->response->setJSON([
                    'success'      => true,
                    'mensaje' => "No es posible realizar este pago por que es mayor al saldo actual"
                ]);
            } else {
                $pago=[
                    'valor_pago'=>$total_pagado,
                     'efectivo'=>$efectivo,
                     'electronico'=>$transferencia
                ];

            $actualizar_pago=model('facturaFormaPagoModel')->set($pago)->where('idfactura_forma_pago',$id)->update();
            $saldo_actual =model('facturaFormaPagoModel')->selectSum('valor_pago')->first();

            $actualizar_saldo=model('pagosModel')->set('saldo',$saldo_actual['valor_pago'])->where('id_factura',$datos['id_factura'])
            ->where('id_estadpo',$datos['id_estado'])
            ->update();

                return $this->response->setJSON([
                    'success'      => true,
                    'mesanje' => "Edicion de abono con éxito "
                ]);
            }
        } catch (\Throwable $e) {

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
        }
    } */

    /*   public function editarAbonar()
    {
        try {

            $data = $this->request->getJSON(true);

            $id             = $data['id'] ?? null;
            $efectivo       = (int) str_replace('.', '', $data['efectivo'] ?? 0);
            $transferencia  = (int) str_replace('.', '', $data['transferencia'] ?? 0);
            $banco             = $data['banco'] ?? null;

            $datos = model('facturaFormaPagoModel')
                ->select('id_factura,id_estado')
                ->where('idfactura_forma_pago', $id)
                ->first();

            if (!$datos) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se encontró el abono.'
                ]);
            }

            $saldo = model('pagosModel')
                ->select('saldo,total_documento')
                ->where('id_factura', $datos['id_factura'])
                ->where('id_estado', $datos['id_estado'])
                ->first();

            if (!$saldo) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se encontró el saldo.'
                ]);
            }

            $total_pagado = $efectivo + $transferencia;

            if ($total_pagado > $saldo['saldo']) {

                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No es posible realizar este pago porque es mayor al saldo actual.'
                ]);
            }

            $pago = [
                'valor_pago'    => $total_pagado,
                'id_clase_pago' => $banco,
                'efectivo'      => $efectivo,
                'electronico'   => $transferencia
            ];

            model('facturaFormaPagoModel')
                ->set($pago)
                ->where('idfactura_forma_pago', $id)
                ->update();

            $saldo_actual = model('facturaFormaPagoModel')
                ->selectSum('valor_pago')
                ->where('id_factura', $datos['id_factura'])
                ->where('id_estado', $datos['id_estado'])
                ->first();

            model('pagosModel')
                ->set('saldo', $saldo['total_documento'] - $saldo_actual['valor_pago'])
                ->where('id_factura', $datos['id_factura'])
                ->where('id_estado', $datos['id_estado'])
                ->update();

            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Edición de abono con éxito.'
            ]);
        } catch (\Throwable $e) {

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'mensaje' => $e->getMessage()
                ]);
        }
    } */

    public function editarAbonar()
    {
        try {

            $data = $this->request->getJSON(true);

            $id             = $data['id'] ?? null;
            $efectivo       = (int) str_replace('.', '', $data['efectivo'] ?? 0);
            $transferencia  = (int) str_replace('.', '', $data['transferencia'] ?? 0);
            $banco          = $data['banco'] ?? null;

            if (empty($id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se recibió el id del abono.'
                ]);
            }

            /*
        |------------------------------------------------------------------
        | OBTENER EL ABONO ACTUAL
        |------------------------------------------------------------------
        */
            $abonoActual = model('facturaFormaPagoModel')
                ->select('id_factura,id_estado,valor_pago')
                ->where('idfactura_forma_pago', $id)
                ->first();

            if (!$abonoActual) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se encontró el abono.'
                ]);
            }

            /*
        |------------------------------------------------------------------
        | OBTENER INFORMACIÓN DEL SALDO
        |------------------------------------------------------------------
        */
            $saldo = model('pagosModel')
                ->select('saldo,total_documento')
                ->where('id_factura', $abonoActual['id_factura'])
                ->where('id_estado', $abonoActual['id_estado'])
                ->first();

            if (!$saldo) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se encontró el saldo.'
                ]);
            }

            /*
        |------------------------------------------------------------------
        | VALIDAR NUEVO VALOR DEL ABONO
        |------------------------------------------------------------------
        |
        | Como es una edición, se devuelve temporalmente el valor actual
        | del abono al saldo disponible para validar correctamente.
        |
        */
            $total_pagado = $efectivo + $transferencia;

            $saldoDisponible = $saldo['saldo'] + $abonoActual['valor_pago'];

            if ($total_pagado > $saldoDisponible) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No es posible realizar este pago porque supera el saldo disponible.'
                ]);
            }

            /*
        |------------------------------------------------------------------
        | ACTUALIZAR ABONO
        |------------------------------------------------------------------
        */
            $pago = [
                'valor_pago'    => $total_pagado,
                'id_clase_pago' => $banco,
                'efectivo'      => $efectivo,
                'electronico'   => $transferencia
            ];

            model('facturaFormaPagoModel')
                ->set($pago)
                ->where('idfactura_forma_pago', $id)
                ->update();

            /*
        |------------------------------------------------------------------
        | RECALCULAR TOTAL ABONADO
        |------------------------------------------------------------------
        */
            $totalAbonado = model('facturaFormaPagoModel')
                ->selectSum('valor_pago')
                ->where('id_factura', $abonoActual['id_factura'])
                ->where('id_estado', $abonoActual['id_estado'])
                ->first();

            $nuevoSaldo = max(
                0,
                $saldo['total_documento'] - ($totalAbonado['valor_pago'] ?? 0)
            );

            /*
        |------------------------------------------------------------------
        | ACTUALIZAR SALDO
        |------------------------------------------------------------------
        */
            model('pagosModel')
                ->set('saldo', $nuevoSaldo)
                ->where('id_factura', $abonoActual['id_factura'])
                ->where('id_estado', $abonoActual['id_estado'])
                ->update();

            return $this->response->setJSON([
                'success'      => true,
                'mensaje'      => 'Edición de abono realizada con éxito.',
                'saldo_actual' => $nuevoSaldo,
                'id'           => $id
            ]);
        } catch (\Throwable $e) {

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'mensaje' => $e->getMessage()
                ]);
        }
    }
}
