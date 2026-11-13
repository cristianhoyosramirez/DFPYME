<?php

namespace App\Libraries;

require APPPATH . "Controllers/mike42/autoload.php";

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use \DateTime;
use \DateTimeZone;

class impresion
{
    /**
     * Calcular imuestos
     * @param   $cod(igo_interno
     * 
     */

    function imprimir_cuadre_caja($id_apertura)
    {


        /*
    |--------------------------------------------------------------------------
    | MODELOS
    |--------------------------------------------------------------------------
    */
        $impresionFacturaModel       = model('impresionFacturaModel');
        $empresaModel                = model('empresaModel');
        $impresorasModel             = model('impresorasModel');
        $aperturaModel               = model('aperturaModel');
        $cierreModel                 = model('cierreModel');
        $pagosModel                  = model('pagosModel');
        $facturaFormaPagoModel       = model('facturaFormaPagoModel');
        $clasePagoModel              = model('clasePagoModel');
        $configuracionPedidoModel    = model('configuracionPedidoModel');
        $retiroModel                 = model('retiroModel');
        $retiroFormaPagoModel        = model('retiroFormaPagoModel');
        $devolucionModel             = model('devolucionModel');
        $detalleDevolucionVentaModel = model('detalleDevolucionVentaModel');
        $productoModel               = model('productoModel');
        $devolucionVentaEfectivoModel = model('devolucionVentaEfectivoModel');
        $cierreFormaPagoModel        = model('cierreFormaPagoModel');

        /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN DE IMPRESORA
    |--------------------------------------------------------------------------
    */
        $impresora = $impresionFacturaModel
            ->select('id_impresora')
            ->first();

        $nombreImpresora = $impresorasModel
            ->select('nombre')
            ->where('id', $impresora['id_impresora'])
            ->first();

        $datosEmpresa = $empresaModel->datosEmpresa()[0];

        $connector = new WindowsPrintConnector($nombreImpresora['nombre']);
        $printer   = new Printer($connector);

        /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN GENERAL DE IMPRESIÓN
    |--------------------------------------------------------------------------
    */
        $titulo = 22;
        $valor  = 22;
        $linea  = str_repeat('-', 48);

        /*
    |--------------------------------------------------------------------------
    | FUNCIONES AUXILIARES
    |--------------------------------------------------------------------------
    */
        $moneda = static function ($monto): string {
            return '$ ' . number_format((float) ($monto ?? 0), 0, ',', '.');
        };

        $fila = static function ($printer, string $texto, $monto) use ($moneda): void {
            $texto = mb_strimwidth($texto, 0, 28, '', 'UTF-8');

            $printer->text(
                str_pad($texto, 28, ' ', STR_PAD_RIGHT) .
                    str_pad($moneda($monto), 16, ' ', STR_PAD_LEFT) .
                    "\n"
            );
        };

        $tituloSeccion = static function ($printer, string $titulo): void {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text($titulo . "\n");
            $printer->setEmphasis(false);
            $printer->setJustification(Printer::JUSTIFY_LEFT);
        };

        /*
    |--------------------------------------------------------------------------
    | DATOS DE APERTURA Y CIERRE
    |--------------------------------------------------------------------------
    */
        $apertura = $aperturaModel
            ->select('fecha, hora, valor')
            ->where('id', $id_apertura)
            ->first();

        $cierre = $cierreModel
            ->select('id, fecha, hora')
            ->where('idapertura', $id_apertura)
            ->first();

        $fechaApertura = $apertura['fecha'] ?? '';
        $horaApertura  = !empty($apertura['hora'])
            ? date('g:i a', strtotime($apertura['hora']))
            : '';

        $textoCierre = !empty($cierre)
            ? ($cierre['fecha'] ?? '') . ' ' .
            (!empty($cierre['hora'])
                ? date('g:i a', strtotime($cierre['hora']))
                : '')
            : 'Sin cierre';

        $valorApertura = (float) ($apertura['valor'] ?? 0);

        /*
    |--------------------------------------------------------------------------
    | ENCABEZADO
    |--------------------------------------------------------------------------
    */
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);

        $printer->text($datosEmpresa['nombrecomercialempresa'] . "\n");
        $printer->text($datosEmpresa['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT: " . $datosEmpresa['nitempresa'] . "\n");
        $printer->text(
            $datosEmpresa['direccionempresa'] . '  ' .
                $datosEmpresa['nombreciudad'] . ' ' .
                $datosEmpresa['nombredepartamento'] . "\n"
        );
        $printer->text("TELEFONO: " . $datosEmpresa['telefonoempresa'] . "\n");
        $printer->text($datosEmpresa['nombreregimen'] . "\n");

        $tituloSeccion($printer, 'MOVIMIENTO DE CAJA');

        $printer->text(
            "Fecha apertura:  {$fechaApertura} {$horaApertura}\n"
        );
        $printer->text("Fecha cierre:    {$textoCierre}\n");
        $printer->text("Caja N°: 1\n");
        $printer->text($linea . "\n");

        /*
    |--------------------------------------------------------------------------
    | VENTAS
    |--------------------------------------------------------------------------
    */
        $totalContado = (float) (
            $pagosModel
                ->selectSum('total_documento')
                ->where('id_apertura', $id_apertura)
                ->where('forma_pago', 1)
                ->where('id_estado !=', 6)
                ->first()['total_documento'] ?? 0
        );

        $totalCredito = (float) (
            $pagosModel
                ->selectSum('total_documento')
                ->where('id_apertura', $id_apertura)
                ->where('forma_pago', 2)
                ->first()['total_documento'] ?? 0
        );

        $propinas = (float) (
            $pagosModel
                ->selectSum('propina')
                ->where('id_apertura', $id_apertura)
                ->first()['propina'] ?? 0
        );

        $totalVentas = $totalContado + $totalCredito;

        $totalAbonos = (float) (
            $facturaFormaPagoModel->totalPago($id_apertura)[0]['valor_pago'] ?? 0
        );

        $tituloSeccion($printer, 'VENTAS');

        $fila($printer, 'Ventas de contado:', $totalContado);
        $fila($printer, 'Ventas a crédito:', $totalCredito);

        $printer->text($linea . "\n");

        $printer->setEmphasis(true);
        $fila($printer, 'TOTAL VENTAS:', $totalVentas);
        $printer->setEmphasis(false);

        $printer->text($linea . "\n");

        /*
    |--------------------------------------------------------------------------
    | PROPINAS
    |--------------------------------------------------------------------------
    */
        $fila($printer, 'PROPINAS:', $propinas);
        $fila($printer, 'VENTAS-PROPINAS:', $totalVentas - $propinas);

        $printer->text($linea . "\n");

        /*
    |--------------------------------------------------------------------------
    | CORTESÍAS
    |--------------------------------------------------------------------------
    */
        $totalCortesias = (float) (
            $pagosModel
                ->selectSum('total_documento')
                ->where('id_apertura', $id_apertura)
                ->where('forma_pago', 1)
                ->where('id_estado', 6)
                ->first()['total_documento'] ?? 0
        );

        $tituloSeccion($printer, 'CORTESIAS');
        $fila($printer, 'Total cortesias:', $totalCortesias);

        $printer->text($linea . "\n");

        /*
    |--------------------------------------------------------------------------
    | INGRESOS
    |--------------------------------------------------------------------------
    */
        $tituloSeccion($printer, 'INGRESOS');

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $fila($printer, 'Apertura en efectivo:', $valorApertura);
        $fila($printer, 'Ventas de contado:', $totalContado);
        $fila($printer, 'Abonos a credito:', $totalAbonos);

        $totalIngresosEfectivo = $valorApertura + $totalContado + $totalAbonos;

        $printer->text($linea . "\n");
        $fila($printer, 'Total:', $totalIngresosEfectivo);
        $printer->text($linea . "\n");

        /*
    |--------------------------------------------------------------------------
    | FORMAS DE PAGO
    |--------------------------------------------------------------------------
    */
        $tituloSeccion($printer, 'FORMAS DE PAGO DE VENTAS');

        $ingresoEfectivo = (float) (
            $pagosModel
                ->selectSum('efectivo')
                ->where('id_apertura', $id_apertura)
                ->first()['efectivo'] ?? 0
        );

        $ingresoTransferencia = (float) (
            $pagosModel
                ->selectSum('transferencia')
                ->where('id_apertura', $id_apertura)
                ->first()['transferencia'] ?? 0
        );

        $efectivoAbonos = (float) (
            $facturaFormaPagoModel
                ->selectSum('efectivo')
                ->where('id_apertura', $id_apertura)
                ->first()['efectivo'] ?? 0
        );

        $electronicoAbonos = (float) (
            $facturaFormaPagoModel
                ->selectSum('electronico')
                ->where('id_apertura', $id_apertura)
                ->first()['electronico'] ?? 0
        );

        $fila(
            $printer,
            'Ingresos efectivo',
            $ingresoEfectivo + $efectivoAbonos
        );

        $mediosPago = $pagosModel->medioPago($id_apertura);

        foreach ($mediosPago as $medio) {
            $clasePago = $clasePagoModel
                ->select('nombre')
                ->where('id', $medio['id_clase_pago'])
                ->first();

            $fila(
                $printer,
                $clasePago['nombre'] ?? 'Desconocido',
                $medio['total'] ?? 0
            );
        }
        $descuentos = model('pagosModel')->selectSum('descuentos')->where('id_apertura', $id_apertura)->first()['descuentos'];


        $fila(
            $printer,
            'Descuentos',
            $descuentos
        );

        $totalFormasPago =
            $ingresoEfectivo +
            $efectivoAbonos +
            $ingresoTransferencia +
            $electronicoAbonos;

        $printer->text($linea . "\n");

        $printer->setEmphasis(true);
        $fila($printer, 'Total formas de pago', $totalFormasPago);
        $printer->setEmphasis(false);

        $printer->text($linea . "\n");

        /*
    |--------------------------------------------------------------------------
    | INFORMACIÓN HOTEL
    |--------------------------------------------------------------------------
    */
        $hotel = $configuracionPedidoModel->reporte_hotel($id_apertura);
        $existeCampo = $configuracionPedidoModel->existeCampo();

        if (($existeCampo[0]['exists'] ?? '') === 't') {
            $tituloSeccion($printer, 'INFORMACION HOTEL');

            $fila(
                $printer,
                'Ingresos hotel',
                $hotel[0]['total'] ?? 0
            );

            $printer->text($linea . "\n");
            $printer->text("* Valor solo informativo\n");
        }

        /*
    |--------------------------------------------------------------------------
    | RETIROS
    |--------------------------------------------------------------------------
    */
        $tituloSeccion($printer, 'RETIROS');

        $retiros = $retiroModel
            ->where('id_apertura', $id_apertura)
            ->findAll();

        $totalRetiros = 0;

        foreach ($retiros as $retiro) {
            $printer->text(
                'FECHA: ' .
                    ($retiro['fecha'] ?? '') . ' ' .
                    (!empty($retiro['hora'])
                        ? date('g:i a', strtotime($retiro['hora']))
                        : '') .
                    "\n"
            );

            $concepto = $retiroFormaPagoModel
                ->select('concepto')
                ->where('idretiro', $retiro['id'])
                ->first();

            $concepto = trim($concepto['concepto'] ?? '');
            $concepto = htmlspecialchars($concepto, ENT_QUOTES, 'UTF-8');

            $valorRetiro = $retiroFormaPagoModel
                ->select('valor')
                ->where('idretiro', $retiro['id'])
                ->first();

            $valor = $valorRetiro['valor'] ?? 0;

            if (is_numeric($valor)) {
                $monto = (float) $valor;
            } else {
                $montoLimpio = preg_replace('/[^0-9,.-]/', '', (string) $valor);
                $monto = (float) str_replace(',', '.', $montoLimpio);
            }

            $totalRetiros += $monto;

            $printer->text($concepto . "\n");
            $printer->text("VALOR: $" . number_format($monto, 0, ',', '.') . "\n");
        }

        $printer->text($linea . "\n");
        $fila($printer, 'Total retiros:', $totalRetiros);
        $printer->text($linea . "\n");

        /*
    |--------------------------------------------------------------------------
    | DEVOLUCIONES
    |--------------------------------------------------------------------------
    */
        $tituloSeccion($printer, 'DEVOLUCIONES');

        $devoluciones = $devolucionModel
            ->where('id_apertura', $id_apertura)
            ->findAll();

        $totalDevoluciones = 0;

        foreach ($devoluciones as $devolucion) {
            $codigo = $detalleDevolucionVentaModel
                ->select('codigo')
                ->where('id_devolucion_venta', $devolucion['id'])
                ->first();

            $producto = $productoModel
                ->select('nombreproducto')
                ->where(
                    'codigointernoproducto',
                    $codigo['codigo'] ?? ''
                )
                ->first();

            $valor = $devolucionVentaEfectivoModel
                ->select('valor')
                ->where('iddevolucion', $devolucion['id'])
                ->first();

            $monto = (float) ($valor['valor'] ?? 0);
            $totalDevoluciones += $monto;

            $fechaHora =
                ($devolucion['fecha'] ?? '') . ' ' .
                (!empty($devolucion['hora'])
                    ? date('g:i a', strtotime($devolucion['hora']))
                    : '');

            $nombreProducto = mb_strimwidth(
                $producto['nombreproducto'] ?? '',
                0,
                18,
                '',
                'UTF-8'
            );

            $printer->text(
                str_pad($fechaHora, 20) .
                    str_pad($nombreProducto, 18) .
                    str_pad(
                        $moneda($monto),
                        10,
                        ' ',
                        STR_PAD_LEFT
                    ) .
                    "\n"
            );
        }

        $printer->text($linea . "\n");
        $fila($printer, 'Total devoluciones:', $totalDevoluciones);
        $printer->text($linea . "\n");

        /*
    |--------------------------------------------------------------------------
    | EFECTIVO EN CAJA
    |--------------------------------------------------------------------------
    */
        $tituloSeccion(
            $printer,
            'INGRESOS-(RETIROS+DEVOLUCIONES)'
        );

        $efectivoCaja =
            $ingresoEfectivo +
            $valorApertura +
            $efectivoAbonos;

        $totalDescuentosCaja =
            $totalRetiros +
            $totalDevoluciones;

        $totalEnCaja =
            $efectivoCaja -
            $totalDescuentosCaja;

        $fila($printer, 'Ingresos a caja', $efectivoCaja);
        $fila($printer, '(-) Total retiros', $totalRetiros);
        $fila($printer, '(-) Total devoluciones', $totalDevoluciones);

        $printer->text($linea . "\n");

        $printer->setEmphasis(true);
        $fila($printer, '(=) Efectivo en caja', $totalEnCaja);
        $printer->setEmphasis(false);

        /*
    |--------------------------------------------------------------------------
    | CIERRE DE CAJA
    |--------------------------------------------------------------------------
    */
        $tituloSeccion($printer, 'CIERRE DE CAJA');

        $idCierre = $cierre['id'] ?? null;

        $cierreEfectivo = 0;
        $cierreTransacciones = 0;

        if ($idCierre) {
            $valorCierreEfectivo =
                $cierreFormaPagoModel
                ->valor_cierre_efectivo_usuario($idCierre);

            $valorCierreTransaccion =
                $cierreFormaPagoModel
                ->valor_cierre_transaccion_usuario($idCierre);

            $cierreEfectivo = (float) (
                $valorCierreEfectivo[0]['valor'] ?? 0
            );

            $cierreTransacciones = (float) (
                $valorCierreTransaccion[0]['valor'] ?? 0
            );
        }

        $transaccionesCaja =
            $ingresoTransferencia +
            $electronicoAbonos;

        $diferenciaEfectivo =
            $cierreEfectivo -
            $totalEnCaja;

        $diferenciaTransacciones =
            $cierreTransacciones -
            $transaccionesCaja;

        $totalDiferencias =
            $diferenciaEfectivo +
            $diferenciaTransacciones;

        $fila($printer, 'Efectivo caja', $totalEnCaja);
        $fila($printer, 'Cierre efectivo', $cierreEfectivo);
        $fila($printer, 'Diferencia efectivo', $diferenciaEfectivo);

        $printer->text("\n");

        $fila($printer, 'Transacciones caja', $transaccionesCaja);
        $fila($printer, 'Cierre transacciones', $cierreTransacciones);
        $fila($printer, 'Diferencia transaccion', $diferenciaTransacciones);

        $printer->text($linea . "\n");

        $printer->setEmphasis(true);
        $fila($printer, 'TOTAL DIFERENCIAS', $totalDiferencias);
        $printer->setEmphasis(false);

        /*
    |--------------------------------------------------------------------------
    | FINALIZAR IMPRESIÓN
    |--------------------------------------------------------------------------
    */
        $printer->feed(1);
        $printer->cut();
        $printer->close();
    }




    function imprimir_factura_electronica($id_factura)  // Cuando es prefactura 
    {

        $id_factura = $id_factura;
        //$id_factura = 24;
        $id_impresora = model('cajaModel')->select('id_impresora')->first();
        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $id_estado = model('facturaElectronicaModel')->select('id_status')->where('id', $id_factura)->first();
        $numero = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura)->first();




        if ($id_estado['id_status'] == 1) {
            $estado = "PENDIENTE";
        }
        if ($id_estado['id_status'] == 2) {
            $estado = "FIRMADO";
        }

        $nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $id_factura)->first();
        $nombres_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $direccion = model('clientesModel')->select('direccioncliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $telefono = model('clientesModel')->select('telefonocliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $email = model('clientesModel')->select('emailcliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();


        $datos_empresa = model('empresaModel')->datosEmpresa();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");

        $actividadEconimica = model('empresaCiiuModel')->select('codigo')->first();
        $printer->text("Actividad Economica Principal: " . $actividadEconimica['codigo'] . "\n");

        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");

        $id_regimen = model('empresaModel')->select('idregimen')->first();
        $regimen = model('regimenModel')->select('descripcion')->where('idregimen', $id_regimen['idregimen'])->first();

        $printer->text($regimen['descripcion']);
        /*  $printer->text($datos_empresa[0]['nombreregimen'] . "\n"); */
        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("ORDEN DE PEDIDO  " . $numero['numero'] . "\n");

        $tipoVenta = model('facturaElectronicaModel')->select('metodo_pago')->where('id', $id_factura)->first();

        if ($tipoVenta['metodo_pago'] == 1) {
            $venta = "CONTADO";
        } else if ($tipoVenta['metodo_pago'] == 2) {
            $venta = "CRÉDITO";
        }

        $printer->text("TIPO DE VENTA:   $venta  \n");


        $printer->text("FECHA:           " . date('Y-m-d') . "\n");
        $printer->text("CAJA:            1"  . "\n");
        $printer->text("CAJERO:          Usuario administrador"  . "\n");
        //$printer->text("\n");
        $items = model('itemFacturaElectronicaModel')->where('id_de', $id_factura)->findAll();


        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CLIENTE:         " . $nombres_cliente['nombrescliente'] . "\n");
        $printer->text("NIT :            " . $nit_cliente['nit_cliente']  . "\n");
        $printer->text("DIRECCIÓN:       " . $direccion['direccioncliente']  . "\n");
        $printer->text("TELEFÓNO         " . $telefono['telefonocliente'] . "\n");
        $printer->text("EMAIL:           " . $email['emailcliente'] . "\n");


        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CÓDIGO    DESCRIPCIÓN   VALOR UNITARIO    TOTAL" . "\n");
        $printer->text("---------------------------------------------" . "\n");


        foreach ($items as $productos) {

            $printer->setTextSize(1, 1);
            $printer->text("Cod." . $productos['codigo'] . "      " . $productos['descripcion'] . "\n");
            $printer->text("Cant. " . $productos['cantidad'] . "      " . "$" . number_format($productos['neto'], 0, ',', '.') . "                   " . "$" . number_format($productos['neto'] * $productos['cantidad'], 0, ',', '.') . "\n");
            if (!empty($productos['nota_producto'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("NOTAS:\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text($productos['nota_producto'] . "\n");
            }

            //$printer->text("\n");
        }


        $inc = model('kardexModel')->get_total_inc($id_factura);
        $iva = model('kardexModel')->get_total_iva($id_factura);



        //$total =  model('pagosModel')->select('total_documento')->where('id_factura', $id_factura)->first();
        $total =  model('kardexModel')->get_total_factura($id_factura);

        //$total =  model('pagosModel')->select('total_documento')->where('id_factura', $id_factura)->first();
        $cambio =  model('pagosModel')->select('cambio')
            ->where('id_factura', $id_factura)
            ->where('id_estado', 8)
            ->first();

        $propina =  model('pagosModel')->select('propina')
            ->where('id_factura', $id_factura)
            ->where('id_estado', 8)
            ->first();


        $transferencia =  model('kardexModel')->get_recibido_transferencia($id_factura);
        $efectivo =  model('kardexModel')->get_recibido_efectivo($id_factura);

        $sub_total = ($total[0]['valor'] - ($inc[0]['total_inc'] + $iva[0]['total_iva'])) - $propina['propina'];





        $printer->setJustification(Printer::JUSTIFY_RIGHT);

        /*   if ($id_regimen['idregimen'] == 1) {

            $printer->text("SUB TOTAL : "   .    "$ ".number_format($sub_total, 0, ",", ".") . "\n");
            $printer->text("INC       :"    .    "$ ".number_format($inc[0]['total_inc'], 0, ",", ".") . "\n");
            $printer->text("IVA       :"    .    "$ ".number_format($iva[0]['total_iva'], 0, ",", ".") . "\n");
            $printer->text("PROPINA   :"    .    "$ ".number_format($propina['propina'], 0, ",", ".") . "\n");
        } */
        $printer->text(str_pad("SUB TOTAL", 15) . ": " . str_pad("$ " . number_format($sub_total, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");

        if ($id_regimen['idregimen'] == 1) {
            $printer->text(str_pad("INC", 15) . ": " . str_pad("$ " . number_format($inc[0]['total_inc'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
            $printer->text(str_pad("IVA", 15) . ": " . str_pad("$ " . number_format($iva[0]['total_iva'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        }
        //$printer->text("\n");
        $printer->text(str_pad("PROPINA", 15) . ": " . str_pad("$ " . number_format($propina['propina'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        //$printer->text("\n");
        $printer->setTextSize(1, 2);
        $printer->setEmphasis(true); // Negrita (resaltado)
        $printer->text(str_pad(" TOTAL", 15) . ":" . "$ " . number_format($total[0]['valor'], 0, ",", ".") . "\n");
        $printer->setEmphasis(false); // Desactiva la negrita
        //$printer->text("\n");
        $printer->setTextSize(1, 1);


        if ($efectivo[0]['recibido_efectivo'] > 0) {
            $printer->text(str_pad("EFECTIVO ", 15) . ": " . str_pad("$ " . number_format($efectivo[0]['recibido_efectivo'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        }

        if ($transferencia[0]['recibido_transferencia'] > 0) {
            $medioPago = model('pagosModel')->getMedioPago($id_factura);
            $printer->text(str_pad($medioPago[0]['nombre_medio_pago'], 15) . ": " . str_pad("$ " . number_format($transferencia[0]['recibido_transferencia'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");
        }

        $printer->text(str_pad("CAMBIO", 15) . ": " . str_pad("$ " . number_format($cambio['cambio'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");

        $temp_encabezado = model('ConfiguracionPedidoModel')->select('encabezado_factura')->first();
        $encabezado = $temp_encabezado['encabezado_factura'];
        //$printer->text("$encabezado \n");

        $total = model('facturaElectronicaModel')->select('total')->where('id', $id_factura)->first();
        $inc_tarifa = model('kardexModel')->get_inc_calc($id_factura);

        $iva_tarifa = model('kardexModel')->get_iva_calc($id_factura);


        if ($id_regimen['idregimen'] == 1) {
            if (!empty($inc_tarifa)) {
                $printer->text("** DISCRIMINACIÓN DE TARIFAS DE INC **   \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $printer->text(str_pad("TARIFA", 10, " ") . str_pad("BASE", 15, " ") . str_pad(" INC ",  15, " ") .            "VENTA\n");
                /* foreach ($inc_tarifa as $detalle) {
                    $inc = model('kardexModel')->get_tarifa_ico($id_factura, $detalle['valor_ico']);
                    $tarifa = $inc_tarifa[0]['valor_ico'] . " %";
                    $base_inc = number_format(($total['total'] - $inc[0]['inc']), 0, ",", ".");
                    $inc = number_format(($total['total'] - ($total['total'] - $inc[0]['inc'])), 0, ",", ".");
                    $venta = number_format($total['total'], 0, ",", ".");

                    $printer->text(str_pad($tarifa, 10, " ") . str_pad($base_inc, 15, " ") . " " .   str_pad($inc, 15, " ") . "  " . $venta . "\n");
                } */

                foreach ($inc_tarifa as $detalle) {

                    $inc = model('kardexModel')->get_tarifa_ico($id_factura, $detalle['valor_ico']);

                    $tarifa   = $detalle['valor_ico'] . "%";
                    $base_inc = number_format($total['total'] - $inc[0]['inc'], 0, ",", ".");
                    $valorInc = number_format($inc[0]['inc'], 0, ",", ".");
                    $venta    = number_format($total['total'], 0, ",", ".");

                    $printer->text(
                        str_pad($tarifa, 2, " ", STR_PAD_RIGHT) .
                            str_pad($base_inc, 14, " ", STR_PAD_LEFT) .
                            str_pad($valorInc, 14, " ", STR_PAD_LEFT) .
                            str_pad($venta, 14, " ", STR_PAD_LEFT) .
                            "\n"
                    );
                }
            }

            if (!empty($iva_tarifa)) {

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("** DISCRIMINACIÓN DE TARIFAS DE IVA **   \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);


                $printer->text(str_pad("TARIFA", 10, " ") . str_pad("BASE", 10, " ") . str_pad("IVA", 10, " ") . "VENTA\n");


                foreach ($iva_tarifa as $detalle) {
                    $iva = model('kardexModel')->get_tarifa_iva($id_factura, $detalle['valor_iva']);  //Esto es el valor total del iva 
                    $venta_total_iva = model('kardexModel')->get_base_iva($id_factura, $detalle['valor_iva']); // Esto es el valor total de la venta 

                    $tarifa = $detalle['valor_iva'] . " %";
                    //$base_iva = number_format(($total['total'] - $iva[0]['iva']), 0, ",", ".");
                    $base_iva = number_format(($venta_total_iva[0]['total_iva'] - $iva[0]['iva']), 0, ",", ".");
                    $iva = number_format($iva[0]['iva'], 0, ",", ".");
                    //$venta = number_format($total['total'], 0, ",", ".");
                    $venta = number_format($venta_total_iva[0]['total_iva'], 0, ",", ".");

                    $printer->text(str_pad($tarifa, 10, " ") . str_pad("$" . $base_iva, 10, " ") . str_pad("$" . $iva, 10, " ") . "$" . $venta . "\n");
                }
            }
        }



        $printer->text("_______________________________________________ ");
        $nota = model('facturaElectronicaModel')->select('nota')->where('id', $id_factura)->first();
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        if (!empty($nota['nota'])) {
            $printer->text("Nota:" . $nota['nota']);
        }
        // $printer->text("\n");
        //$printer->text("\n");

        $mesaMesero = model('pagosModel')->getMesaMesero($id_factura);

        if (!empty($mesaMesero)) {
            $printer->text("Mesa: " . $mesaMesero[0]['nombre_mesa'] .  "\n");
            $printer->text("Mesero: " . $mesaMesero[0]['nombre_mesero'] . "\n");
        }


        $textoPropina = model('configuracionPedidoModel')->select('permitir_impresion_texto_propina')->first();


        //$printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        //$printer->text("Actividad Economica Principal: " . $actividadEconimica['codigo'] . "\n");


        if ($textoPropina['permitir_impresion_texto_propina'] == 't') {

            $printer->text("\n");

            $texto = model('configuracionPedidoModel')->select('texto_propina')->first();

            $printer->text($texto['texto_propina'] . "\n");
        }

        $printer->text("\n");

        $total = model('facturaElectronicaModel')->select('total')->where('id', $id_factura)->first();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text("SOFTWARE DFPYME INTREDETE. \n");
        $printer->text("INTREDETE 901448365\n");
        $printer->text("\n");

        $printer->text("*GRACIAS POR SER NUESTROS CLIENTES* \n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }


    function impresion_factura_electronica($id_factura)  //Ya ha sido trasmitoda 
    {
        //$id_factura = 2026;

        $id_factura = $id_factura;
        $id_impresora = model('cajaModel')->select('id_impresora')->first();
        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();
        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);


        $id_estado = model('facturaElectronicaModel')->select('id_status')->where('id', $id_factura)->first();
        $numero = model('facturaElectronicaModel')->select('numero')->where('id', $id_factura)->first();

        $fecha = model('facturaElectronicaModel')->select('fecha,fecha_limite')->where('id', $id_factura)->first();
        $hora = model('facturaElectronicaModel')->select('hora')->where('id', $id_factura)->first();


        if ($id_estado['id_status'] == 1) {
            $estado = "PENDIENTE";
        }
        if ($id_estado['id_status'] == 2) {
            $estado = "FIRMADO";
        }

        $nit_cliente = model('facturaElectronicaModel')->select('nit_cliente')->where('id', $id_factura)->first();
        $nombres_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $direccion = model('clientesModel')->select('direccioncliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $telefono = model('clientesModel')->select('telefonocliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();
        $email = model('clientesModel')->select('emailcliente')->where('nitcliente', $nit_cliente['nit_cliente'])->first();


        $datos_empresa = model('empresaModel')->datosEmpresa();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT: " . $datos_empresa[0]['nitempresa'] . "\n");

        $actividadEconimica = model('empresaCiiuModel')->select('codigo')->first();
        $printer->text("Actividad Economica Principal: " . $actividadEconimica['codigo'] . "\n");

        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");

        $temp_descripcion = model('regimenModel')->select('descripcion')->where('idregimen', $datos_empresa[0]['idregimen'])->first();
        $descripcion = $temp_descripcion['descripcion'];

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text($descripcion);



        // Obtener el encabezado de la base de datos
        $temp_encabezado = model('ConfiguracionPedidoModel')->select('encabezado_factura')->first();
        $encabezado = $temp_encabezado['encabezado_factura'];
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("$encabezado \n");

        $printer->text("\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("FACTURA ELECTRÓNICA DE VENTA NÚMERO: " . $numero['numero'] . "\n");

        $metodoPago = model('facturaElectronicaModel')->select('metodo_pago,medio_pago')->where('id', $id_factura)->first();

        if ($metodoPago['metodo_pago'] == 1) {
            $venta = "CONTADO";
        }

        if ($metodoPago['metodo_pago'] == 2) {
            $venta = "CRÉDITO";
        }

        $medioPago = model('medioPagoModel')->select('nombre_comercial')->where('codigo', $metodoPago['medio_pago'])->first();

        $printer->text("FORMA DE PAGO: " . $venta . " \n");
        $printer->text("MEDIO DE PAGO: " . $medioPago['nombre_comercial'] . " \n");


        /* $printer->text("FECHA Y HORA DE GENERACIÓN: " . $fecha['fecha'] ." ".$hora['hora'] ."\n"); */
        $printer->text("FECHA GENERACIÓN: " . $fecha['fecha'] . "      HORA: " . $hora['hora'] . "\n");
        //$printer->text("FECHA LIMITE: " . $fecha['fecha'] . "      HORA: " . $hora['hora'] . "\n");
        $printer->text("FECHA LIMITE: " . $fecha['fecha_limite'] .  "\n");

        $printer->text("CAJA:            1"  . "\n");
        $printer->text("CAJERO:          Usuario administrador"  . "\n");
        //$printer->text("\n");




        $items = model('kardexModel')->get_productos_factura($id_factura);

        $printer->text("---------------------------------------------" . "\n");
        $printer->text("CLIENTE:     " . $nombres_cliente['nombrescliente'] . "\n");
        $printer->text("NIT/CC:      " . $nit_cliente['nit_cliente']  . "\n");
        $printer->text("DIRECCIÓN:   " . $direccion['direccioncliente']  . "\n");
        $printer->text("TELEFÓNO    " . $telefono['telefonocliente'] . "\n");
        $printer->text("EMAIL:       " . $email['emailcliente'] . "\n");

        $printer->text("\n");


        /*      
        foreach ($items as $productos) {

            $printer->setTextSize(1, 1);
            $printer->text("Cod." . $productos['codigo'] . "      " . $productos['descripcion'] . "\n");

            if ($productos['aplica_ico'] == 't') {
                $impuesto = $productos['valor_ico'];
            }
            if ($productos['aplica_ico'] == 'f') {
                $impuesto = $productos['valor_iva'];
            }



            $printer->text("Cant. " . $productos['cantidad'] . "      " . "$" . number_format($productos['neto'], 0, ',', '.') . "               " . "$" . number_format($productos['total'], 0, ',', '.') . " " .  $impuesto . "%" . "\n");
            if (!empty($productos['nota_producto'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("NOTAS:\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text($productos['nota_producto'] . "\n");
            }

            $printer->text("\n");
        }  */

        $printer->text("Cant     Unitario         Total  Imp\n");
        $printer->text("---------------------------------------\n");

        foreach ($items as $productos) {

            $impuesto = ($productos['aplica_ico'] == 't')
                ? $productos['valor_ico']
                : $productos['valor_iva'];

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);

            // Código y descripción
            $printer->text(
                "[" . $productos['codigo'] . "] " .
                    $productos['descripcion'] . "\n"
            );

            // Línea de valores alineados
            $linea =
                str_pad($productos['cantidad'] . "x", 5) .
                str_pad(
                    number_format($productos['neto'], 0, ',', '.'),
                    15,
                    ' ',
                    STR_PAD_LEFT
                ) .
                str_pad(
                    number_format($productos['total'], 0, ',', '.'),
                    15,
                    ' ',
                    STR_PAD_LEFT
                ) .
                str_pad(
                    $impuesto . "%",
                    6,
                    ' ',
                    STR_PAD_LEFT
                );

            $printer->text($linea . "\n");

            // Observaciones
            if (!empty($productos['nota_producto'])) {
                $printer->text(
                    "Obs: " . $productos['nota_producto'] . "\n"
                );
            }
        }

        $inc = model('kardexModel')->get_total_inc($id_factura);
        $iva = model('kardexModel')->get_total_iva($id_factura);

        //$total =  model('kardexModel')->get_total_factura($id_factura);
        $total =  model('pagosModel')->select('valor')
            ->where('id_factura', $id_factura)
            ->where('id_estado', 8)
            ->first();

        $transferencia =  model('kardexModel')->get_recibido_transferencia($id_factura);
        $efectivo =  model('kardexModel')->get_recibido_efectivo($id_factura);
        $propina =  model('pagosModel')->select('propina')->where('id_factura', $id_factura)->first();

        //$sub_total = ($total[0]['valor'] - ($inc[0]['total_inc'] + $iva[0]['total_iva'])) - $propina['propina'];
        //$sub_total = ($total['valor'] - ($inc[0]['total_inc'] + $iva[0]['total_iva']));

        $sub_total = (
            $total['valor'] -
            (
                ($inc[0]['total_inc'] ?? 0) +
                ($iva[0]['total_iva'] ?? 0) +
                ($propina['propina'] ?? 0)
            )
        );


        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("_______________________________________________ ");
        $id_regimen = model('empresaModel')->select('idregimen')->first();
        $printer->text(
            str_pad("SUB TOTAL", 15) . ": " .
                str_pad("$ " . number_format($sub_total, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n"
        );

        if ($id_regimen['idregimen'] == 1) {

            $printer->text(
                str_pad("INC", 15) . ": " .
                    str_pad("$ " . number_format($inc[0]['total_inc'] ?? 0, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n"
            );

            $printer->text(
                str_pad("IVA", 15) . ": " .
                    str_pad("$ " . number_format($iva[0]['total_iva'] ?? 0, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n"
            );
        }

        $printer->text(
            str_pad("PROPINA", 15) . ": " .
                str_pad("$ " . number_format($propina['propina'] ?? 0, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n"
        );

        $descuentos = model('DescuentoModel')->getDescuento($id_factura);

        $printer->setTextSize(1, 1);
        $printer->setEmphasis(true);

        $printer->text(
            str_pad("TOTAL", 15) . ": " .
                str_pad("$ " . number_format(($total['valor'] ?? 0) , 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n"
        );

        $printer->setEmphasis(false);
        $printer->setTextSize(1, 1);

        $printer->text(
            str_pad("DESCUENTOS", 15) . ": " .
                str_pad("$ " . number_format($descuentos[0]['descuento'] ?? 0, 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n"
        );

        $printer->setEmphasis(true);

        $printer->text(
            str_pad("TOTAL A PAGAR", 15) . ": " .
                str_pad(
                    "$ " . number_format(
                        (($total['valor'] ?? 0) ) - ($descuentos[0]['descuento'] ?? 0),
                        0,
                        ",",
                        "."
                    ),
                    10,
                    " ",
                    STR_PAD_LEFT
                ) . "\n"
        );

        $printer->setEmphasis(false);
        $printer->setTextSize(1, 1);

        $cambio = model('kardexModel')->cambio($id_factura);

        if (($efectivo[0]['recibido_efectivo'] ?? 0) > 0) {
            $printer->text(
                str_pad("PAGO EFECTIVO", 15) . ": " .
                    str_pad("$ " . number_format($efectivo[0]['recibido_efectivo'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n"
            );
        }

        if (($transferencia[0]['recibido_transferencia'] ?? 0) > 0) {
            $printer->text(
                str_pad("PAGO TRANSFERENCIA", 15) . ": " .
                    str_pad("$ " . number_format($transferencia[0]['recibido_transferencia'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n"
            );
        }
        $printer->text(str_pad("CAMBIO", 15) . ": " . str_pad("$ " . number_format($cambio[0]['cambio'], 0, ",", "."), 10, " ", STR_PAD_LEFT) . "\n");




        $total = model('facturaElectronicaModel')->select('total')->where('id', $id_factura)->first();

        $inc_tarifa = model('kardexModel')->get_inc_calc($id_factura);
        $iva_tarifa = model('kardexModel')->get_iva_calc($id_factura);



        $items = model('kardexModel')->items($id_factura);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Numero de items: " . $items[0]['id'] .  "\n");
        $printer->text("________________________________________________");

        //if (!empty($inc_tarifa)) {

        $printer->setJustification(Printer::JUSTIFY_CENTER);

        if ($id_regimen['idregimen'] == 1) {
            if (!empty($inc_tarifa)) {
                $printer->text("** DISCRIMINACIÓN DE TARIFAS DE INC **   \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);


                $printer->text(str_pad("TARIFA", 10, " ") . str_pad("BASE", 12, " ") . str_pad("INC", 12, " ") . "VENTA\n");



                foreach ($inc_tarifa as $detalle) {
                    $inc = model('kardexModel')->get_tarifa_ico($id_factura, $detalle['valor_ico']);
                    $totalInc = model('kardexModel')->get_total_ico($id_factura, $detalle['valor_ico']);
                    $tarifa = $inc_tarifa[0]['valor_ico'] . " %";
                    $base_inc = number_format(($totalInc[0]['total_inc'] - $inc[0]['inc']), 0, ",", ".");
                    $inc = number_format(($inc[0]['inc']), 0, ",", ".");
                    $venta = number_format($totalInc[0]['total_inc'], 0, ",", ".");

                    $printer->text(str_pad($tarifa, 10, " ") . "$" . str_pad($base_inc, 12, " ") . "$" . str_pad($inc, 12, " ") . "$" . $venta . "\n");
                }
            }



            if (!empty($iva_tarifa)) {

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->text("** DISCRIMINACIÓN DE TARIFAS DE IVA **   \n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $printer->text(str_pad("TARIFA", 10, " ") . str_pad("BASE", 10, " ") . str_pad("IVA", 10, " ") . "VENTA\n");

                foreach ($iva_tarifa as $detalle) {
                    $iva = model('kardexModel')->get_tarifa_iva($id_factura, $detalle['valor_iva']);  //Esto es el valor total del iva 
                    $venta_total_iva = model('kardexModel')->get_base_iva($id_factura, $detalle['valor_iva']); // Esto es el valor total de la venta 

                    $tarifa = $detalle['valor_iva'] . " %";
                    //$base_iva = number_format(($total['total'] - $iva[0]['iva']), 0, ",", ".");
                    $base_iva = number_format(($venta_total_iva[0]['total_iva'] - $iva[0]['iva']), 0, ",", ".");
                    $iva = number_format($iva[0]['iva'], 0, ",", ".");
                    //$venta = number_format($total['total'], 0, ",", ".");
                    $venta = number_format($venta_total_iva[0]['total_iva'], 0, ",", ".");

                    $printer->text(str_pad($tarifa, 10, " ") . str_pad("$" . $base_iva, 10, " ") . str_pad("$" . $iva, 10, " ") . "$" . $venta . "\n");
                }
            }
        }
        //}

        $id_resolucion = model('facturaElectronicaModel')->select('id_resolucion')->where('id', $id_factura)->first();

        //dd( $id_resolucion  )


        $datos_resolucion = model('resolucionElectronicaModel')->where('id', $id_resolucion['id_resolucion'])->first();

        $printer->text("\n");
        $printer->text("Resolución DIAN No " . $datos_resolucion['numero'] . " de " . $datos_resolucion['date_begin'] . "\n");
        $printer->text("del " . $datos_resolucion['number_begin'] . " al " . $datos_resolucion['number_end'] . " prefijo " . $datos_resolucion['prefijo'] . " VIGENCIA " . $datos_resolucion['vigency'] . " MESES" . "\n");
        $printer->text("\n");




        //$printer->text("Vigencia " . $datos_resolucion['vigency'] . "\n\n");






        $qr = model('facturaElectronicaModel')->select('qrcode')->where('id', $id_factura)->first();
        $cufe = model('facturaElectronicaModel')->select('cufe')->where('id', $id_factura)->first();
        //$printer->qrCode($qr['qrcode']);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Representación gráfica de factura electrónica \n");

        $printer->text("número: " . $numero['numero'] . "\n\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->qrCode($qr['qrcode'], Printer::QR_ECLEVEL_L, 4);

        $printer->text("\n");
        $actividadEconimica = model('empresaCiiuModel')->select('codigo')->first();
        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("CUFE: \n" . $cufe['cufe'] . "\n");



        $printer->setTextSize(1, 1);
        $printer->text("_______________________________________________ ");

        $temp_pie = model('ConfiguracionPedidoModel')->select('pie_factura')->first();
        $pie = $temp_pie['pie_factura'];
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("$pie ");


        $nota = model('facturaElectronicaModel')->select('nota')->where('id', $id_factura)->first();
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        if (!empty($nota['nota'])) {
            $printer->text("Nota:" . $nota['nota']);
        }

        $mesaMesero = model('pagosModel')->getMesaMesero($id_factura);

        if (!empty($mesaMesero)) {
            $printer->text(
                "Mesa: " . $mesaMesero[0]['nombre_mesa'] .
                    " | Mesero: " . $mesaMesero[0]['nombre_mesero'] .
                    "\n"
            );
        }


        $textoPropina = model('configuracionPedidoModel')->select('permitir_impresion_texto_propina')->first();

        if ($textoPropina['permitir_impresion_texto_propina'] == 't') {

            $printer->text("\n");

            $texto = model('configuracionPedidoModel')->select('texto_propina')->first();

            $printer->text($texto['texto_propina'] . "\n");
        }

        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("SOFTWARE DFPYME \n");
        $printer->text("INTREDETE 901448365 \n");
        $printer->text("Proveedor tecnológico \n");
        $printer->text("DATAICO SAS  901223648\n\n");
        $printer->text("*GRACIAS POR SER NUESTROS CLIENTES*\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }


    /*   public function imprimir_factura($id_factura)
    {

        //$id_factura = 28;

        $id_factura = $id_factura;


        $numero_factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $id_factura)->first();

        $regimen = model('empresaModel')->select('idregimen')->first();

        if (!empty($numero_factura['numerofactura_venta'])) {

            $numero_factura['numerofactura_venta'];


            $fecha_factura_venta = model('facturaVentaModel')->select('fecha_factura_venta')->where('id', $id_factura)->first();
            $hora_factura_venta = model('facturaVentaModel')->select('horafactura_venta')->where('id', $id_factura)->first();
            $id_usuario = model('facturaVentaModel')->select('idusuario_sistema')->where('id', $id_factura)->first();
            $nombre_usuario = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $id_usuario['idusuario_sistema'])->first();
            $datos_empresa = model('empresaModel')->datosEmpresa();

            $nit_cliente = model('facturaVentaModel')->select('nitcliente')->where('id', $id_factura)->first();
            $nombre_cliente = model('clientesModel')->select('nombrescliente')->where('nitcliente', $nit_cliente['nitcliente'])->first();

            $id_impresora = model('cajaModel')->select('id_impresora')->first();

            $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

            $connector = new WindowsPrintConnector($nombre_impresora['nombre']);

            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 2);
            $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
            $printer->setTextSize(1, 1);
            $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
            $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
            $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
            $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
            $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
            $id_regimen = model('empresaModel')->select('idregimen')->first();
            $regimen = model('regimenModel')->select('descripcion')->where('idregimen', $id_regimen['idregimen'])->first();
         
            $printer->text("\n");


            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $estado_factura = model('facturaVentaModel')->estado_factura($id_factura);
            if ($estado_factura[0]['idestado'] == 1 or $estado_factura[0]['idestado'] == 2) {
                $printer->text("FACTURA DE VENTA: " . $numero_factura['numerofactura_venta'] . "\n");
            }

            if ($estado_factura[0]['idestado'] == 7) {
                $printer->text("REMISION : " . $numero_factura['numerofactura_venta'] . "\n");
            }


            $forma_pago = model('pagosModel')->select('forma_pago')
                ->where('id_factura', $id_factura)
                ->where('id_estado', $estado_factura[0]['idestado'])
                ->first()['forma_pago'];



            $tipoVenta = ($forma_pago == 1)
                ? 'CONTADO'
                : 'CRÉDITO';

            $printer->text("TIPO DE VENTA: {$tipoVenta}\n");



            $printer->text("FECHA:" . " " . $fecha_factura_venta['fecha_factura_venta'] . "  " . date("g:i a", strtotime($hora_factura_venta['horafactura_venta'])) . "\n");
            if ($estado_factura[0]['idestado'] == 2) {
                $printer->text("FECHA LIMITE:" . $estado_factura[0]['fechalimitefactura_venta'] . "\n");
            }
            $printer->text("CAJA : 1" . "\n");
            $printer->text("CAJERO: " . $nombre_usuario['nombresusuario_sistema'] . "\n");

            $printer->text("---------------------------------------------" . "\n");
            $printer->text("CLIENTE :" . " " . $nombre_cliente['nombrescliente'] . "\n");
            $printer->text("NIT     :" . " " . number_format($nit_cliente['nitcliente'], 0, ",", ".") . "\n");
            $printer->text("---------------------------------------------" . "\n");
            $printer->text("CODIGO    DESCRIPCION   VALOR UNITARIO    TOTAL" . "\n");
            $printer->text("---------------------------------------------" . "\n");



            $items = model('productoFacturaVentaModel')->getProductosFacturaVentaModel($id_factura);

            foreach ($items as $detalle) {
                $valor_venta = $detalle['total'] / $detalle['cantidadproducto_factura_venta'];
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->text("Cod." . $detalle['codigointernoproducto'] . "      " . $detalle['nombreproducto'] . "\n");
                $printer->text("Cant. " . $detalle['cantidadproducto_factura_venta'] . "     " . "$" . number_format($valor_venta, 0, ',', '.') . "                " . "$" . number_format($detalle['total'], 0, ',', '.') . "\n");
            }



            $id_estado = model('facturaVentaModel')->select('idestado')->where('id', $id_factura)->first();


            $inc = model('kardexModel')->get_inc_pos($id_factura, $id_estado['idestado']);
            $iva = model('kardexModel')->get_iva_pos($id_factura, $id_estado['idestado']);


            $printer->text("---------------------------------------------" . "\n");
            $total = model('productoFacturaVentaModel')->selectSum('total')->where('id_factura', $id_factura)->find();

            $printer->setJustification(Printer::JUSTIFY_RIGHT);

            $impuesto_saludable = model('productoFacturaVentaModel')->get_impuesto_saluidable($id_factura);


            if ($id_regimen['idregimen'] == 1) {

                if ($estado_factura[0]['idestado'] == 1 or $estado_factura[0]['idestado'] == 2 or $estado_factura[0]['idestado'] == 6) {


                    

                    if (!empty($iva[0]['iva'])) {
                        $printer->text("SUB TOTAL :" . "$" . number_format($total[0]['total'] - ($iva[0]['iva']), 0, ",", ".") . "\n");
                        $printer->text("IVA       :" . "$" . number_format($iva[0]['iva'], 0, ",", ".") . "\n");
                    }

                    if (!empty($inc[0]['inc'])) {
                        $printer->text("SUB TOTAL :" . "$" . number_format($total[0]['total'] - ($inc[0]['inc']), 0, ",", ".") . "\n");
                        $printer->text("IMPUESTO AL CONSUMO :" . "$" . number_format($inc[0]['inc'], 0, ",", ".") . "\n");
                    }
                }
            }




            $descuento = model('facturaVentaModel')->select('descuento')->where('id', $id_factura)->first();
            

            $propina = model('facturaVentaModel')->select('propina')->where('id', $id_factura)->first();
            $printer->text(str_pad("PROPINA", 20) . "$" . str_pad(number_format($propina['propina'], 0, ",", "."), 12, " ", STR_PAD_LEFT) . "\n");

        

            $printer->text(str_repeat("-", 32) . "\n");

            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer->text(str_pad("TOTAL", 20) . "$" . str_pad(number_format(
                ($total[0]['total'] - $descuento['descuento']) + $propina['propina'],
                0,
                ",",
                "."
            ), 12, " ", STR_PAD_LEFT) . "\n");
            $printer->selectPrintMode();
            $efectivo = model('facturaFormaPagoModel')->selectSum('valor_pago')->where('id_factura', $id_factura)->find();
            $printer->setTextSize(1, 1);
            $id_forma_pago = model('facturaFormaPagoModel')->id_forma_pago($id_factura);
            $temp_cambio = 0;
            foreach ($id_forma_pago as $forma_pago) {
                $id_forma_pago = model('facturaFormaPagoModel')->id_forma_pago($id_factura);
                $nombre_forma_pago = model('facturaFormaPagoModel')->nombre_forma_pago($forma_pago['idforma_pago']);
                $valor_forma_pago = model('facturaFormaPagoModel')->valor_forma_pago($forma_pago['idforma_pago'], $id_factura);

                if ($valor_forma_pago[0]['valor_pago'] > 0) {
                    $printer->text($nombre_forma_pago[0]['nombreforma_pago'] . ":  $" . number_format($valor_forma_pago[0]['valor_pago'], 0, ",", ".") . "\n");
                }
            }
            if ($estado_factura[0]['idestado'] == 6) {
                $printer->text("Efectivo : 0\n");
                $printer->text("Cambio   : 0\n");
            }
            if ($estado_factura[0]['idestado'] == 1 or $estado_factura[0]['idestado'] == 7) {
                // $printer->text("CAMBIO: " . "$" . number_format($efectivo[0]['valor_pago'] - (($total[0]['total'] - $descuento['descuento']) + $propina['propina']), 0, ",", ".") . "\n");

                $cambio = ($forma_pago == 2)
                    ? 0
                    : $efectivo[0]['valor_pago'] - (($total[0]['total'] - $descuento['descuento']) + $propina['propina']);

                $printer->text("CAMBIO: $" . number_format($cambio, 0, ",", ".") . "\n");

                $printer->text("-----------------------------------------------" . "\n");
            }
            $regimen = model('empresaModel')->select('idregimen')->first();

            if ($regimen['idregimen'] == 1) {
                if ($estado_factura[0]['idestado'] == 1 or $estado_factura[0]['idestado'] == 2 or $estado_factura[0]['idestado'] == 6) {
                   
                    $tarifa_iva = model('kardexModel')->iva_producto($id_factura, $estado_factura[0]['idestado']);



         

                    if (!empty($iva[0]['iva'])) {
                        $printer->setJustification(Printer::JUSTIFY_CENTER);
                        $printer->setTextSize(1, 1);
                        $printer->text("**DISCRIMINACION TARIFAS DE IVA** \n");
                        $printer->setJustification(Printer::JUSTIFY_LEFT);
                        $printer->text("TARIFA    VENTA       BASE/IMP         IVA" . "\n");
                        foreach ($tarifa_iva as $iva) {
                            // $datos_iva = model('productoFacturaVentaModel')->base_iva($iva['valor_iva'], $id_factura);
                            $datos_iva = model('kardexModel')->total_iva_producto_pos($id_factura, $estado_factura[0]['idestado']);
                            if (!empty($datos_iva)) {
                                $printer->text($iva['porcentaje_iva'] . "%" . "       " . "$" . number_format($total[0]['total'], 0, ",", ".") . "   " . "$" . number_format($total[0]['total'] - $datos_iva[0]['iva'], 0, ",", ".") . "    " . "$" . number_format(($datos_iva[0]['iva']), 0, ",", ".") . "\n");
                            }
                        }
                    }

                    $tarifa_ico = model('productoFacturaVentaModel')->tarifa_ico($id_factura);



                    if (!empty($inc[0]['inc'])) {
                        $printer->text("\n");
                        $printer->setJustification(Printer::JUSTIFY_CENTER);
                        $printer->setTextSize(1, 1);
                        $printer->text("**DISCRIMINACION TARIFAS DE INC** \n");
                        $printer->setJustification(Printer::JUSTIFY_LEFT);
                        $printer->text("TARIFA   BASE/IMP        INC     TOTAL" . "\n");


                        foreach ($tarifa_ico as $ico) {
                           


                            $total_compra = model('productoFacturaVentaModel')->total_compra($ico['valor_ico'], $id_factura);

                            $base_ico = model('productoFacturaVentaModel')->base_ico($ico['valor_ico'], $id_factura);

                            if ($total_compra[0]['compra'] >= 100000) {
                                $printer->text($ico['valor_ico'] . "%      " .  "$" . number_format(($total_compra[0]['compra'] - ($base_ico[0]['base']) - $impuesto_saludable[0]['total_impuesto_saludable']), 0, ",", ".") . "        " . "$" . number_format($base_ico[0]['base'], 0, ",", ".") . "       $" . number_format($total_compra[0]['compra'], 0, ",", ".") . "\n");
                            }
                            if ($total_compra[0]['compra'] < 100000) {
                                $printer->text($ico['valor_ico'] . "%" . "       " . "$ " . number_format($total_compra[0]['compra'] - ($base_ico[0]['base']), 0, ",", ".") . "      " . "   $" . number_format($base_ico[0]['base'], 0, ",", ".") . "    $" . number_format($total_compra[0]['compra'], 0, ",", ".") . "\n");
                            }
                        }
                    }


                    
                    $id_registro_dian = model('consecutivosModel')->select('numeroconsecutivo')->Where('idconsecutivos', 6)->first();

                    $prefijo_factura = model('dianModel')->select('inicialestatica')->where('iddian ', $id_registro_dian['numeroconsecutivo'])->first();

                    $id_resolucion_dian = model('consecutivosModel')->select('numeroconsecutivo')->where('conceptoconsecutivo =', 'IdRegistroDian')->first();

                    $factura_prefijo = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', $id_resolucion_dian['numeroconsecutivo'])->first();

                    $fecha_dian = model('dianModel')->select('fechadian')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();
                    $rango_inicial = model('dianModel')->select('rangoinicialdian')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();
                    $rango_final = model('dianModel')->select('rangofinaldian')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();
                    $texto_inicial = model('dianModel')->select('texto_inicial')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();
                    $texto_final = model('dianModel')->select('texto_final')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();

                    $numero_resolucion_dian = model('dianModel')->select('numeroresoluciondian')->where('iddian', $id_resolucion_dian['numeroconsecutivo'])->first();
                    $printer->text("\n");
                    $printer->setJustification(Printer::JUSTIFY_LEFT);
                    $printer->setTextSize(1, 1);
                    
                }
            }

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $fk_usuario_mesero = model('facturaVentaModel')->select('fk_usuario_mesero')->where('id', $id_factura)->first();
            $nombreusuario_sistema = model('usuariosModel')->select('nombresusuario_sistema')->where('idusuario_sistema', $fk_usuario_mesero['fk_usuario_mesero'])->first();
            $printer->text("ATENDIDO POR:" . $nombreusuario_sistema['nombresusuario_sistema'] . "\n");

            $observaciones_genereles = model('facturaVentaModel')->select('observaciones_generales')->where('id', $id_factura)->first();
            $fk_mesa = model('facturaVentaModel')->select('fk_mesa')->where('id', $id_factura)->first();
            $nombre_mesa = model('mesasModel')->select('nombre')->where('id', $fk_mesa['fk_mesa'])->first();
            if (!empty($nombre_mesa['nombre'])) {
                //$printer->text("MESA:" . $nombre_mesa['nombre'] . "\n");
            }
            if (empty($nombre_mesa['nombre'])) {
                $printer->text("MESA: VENTAS DE MOSTRADOR" . "\n");
            }

            if (!empty($observaciones_genereles['observaciones_generales'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 2);
                $printer->text("\n");
                $printer->text("OBSERVACIONES GENERALES\n");
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                $printer->setTextSize(1, 2);
                $printer->text($observaciones_genereles['observaciones_generales'] . "\n");
            }

  

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 1);
          

            $printer->text("-----------------------------------------------" . "\n");
            $printer->text("GRACIAS POR SU VISITA " . "\n");





            $printer->feed(1);
            $printer->cut();
            //$printer->pulse();
            $printer->close();

            $returnData = array(
                "resultado" => 1, //Falta plata 
                "tabla" => view('factura_pos/tabla_reset_factura')
            );
            echo  json_encode($returnData);
        }
    } */

    public function imprimir_factura($id_factura)
    {
        $facturaModel = model('facturaVentaModel');
        $productoModel = model('productoFacturaVentaModel');
        $formaPagoModel = model('facturaFormaPagoModel');
        $kardexModel = model('kardexModel');
        $empresaModel = model('empresaModel');
        $estadoActual = null;

        $numero_factura = $facturaModel
            ->select('numerofactura_venta')
            ->where('id', $id_factura)
            ->first();

        if (empty($numero_factura['numerofactura_venta'])) {
            return;
        }

        $fecha_factura_venta = $facturaModel
            ->select('fecha_factura_venta')
            ->where('id', $id_factura)
            ->first();

        $hora_factura_venta = $facturaModel
            ->select('horafactura_venta')
            ->where('id', $id_factura)
            ->first();

        $id_usuario = $facturaModel
            ->select('idusuario_sistema')
            ->where('id', $id_factura)
            ->first();

        $nombre_usuario = model('usuariosModel')
            ->select('nombresusuario_sistema')
            ->where('idusuario_sistema', $id_usuario['idusuario_sistema'])
            ->first();

        $datos_empresa = $empresaModel->datosEmpresa();

        $id_regimen = $empresaModel
            ->select('idregimen')
            ->first();

        $nit_cliente = $facturaModel
            ->select('nitcliente')
            ->where('id', $id_factura)
            ->first();

        $nombre_cliente = model('clientesModel')
            ->select('nombrescliente')
            ->where('nitcliente', $nit_cliente['nitcliente'])
            ->first();

        $id_impresora = model('cajaModel')
            ->select('id_impresora')
            ->first();

        $nombre_impresora = model('impresorasModel')
            ->select('nombre')
            ->where('id', $id_impresora['id_impresora'])
            ->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        // =====================================================
        // ENCABEZADO DE LA EMPRESA
        // =====================================================
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 2);
        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");

        $printer->setTextSize(1, 1);
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");

        $printer->text(
            $datos_empresa[0]['direccionempresa'] . "  " .
                $datos_empresa[0]['nombreciudad'] . " " .
                $datos_empresa[0]['nombredepartamento'] . "\n"
        );

        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n\n");

        $regimen = model('regimenModel')
            ->select('descripcion')
            ->where('idregimen', $id_regimen['idregimen'])
            ->first();

        // =====================================================
        // INFORMACIÓN DE LA FACTURA
        // =====================================================
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);

        $estado_factura = $facturaModel->estado_factura($id_factura);
        $estadoActual = (int) $estado_factura[0]['idestado'];

        if (in_array($estadoActual, [1, 2], true)) {
            $printer->text(
                "FACTURA DE VENTA: " .
                    $numero_factura['numerofactura_venta'] . "\n"
            );
        }

        if ($estadoActual === 7) {
            $printer->text(
                "REMISION : " .
                    $numero_factura['numerofactura_venta'] . "\n"
            );
        }

        $forma_pago = model('pagosModel')
            ->select('forma_pago')
            ->where('id_factura', $id_factura)
            ->where('id_estado', $estadoActual)
            ->first()['forma_pago'] ?? null;

        $tipoVenta = ($forma_pago == 1) ? 'CONTADO' : 'CRÉDITO';

        $nombre_estado = model('estadoModel')
            ->select('descripcionestado')
            ->where('idestado', $estadoActual)
            ->first()['descripcionestado'];

        if ($estadoActual !== 6) {
            $printer->text("FORMA DE PAGO: {$tipoVenta}\n");
        }

        $printer->text("TIPO DE DOCUMENTO : {$nombre_estado}\n");

        $printer->text(
            "FECHA: " .
                $fecha_factura_venta['fecha_factura_venta'] . "  " .
                date("g:i a", strtotime($hora_factura_venta['horafactura_venta'])) .
                "\n"
        );

        if ($estadoActual === 2) {
            $printer->text(
                "FECHA LIMITE:" .
                    $estado_factura[0]['fechalimitefactura_venta'] . "\n"
            );
        }

        $printer->text("CAJA : 1\n");
        $printer->text("CAJERO: " . $nombre_usuario['nombresusuario_sistema'] . "\n");
        $printer->text(str_repeat("-", 48) . "\n");

        // =====================================================
        // INFORMACIÓN DEL CLIENTE
        // =====================================================
        $printer->text("CLIENTE : " . $nombre_cliente['nombrescliente'] . "\n");
        $printer->text(
            "NIT     : " .
                number_format($nit_cliente['nitcliente'], 0, ",", ".") . "\n"
        );

        $printer->text(str_repeat("-", 48) . "\n");
        $printer->text("CODIGO    DESCRIPCION   VALOR UNITARIO    TOTAL\n");
        $printer->text(str_repeat("-", 48) . "\n");

        // =====================================================
        // DETALLE DE PRODUCTOS
        // =====================================================
        $items = $productoModel->getProductosFacturaVentaModel($id_factura);
        $anchoTicket = 48;

        foreach ($items as $detalle) {
            $cantidad = (float) $detalle['cantidadproducto_factura_venta'];

            $valor_venta = ($cantidad != 0)
                ? $detalle['total'] / $cantidad
                : 0;

            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $codigoProducto = 'Cod. ' . $detalle['codigointernoproducto'] . ' - ';
            $descripcionProducto = trim((string) $detalle['nombreproducto']);

            $lineasDescripcion = wordwrap(
                $codigoProducto . $descripcionProducto,
                $anchoTicket,
                "\n",
                true
            );

            $printer->text($lineasDescripcion . "\n");
            $printer->text(
                'Cant.: ' . $detalle['cantidadproducto_factura_venta'] . "\n"
            );

            $textoUnitario = 'Unit.: $' . number_format($valor_venta, 0, ',', '.');
            $textoTotal = 'Total: $' . number_format($detalle['total'], 0, ',', '.');

            $espacios = max(
                1,
                $anchoTicket - strlen($textoUnitario) - strlen($textoTotal)
            );

            $printer->text(
                $textoUnitario .
                    str_repeat(' ', $espacios) .
                    $textoTotal . "\n"
            );
        }

        // =====================================================
        // CÁLCULO DE IMPUESTOS Y TOTALES
        // =====================================================
        $id_estado = $facturaModel
            ->select('idestado')
            ->where('id', $id_factura)
            ->first();

        $inc = $kardexModel->get_inc_pos($id_factura, $id_estado['idestado']);
        $iva = $kardexModel->get_iva_pos($id_factura, $id_estado['idestado']);

        // Único separador entre los productos y el resumen.
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text(str_repeat("-", 48) . "\n");

        $total = $productoModel
            ->selectSum('total')
            ->where('id_factura', $id_factura)
            ->find();

        $impuesto_saludable = $productoModel->get_impuesto_saluidable($id_factura);

        // =====================================================
        // RESUMEN DE VALORES: 48 COLUMNAS
        // =====================================================
        $anchoLinea = 48;
        $anchoEtiqueta = 27;
        $anchoValor = $anchoLinea - $anchoEtiqueta;

        $descuento = $facturaModel
            ->select('descuento')
            ->where('id', $id_factura)
            ->first();

        $propina = $facturaModel
            ->select('propina')
            ->where('id', $id_factura)
            ->first();

        $valorDescuento = (float) ($descuento['descuento'] ?? 0);
        $valorPropina = (float) ($propina['propina'] ?? 0);
        $valorTotal = (float) ($total[0]['total'] ?? 0);

        // Imprime las etiquetas a la izquierda y los valores a la derecha.
        $imprimirLinea = function ($etiqueta, $valor) use (
            $printer,
            $anchoEtiqueta,
            $anchoValor
        ) {
            $printer->setJustification(Printer::JUSTIFY_LEFT);

            $printer->text(
                str_pad($etiqueta, $anchoEtiqueta, ' ', STR_PAD_RIGHT) .
                    str_pad($valor, $anchoValor, ' ', STR_PAD_LEFT) .
                    "\n"
            );
        };

        // No se agrega otro separador aquí: ya se imprimió después
        // de los productos.

        // -----------------------------------------------------
        // DISCRIMINACIÓN DE IMPUESTOS
        // -----------------------------------------------------
        if ($id_regimen['idregimen'] == 1 && $estadoActual !== 6) {
            $valorIVA = (float) ($iva[0]['iva'] ?? 0);
            $valorINC = (float) ($inc[0]['inc'] ?? 0);

            if ($valorIVA > 0) {
                $subtotal = $valorTotal - $valorIVA - $valorINC;

                $imprimirLinea(
                    'SUB TOTAL:',
                    '$' . number_format($subtotal, 0, ',', '.')
                );

                $imprimirLinea(
                    'IVA:',
                    '$' . number_format($valorIVA, 0, ',', '.')
                );
            }

            if ($valorINC > 0) {
                $imprimirLinea(
                    'IMPUESTO AL CONSUMO:',
                    '$' . number_format($valorINC, 0, ',', '.')
                );
            }
        }

        // -----------------------------------------------------
        // PROPINA
        // -----------------------------------------------------
        if ($estadoActual !== 6) {
            $imprimirLinea(
                'SUB TOTAL:',
                '$' . number_format($valorTotal, 0, ',', '.')
            );
            $imprimirLinea(
                'PROPINA:',
                '$' . number_format($valorPropina, 0, ',', '.')
            );
        }

        // -----------------------------------------------------
        // TOTAL FINAL
        // -----------------------------------------------------
        $totalFinal = ($estadoActual === 6)
            ? $valorTotal
            : ($valorTotal - $valorDescuento + $valorPropina);

        // Única línea separadora inmediatamente antes del total.
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text(str_repeat("-", $anchoLinea) . "\n");

        $printer->selectPrintMode(Printer::MODE_EMPHASIZED);

        $imprimirLinea(
            'TOTAL:',
            '$' . number_format($totalFinal, 0, ',', '.')
        );

        $printer->selectPrintMode();

        // =====================================================
        // CORTESÍA
        // Sin rayas adicionales ni segundo total.
        // =====================================================
        if ($estadoActual === 6) {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer->setTextSize(1, 2);
            $printer->text("*** CORTESÍA ***\n");

            $printer->setTextSize(1, 1);
            $printer->selectPrintMode();
            $printer->text("VENTA SIN COBRO\n\n");
            $printer->text("FIRMA: __________________________________\n\n");

            $printer->setJustification(Printer::JUSTIFY_RIGHT);
        }

        // =====================================================
        // FORMAS DE PAGO
        // =====================================================
        $efectivo = model('facturaFormaPagoModel')
            ->selectSum('valor_pago')
            ->where('id_factura', $id_factura)
            ->find();

        $id_forma_pago = $formaPagoModel->id_forma_pago($id_factura);

        $printer->setTextSize(1, 1);

        if ($estadoActual !== 6) {
            foreach ($id_forma_pago as $forma_pago_item) {
                $nombre_forma_pago = $formaPagoModel->nombre_forma_pago(
                    $forma_pago_item['idforma_pago']
                );

                $valor_forma_pago = $formaPagoModel->valor_forma_pago(
                    $forma_pago_item['idforma_pago'],
                    $id_factura
                );

                if ((float) ($valor_forma_pago[0]['valor_pago'] ?? 0) > 0) {
                    $printer->text(
                        $nombre_forma_pago[0]['nombreforma_pago'] .
                            ":  $" .
                            number_format(
                                $valor_forma_pago[0]['valor_pago'],
                                0,
                                ",",
                                "."
                            ) . "\n"
                    );
                }
            }
        }

        // =====================================================
        // CAMBIO
        // =====================================================
        if ($estadoActual === 1 || $estadoActual === 7) {
            $cambio = ($forma_pago == 2)
                ? 0
                : (
                    (float) ($efectivo[0]['valor_pago'] ?? 0) -
                    (
                        ($valorTotal - $valorDescuento) +
                        $valorPropina
                    )
                );

            $printer->text(
                "CAMBIO: $" . number_format($cambio, 0, ",", ".") . "\n"
            );

            $printer->text(str_repeat("-", 48) . "\n");
        }

        // =====================================================
        // DISCRIMINACIÓN DE IVA, INC Y RESOLUCIÓN DIAN
        // =====================================================
        if (
            $id_regimen['idregimen'] == 1 &&
            in_array($estadoActual, [1, 2], true)
        ) {
            // -------------------------------------------------
            // DISCRIMINACIÓN DE IVA
            // -------------------------------------------------
            $tarifa_iva = $kardexModel->iva_producto(
                $id_factura,
                $estadoActual
            );

            if (!empty($iva[0]['iva'])) {
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 1);
                $printer->text("DISCRIMINACION TARIFAS DE IVA\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $printer->text(
                    sprintf(
                        "%-7s%12s%15s%12s\n",
                        "TARIFA",
                        "BASE/IMP",
                        "IVA",
                        "TOTAL",


                    )
                );

                $datos_iva = $kardexModel->total_iva_producto_pos(
                    $id_factura,
                    $estadoActual
                );

                foreach ($tarifa_iva as $iva_item) {
                    $total_venta_iva = $kardexModel->total_venta_iva(
                        $id_factura,
                        $estadoActual,
                        $iva_item['porcentaje_iva']
                    );

                    $valor_base = "$" . number_format(
                        $total_venta_iva[0]['total_venta'] -
                            $datos_iva[0]['iva'],
                        0,
                        ",",
                        "."
                    );

                    $valor_iva = "$" . number_format(
                        $datos_iva[0]['iva'],
                        0,
                        ",",
                        "."
                    );

                    if (!empty($datos_iva)) {
                        $valor_venta = "$" . number_format(
                            $total_venta_iva[0]['total_venta'],
                            0,
                            ",",
                            "."
                        );





                        $printer->text(
                            sprintf(
                                "%-7s%12s%15s%12s\n",
                                $iva_item['porcentaje_iva'] . "%",
                                $valor_base,
                                $valor_iva,
                                $valor_venta,
                            )
                        );
                    }
                }
            }

            // -------------------------------------------------
            // DISCRIMINACIÓN DE INC
            // -------------------------------------------------
            $tarifa_ico = $productoModel->tarifa_ico($id_factura);

            if (!empty($inc[0]['inc'])) {
                $printer->text("\n");
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 1);
                $printer->text("DISCRIMINACION TARIFAS DE INC\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);

                $printer->text(
                    sprintf(
                        "%-7s%15s%12s%12s\n",
                        "TARIFA",
                        "BASE/IMP",
                        "INC",
                        "TOTAL"
                    )
                );

                foreach ($tarifa_ico as $ico) {
                    $total_compra = $productoModel->total_compra(
                        $ico['valor_ico'],
                        $id_factura
                    );

                    $base_ico = $productoModel->base_ico(
                        $ico['valor_ico'],
                        $id_factura
                    );

                    $totalCompra = (float) ($total_compra[0]['compra'] ?? 0);
                    $baseImpuesto = (float) ($base_ico[0]['base'] ?? 0);

                    $valor_base = $totalCompra - $baseImpuesto;

                    if ($totalCompra >= 100000) {
                        $valor_base -= (float) (
                            $impuesto_saludable[0]['total_impuesto_saludable'] ?? 0
                        );
                    }

                    $printer->text(
                        sprintf(
                            "%-7s%15s%12s%12s\n",
                            $ico['valor_ico'] . "%",
                            "$" . number_format(
                                $valor_base,
                                0,
                                ",",
                                "."
                            ),
                            "$" . number_format(
                                $baseImpuesto,
                                0,
                                ",",
                                "."
                            ),
                            "$" . number_format(
                                $totalCompra,
                                0,
                                ",",
                                "."
                            )
                        )
                    );
                }
            }

            // -------------------------------------------------
            // INFORMACIÓN DE RESOLUCIÓN DIAN
            // -------------------------------------------------
            $id_registro_dian = model('consecutivosModel')
                ->select('numeroconsecutivo')
                ->where('idconsecutivos', 6)
                ->first();

            $prefijo_factura = model('dianModel')
                ->select('inicialestatica')
                ->where('iddian ', $id_registro_dian['numeroconsecutivo'])
                ->first();

            $id_resolucion_dian = model('consecutivosModel')
                ->select('numeroconsecutivo')
                ->where('conceptoconsecutivo =', 'IdRegistroDian')
                ->first();

            $factura_prefijo = model('consecutivosModel')
                ->select('numeroconsecutivo')
                ->where(
                    'idconsecutivos',
                    $id_resolucion_dian['numeroconsecutivo']
                )
                ->first();

            $fecha_dian = model('dianModel')
                ->select('fechadian')
                ->where('iddian', $id_resolucion_dian['numeroconsecutivo'])
                ->first();

            $rango_inicial = model('dianModel')
                ->select('rangoinicialdian')
                ->where('iddian', $id_resolucion_dian['numeroconsecutivo'])
                ->first();

            $rango_final = model('dianModel')
                ->select('rangofinaldian')
                ->where('iddian', $id_resolucion_dian['numeroconsecutivo'])
                ->first();

            $texto_inicial = model('dianModel')
                ->select('texto_inicial')
                ->where('iddian', $id_resolucion_dian['numeroconsecutivo'])
                ->first();

            $texto_final = model('dianModel')
                ->select('texto_final')
                ->where('iddian', $id_resolucion_dian['numeroconsecutivo'])
                ->first();

            $numero_resolucion_dian = model('dianModel')
                ->select('numeroresoluciondian')
                ->where('iddian', $id_resolucion_dian['numeroconsecutivo'])
                ->first();

            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
        }

        // =====================================================
        // MESERO, MESA Y OBSERVACIONES
        // =====================================================
        $fk_usuario_mesero = $facturaModel
            ->select('fk_usuario_mesero')
            ->where('id', $id_factura)
            ->first();

        $nombreusuario_sistema = model('usuariosModel')
            ->select('nombresusuario_sistema')
            ->where(
                'idusuario_sistema',
                $fk_usuario_mesero['fk_usuario_mesero']
            )
            ->first();

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);

        $printer->text(
            "ATENDIDO POR:" .
                ($nombreusuario_sistema['nombresusuario_sistema'] ?? '') . "\n"
        );

        $observaciones_genereles = $facturaModel
            ->select('observaciones_generales')
            ->where('id', $id_factura)
            ->first();

        $fk_mesa = $facturaModel
            ->select('fk_mesa')
            ->where('id', $id_factura)
            ->first();

        $nombre_mesa = model('mesasModel')
            ->select('nombre')
            ->where('id', $fk_mesa['fk_mesa'])
            ->first();

        if (empty($nombre_mesa['nombre'])) {
            $printer->text("MESA: VENTAS DE MOSTRADOR\n");
        }

        if (!empty($observaciones_genereles['observaciones_generales'])) {
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(1, 2);
            $printer->text("\nOBSERVACIONES GENERALES\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->setTextSize(1, 1);
            $printer->text(
                $observaciones_genereles['observaciones_generales'] . "\n"
            );
        }

        // =====================================================
        // PIE DEL TICKET
        // =====================================================
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text(str_repeat("-", 48) . "\n");
        $printer->text("GRACIAS POR SU VISITA\n");

        $printer->feed(1);
        $printer->cut();
        $printer->close();

        // =====================================================
        // RESPUESTA
        // =====================================================
        $returnData = [
            "resultado" => 1,
            "tabla" => view('factura_pos/tabla_reset_factura')
        ];

        echo json_encode($returnData);
    }






    public function imprimir_comprobnate_transferencia($id_factura)
    {

        //try {

        // Impresora
        $id_impresora = model('cajaModel')->select('id_impresora')->first();

        if (!$id_impresora) {
            throw new \Exception("No se encontró la configuración de la caja.");
        }

        $impresora = model('impresorasModel')
            ->select('nombre')
            ->where('id', $id_impresora['id_impresora'])
            ->first();

        if (!$impresora) {
            throw new \Exception("No se encontró la impresora.");
        }

        $connector = new WindowsPrintConnector($impresora['nombre']);
        $printer   = new Printer($connector);

        // Factura
        $factura = model('facturaVentaModel')
            ->where('id', $id_factura)
            ->first();

        if (!$factura) {
            throw new \Exception("No existe la factura.");
        }

        // Usuario
        $usuario = model('usuariosModel')
            ->select('nombresusuario_sistema')
            ->where('idusuario_sistema', $factura['idusuario_sistema'])
            ->first();

        // Empresa
        $empresa = model('empresaModel')->datosEmpresa();

        // Formas de pago
        $movimientos_transaccion = model('facturaformaPagoModel')->valor_pago_transaccion($id_factura);
        $movimientos_efectivo    = model('facturaformaPagoModel')->valor_pago_efectivo($id_factura);

        // Total factura
        $total = model('productoFacturaVentaModel')
            ->selectSum('total')
            ->where('id_factura', $id_factura)
            ->first();

        $valorTotal = $total['total'] ?? 0;

        $efectivo = $movimientos_efectivo[0]['valor_pago'] ?? 0;
        $transferencia = $movimientos_transaccion[0]['valor_pago'] ?? 0;

        $cambio = ($efectivo + $transferencia) - $valorTotal;

        // ================= IMPRESIÓN ===================

        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $printer->text("SOPORTE TRANSACCIÓN\n");
        $printer->text($empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text("NIT: " . $empresa[0]['nitempresa'] . "\n");
        $printer->text("----------------------------------------\n");

        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $printer->text("FACTURA:        " . $factura['numerofactura_venta'] . "\n");
        $printer->text("FECHA:          " . $factura['fecha_factura_venta'] . " " . $factura['horafactura_venta'] . "\n");
        $printer->text("CAJA:           1\n");
        $printer->text("USUARIO:        " . ($usuario['nombresusuario_sistema'] ?? '') . "\n");

        $printer->text("----------------------------------------\n");

        $printer->text("VALOR FACTURA:  $" . number_format($valorTotal, 0, ",", ".") . "\n");
        $printer->text("EFECTIVO:       $" . number_format($efectivo, 0, ",", ".") . "\n");
        $printer->text("TRANSFERENCIA:  $" . number_format($transferencia, 0, ",", ".") . "\n");
        $printer->text("CAMBIO:         $" . number_format($cambio, 0, ",", ".") . "\n");

        $printer->text("\n");
        $printer->text("Nota:\n");
        $printer->text("____________________________________\n\n");
        $printer->text("Nombre: ____________________________\n\n");
        $printer->text("Identificación: ____________________\n\n");
        $printer->text("Teléfono: __________________________\n\n");

        $printer->feed(3);
        $printer->cut();
        //$printer->pulse(); // Descomentar si tu impresora tiene cajón monedero
        $printer->close();
    }




    public function imprimirComprobnateTransferencia($id_factura, $transferencia, $efectivo, $total)
    {

        $id_impresora = model('cajaModel')->select('id_impresora')->first();
        $impresora = model('impresorasModel')
            ->select('nombre')
            ->where('id', $id_impresora['id_impresora'])
            ->first();

        $connector = new WindowsPrintConnector($impresora['nombre']);
        $printer = new Printer($connector);

        $empresa = model('empresaModel')->datosEmpresa();

        // Una sola consulta para la factura
        $factura = model('pagosModel')
            ->select('documento, fecha, hora, total_documento')
            ->where('id', $id_factura)
            ->first();



        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);
        $printer->text("SOPORTE DE TRANSFERENCIA\n");
        $printer->text($empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text("NIT: " . $empresa[0]['nitempresa'] . "\n\n");


        $printer->setJustification(Printer::JUSTIFY_LEFT);

        $printer->text("FACTURA DE VENTA : " . $factura['documento'] . "\n");
        $printer->text("FECHA   : " . $factura['fecha'] . " " . $factura['hora'] . "\n");
        $printer->text("CAJA   :  1" . "\n");
        $printer->text("Valor factura" . "\n\n");



        $printer->text(
            str_pad("Efectivo:", 22) .
                str_pad("$" . number_format($efectivo, 0, ",", "."), 18, " ", STR_PAD_LEFT) . "\n"
        );

        $printer->text(
            str_pad("Transaccion:", 22) .
                str_pad("$" . number_format($transferencia, 0, ",", "."), 18, " ", STR_PAD_LEFT) . "\n"
        );
        $printer->text(
            str_pad("Cambio:", 22) .
                str_pad("$" . number_format(0, 0, ",", "."), 18, " ", STR_PAD_LEFT) . "\n\n"
        );


        $printer->text("Nota           : ___________________________\n\n");
        $printer->text("Nombre         : ___________________________\n\n");
        $printer->text("Identificación : ___________________________\n\n");
        $printer->text("Teléfono       : ___________________________\n\n");

        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();
    }





    function imprimir_comanda() {}


    function imp_reporte_ventas($id_apertura)
    {

        $limpiar = model('reporteProductoModel')->truncate();
        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();
        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        // FUENTE MAS COMPACTA
        //$printer->selectPrintMode(Printer::MODE_FONT_B);
        $printer->selectPrintMode();

        // ENCABEZADO EMPRESA
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setTextSize(1, 1);

        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT: " . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . " - " . $datos_empresa[0]['nombreciudad'] . " (" . $datos_empresa[0]['nombredepartamento'] . ")\n");
        $printer->text("TEL: " . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text($datos_empresa[0]['nombreciudad'] . " - " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("\n");
        // TITULO
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("** REPORTE DE VENTAS **\n");

        // FECHAS
        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hora_apertura  = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();

        $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
        $hora_cierre  = model('cierreModel')->select('hora')->where('idapertura', $id_apertura)->first();

        if (!empty($fecha_apertura['fecha'])) {
            $printer->text("APERTURA: " . $fecha_apertura['fecha'] . " " . $hora_apertura['hora'] . "\n");
        }

        if (!empty($fecha_cierre['fecha'])) {
            $printer->text("CIERRE: " . $fecha_cierre['fecha'] . " " . $hora_cierre['hora'] . "\n");
        }

        $printer->text("IMP: " . date('Y-m-d H:i') . "\n");

        // PRODUCTOS
        $productos_distinct = model('kardexModel')->get_productos($id_apertura);
        $categorias = model('kardexModel')->get_categorias($id_apertura);

        foreach ($productos_distinct as $detalle) {

            $total = model('kardexModel')->get_total($id_apertura, $detalle['valor_unitario'], $detalle['codigo']);

            $nombre_producto = model('productoModel')
                ->select('nombreproducto')
                ->where('codigointernoproducto', $detalle['codigo'])
                ->first();

            $cantidad = $total[0]['cantidad'];

            // Creamos una línea para que se escriba la cantidad física
            $linea_fisica = str_repeat('_', 10); // 10 guiones como ejemplo

            $data = [
                'cantidad' => $cantidad,
                'nombre_producto' => $nombre_producto['nombreproducto'],
                'precio_venta' => $detalle['valor_unitario'],
                'valor_total' => $detalle['valor_unitario'] * $cantidad,
                'id_categoria' => $detalle['id_categoria'],
                'codigo_interno_producto' => $detalle['codigo'],
                'valor_unitario' => $detalle['valor_unitario'],
                'cantidad_fisica' => $linea_fisica  // <-- aquí agregamos la línea
            ];

            model('reporteProductoModel')->insert($data);
        }

        $anchoCategoria = 45;

        foreach ($categorias as $detalle) {

            $nombre_categoria = model('categoriasModel')
                ->select('nombrecategoria')
                ->where('codigocategoria', $detalle['id_categoria'])
                ->first();

            $categoria = strtoupper($nombre_categoria['nombrecategoria'] ?? '');

            // TITULO CATEGORIA
            $printer->text("\n" . $categoria . "\n");

            // HEADER con columna de CONTEO
            $header =
                str_pad("C", 3) .           // Columna de conteo
                str_pad("PRODUCTO", 20) .   // Nombre del producto
                str_pad("V/U", 8, " ", STR_PAD_LEFT) .
                str_pad("TOTAL", 10, " ", STR_PAD_LEFT) .
                str_pad("CONTEO", 7, " ", STR_PAD_LEFT);  // Última columna

            $printer->text($header . "\n");

            $productos = model('reporteProductoModel')
                ->where('id_categoria', $detalle['id_categoria'])
                ->orderBy('codigo_interno_producto', 'asc')
                ->findAll();

            foreach ($productos as $valor) {

                $ancho_cant = 3;       // columna de conteo
                $ancho_nombre = 20;    // nombre del producto
                $ancho_unitario = 8;   // valor unitario
                $ancho_total = 10;     // total
                $ancho_raya = 7;       // ancho de la rayita para cantidad física

                // Valor real que contaron manualmente (puede estar vacío)
                $cantidad = str_pad($valor['cantidad'], $ancho_cant, " ", STR_PAD_RIGHT);

                // Formatear valores
                $valor_unitario = number_format($valor['valor_unitario'], 0, ",", ".");
                $valor_unitario = str_pad($valor_unitario, $ancho_unitario, " ", STR_PAD_LEFT);

                $valor_total = number_format($valor['valor_total'], 0, ",", ".");
                $valor_total = str_pad($valor_total, $ancho_total, " ", STR_PAD_LEFT);

                // Nombre del producto, cortado en líneas si es largo
                $nombre_producto = strtoupper(trim($valor['nombre_producto']));
                $lineas_nombre = explode("\n", wordwrap($nombre_producto, $ancho_nombre, "\n", true));

                // Recorremos todas las líneas del nombre
                foreach ($lineas_nombre as $i => $linea) {

                    $nombre = str_pad($linea, $ancho_nombre, " ", STR_PAD_RIGHT);

                    // Solo en la primera línea agregamos cantidad, valores y rayita
                    if ($i === 0) {
                        $raya = str_repeat("_", $ancho_raya);
                        $printer->text(
                            $cantidad .
                                $nombre .
                                $valor_unitario .
                                $valor_total .
                                $raya .
                                "\n"
                        );
                    } else {
                        // Líneas extra para nombres largos (solo texto, sin cantidad ni valores)
                        $printer->text(
                            str_repeat(" ", $ancho_cant) .   // espacio para conteo
                                $nombre .
                                str_repeat(" ", $ancho_unitario + $ancho_total + $ancho_raya) .  // espacio para valores + rayita
                                "\n"
                        );
                    }
                }
            }

            $total_categoria = model('kardexModel')->get_total_categoria($detalle['id_categoria']);
            $total_cat_valor = number_format($total_categoria[0]['total'] ?? 0, 0, ",", ".");

            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("TOTAL " . $categoria . ": " . $total_cat_valor . "\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text(str_repeat("-", $ancho_cant + $ancho_nombre + $ancho_unitario + $ancho_total) . "\n");
        }

        // TOTAL GENERAL
        $total = model('kardexModel')->selectSum('total')->where('id_apertura', $id_apertura)->first();

        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->setTextSize(1, 2);
        $printer->text("TOTAL: $" . number_format($total['total'], 0, ",", ".") . "\n");

        // CORTE
        $printer->setTextSize(1, 1);
        $printer->cut();
        $printer->close();

        echo json_encode(["resultado" => 1]);
    }


    function imp_reporte_ventasSinCantidades($id_apertura)
    {

        $id_impresora = model('impresionFacturaModel')->select('id_impresora')->first();
        $datos_empresa = model('empresaModel')->datosEmpresa();

        $nombre_impresora = model('impresorasModel')->select('nombre')->where('id', $id_impresora['id_impresora'])->first();

        $connector = new WindowsPrintConnector($nombre_impresora['nombre']);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setFont(Printer::FONT_A);
        $printer->setTextSize(1, 1);

        $printer->text($datos_empresa[0]['nombrecomercialempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombrejuridicoempresa'] . "\n");
        $printer->text("NIT :" . $datos_empresa[0]['nitempresa'] . "\n");
        $printer->text($datos_empresa[0]['direccionempresa'] . "  " . $datos_empresa[0]['nombreciudad'] . " " . $datos_empresa[0]['nombredepartamento'] . "\n");
        $printer->text("TELEFONO:" . $datos_empresa[0]['telefonoempresa'] . "\n");
        $printer->text($datos_empresa[0]['nombreregimen'] . "\n");
        $printer->text("\n");



        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("**REPORTE DE VENTAS** \n\n");

        $fecha_apertura = model('aperturaModel')->select('fecha')->where('id', $id_apertura)->first();
        $hora_apertura = model('aperturaModel')->select('hora')->where('id', $id_apertura)->first();
        $fecha_cierre = model('cierreModel')->select('fecha')->where('idapertura', $id_apertura)->first();
        $hora_cierre = model('cierreModel')->select('hora')->where('idapertura', $id_apertura)->first();
        //$printer->text("Fecha de apertura:     " . $fecha_apertura['fecha'] . "   " . $hora_apertura['hora'] . "\n");

        if (!empty($fecha_cierre['fecha'])) {
            $printer->text("Fecha de cierre:       " .     $fecha_cierre['fecha'] . "   " . $hora_cierre['hora'] . "\n");
        }

        $printer->text("Fecha de impresion:    " . date('Y-m-d') . "\n");



        $categorias = model('kardexModel')->temp_categoria($id_apertura);


        $printer->setJustification(Printer::JUSTIFY_LEFT);



        foreach ($categorias as $detalle) {
            $nombre_categoria = model('categoriasModel')->select('nombrecategoria')->where('codigocategoria', $detalle['id_categoria'])->first();
            $categoria = $nombre_categoria['nombrecategoria'];
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("------------------------------------\n");
            $printer->text("CATEGORIA: " . $categoria . "\n");
            $printer->text("------------------------------------\n\n");
            $productos = model('kardexModel')->temp_categoria_productos($detalle['id_categoria'], $id_apertura);

            foreach ($productos as $valor) {
                $printer->setJustification(Printer::JUSTIFY_LEFT);
                // Alinea la cantidad a la derecha con una longitud fija de 10 caracteres
                $cantidad_alineada = str_pad($valor['cantidad'], 7, ' ', STR_PAD_LEFT);
                $printer->text($cantidad_alineada . " ____ " . $valor['nombreproducto'] .   "\n");
                $valor_total = model('kardexModel')->valor_producto($valor['codigo'], $id_apertura);
            }
            $valor_total_categoria = model('kardexModel')->valor_total_categoria($detalle['id_categoria'], $id_apertura);
            $printer->text("\n");
        }

        $total = model('kardexModel')->selectSum('total')->where('id_apertura', $id_apertura)->findAll();
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->setTextSize(1, 2);


        $printer->text("\n");

        $printer->feed(1);
        $printer->cut();

        $printer->close();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }
}
