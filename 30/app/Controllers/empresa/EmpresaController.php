<?php

namespace App\Controllers\empresa;

use App\Controllers\BaseController;
use App\Libraries\Inventario;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class EmpresaController extends BaseController
{
    public function datos()
    {
        $regimen = model('regimenModel')->orderBy('idregimen', 'asc')->findAll();
        $departamentos = model('departamentoModel')->select('*')->where('idpais', 49)->findAll();
        $datos_empresa = model('empresaModel')->findAll();
        $municipios = model('ciudadModel')->where('iddepartamento', $datos_empresa[0]['iddepartamento'])->findAll();
        $tipo_empresa = model('tipoEmpresaModel')->findAll();
        $NombreRegimen = model('regimenModel')->regimen();

        $actividadEconimica = model('empresaCiiuModel')->select('codigo')->first();

        return view('empresa/datos', [
            'regimen' => $regimen,
            'departamentos' => $departamentos,
            'datos_empresa' => $datos_empresa,
            'municipios' => $municipios,
            'tipo_empresa' => $tipo_empresa,
            'descriprionRegimen' => $NombreRegimen[0]['descripcion'],
            'actividadEconomica' => $actividadEconimica['codigo']
        ]);
    }


    function actualizar_datos()
    {

        $data = [
            'nitempresa' => $this->request->getPost('nit_empresa'),
            'idregimen' => $this->request->getPost('id_regimen'),
            //'nombrecomercialempresa' => $this->request->getPost('razon_social'),
            'nombrecomercialempresa' => $this->request->getPost('nombre_comercial'),
            'nombrejuridicoempresa' => $this->request->getPost('razonSocial'),
            //'nombrejuridicoempresa' => $this->request->getPost('razon_social'),
            'telefonoempresa' => $this->request->getPost('telefono'),
            'celularempresa' => '0',
            'faxempresa' => '0',
            'emailempresa' => $this->request->getPost('email'),
            'pagwebempresa' => '0',
            'representantelegalempresa' => $this->request->getPost('representante_legal'),
            'iddepartamento' => $this->request->getPost('departamento'),
            'idciudad' => $this->request->getPost('municipio'),
            'direccionempresa' => $this->request->getPost('direccion'),
            'dv' => $this->request->getPost('digito_verificacion'),
            'estadoempresa' => 'true',
            'descripcion' => '0',
            'recauda_iva' => 'false',
            'fk_tipo_empresa' => $this->request->getPost('tipo_empresa')

        ];

        $empresa = model('empresaModel');
        $datos_empresa = $empresa->set($data);
        $datos_empresa = $empresa->update();



        /*  $updateRegimen = model('regimenModel')
            ->set('descripcion', $this->request->getPost('descripcionRegimen'))
            ->where('idregimen', $this->request->getPost('id_regimen'))
            ->update(); */
        $updateActividad = model('empresaCiiuModel')->set('codigo', $this->request->getPost('actividadEconomica'))->update();


        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('empresa/datos'))->with('mensaje', 'Datos almacenados éxitosamente');
    }

    function resolucion_facturacion()
    {
        $id_dian = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 6)->first();
        $resoluciones_dian = model('resolucionDianModel')->findAll();
        return view('empresa/resolucion_facturacion', [
            'resoluciones_dian' => $resoluciones_dian,
            'id_dian' => $id_dian['numeroconsecutivo']
        ]);
    }
    function resolucion_electronica()
    {

        $resoluciones_dian = model('resolElectronicaModel')->orderBy('id', 'desc')->findAll();



        return view('empresa/resolucion_facturacion_electronica', [
            'resoluciones_dian' => $resoluciones_dian,

        ]);
    }


    function consecutivos()
    {
        $consecutivos = model('consecutivosModel')->orderBy('idconsecutivos ', 'asc')->findAll();
        return view('consecutivos/listado', [
            'consecutivos' => $consecutivos
        ]);
    }


    function guardar_resolucion_facturacion()
    {
        if (!$this->validate([
            'numero_dian' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Documento ya existe'

                ]
            ],
            'fecha_dian' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'prefijo_dian' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Usuario ya existe'
                ]
            ],
            'numero_inicial' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'numero_final' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'vigencia' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'numero_final' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'texto_inicial' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'tipo_de_solicitud' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'alerta' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],

        ])) {
            $errors = $this->validator->getErrors();
            echo json_encode([
                'code' => 0,
                'error' => $errors
            ]);
        } else {
            $data = [
                'numeroresoluciondian' => $this->request->getPost('numero_dian'),
                'fechadian' => $this->request->getPost('fecha_dian'),
                'rangoinicialdian' => $this->request->getPost('numero_inicial'),
                'rangofinaldian' => $this->request->getPost('numero_final'),
                'texto_inicial' => $this->request->getPost('texto_inicial'),
                'inicialestatica' => $this->request->getPost('prefijo_dian'),
                'finalestatica' => $this->request->getPost('prefijo_dian'),
                'texto_final' => $this->request->getPost('tipo_de_solicitud'),
                'id_modalidad' => $this->request->getPost('modalidad_dian'),
                'vigencia' => $this->request->getPost('fecha_caducidad'),
                'id_caja' => 1,
                'vigencia_mes' => $this->request->getPost('vigencia'),
                'alerta_facturacion' => $this->request->getPost('alerta')
            ];
            /* $data = [
                'numeroresoluciondian' => $this->request->getPost('numero_dian'),
                'fechadian' => $this->request->getPost('fecha_dian'),
                'rangoinicialdian' => $this->request->getPost('numero_inicial'),
                'rangofinaldian' => $this->request->getPost('numero_final'),
                'texto_inicial' => $this->request->getPost('texto_inicial'),
                'inicialestatica' => $this->request->getPost('prefijo_dian'),
                'finalestatica' => $this->request->getPost('prefijo_dian'),
                'texto_final' => $this->request->getPost('texto_final'),
                'id_modalidad' => $this->request->getPost('modalidad_dian'),
                'vigencia' => $this->request->getPost('fecha_caducidad'),
                'id_caja' => 1,
                'vigencia_mes' => $this->request->getPost('vigencia'),
                'alerta_facturacion' => $this->request->getPost('alerta')
            ]; */


            $insert = model('dianModel')->insert($data);


            if ($insert) {

                $data = [
                    'numeroconsecutivo' => $this->request->getPost('prefijo_dian'),
                ];

                $model = model('consecutivosModel');
                $consecutivo = $model->set($data);
                $consecutivo = $model->where('idconsecutivos', 7);
                $consecutivo = $model->update();

                $resoluciones_dian = model('resolucionDianModel')->findAll();
                $id_dian = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 6)->first();

                echo json_encode([
                    'code' => 1,
                    'resoluciones' => view('empresa/tbody_resolucion_facturacion', [
                        'resoluciones_dian' => $resoluciones_dian,
                        'id_dian' => $id_dian['numeroconsecutivo']
                    ]),
                ]);
            } else {
                echo json_encode(['code' => 0, 'msg' => 'No se pudo crear ']);
            }
        }
    }

    function actualizar_consecutivos()
    {

        $data = [
            'numeroconsecutivo' => $this->request->getPost('valor_consecutivo'),
        ];

        $model = model('consecutivosModel');
        $consecutivo = $model->set($data);
        $consecutivo = $model->where('idconsecutivos', $this->request->getPost('id_consecutivos'));
        $consecutivo = $model->update();

        if ($consecutivo) {
            $returnData = array(
                "resultado" => 0, //No hay pedido 
            );
            echo  json_encode($returnData);
        }
    }


    function municipios()
    {

        $id_departamento = $this->request->getPost('id_departamento');


        $returnData = array(
            "resultado" => 1, //No hay pedido 
            "municipios" => view('municipios/municipios_empresa', [
                'municipios' => model('ciudadModel')->select('*')->where('iddepartamento', $id_departamento)->findAll()
            ])
        );
        echo  json_encode($returnData);
    }

    function actualizar_nombre_cuenta()
    {


        $data = [
            'nombre_cuenta' => $this->request->getPost('nombre_cuenta'),
        ];

        $model = model('cuentaRetiroModel');
        $consecutivo = $model->set($data);
        $consecutivo = $model->where('id', $this->request->getPost('id_cuenta'));
        $consecutivo = $model->update();

        $returnData = array(
            "resultado" => 1
        );
        echo  json_encode($returnData);
    }

    function datos_resolucion_facturacion()
    {
        $datos_resolucion = model('dianModel')->select('*')->where('iddian', $_POST['id_resolucion'])->first();

        $returnData = array(
            "resultado" => 1,
            'resolucion' => view('empresa/formulario_editar_resolucion', [
                'datos_resolucion' => $datos_resolucion,
                'id_resolucion' => $_POST['id_resolucion']
            ])
        );
        echo  json_encode($returnData);
    }

    function actualizar_resolucion_facturacion()
    {
        if (!$this->validate([
            'numero_dian' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'fecha_dian' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'numero_inicial' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'numero_final' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'vigencia' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'numero_final' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'texto_inicial' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'tipo_de_solicitud' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'alerta' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],

        ])) {
            $errors = $this->validator->getErrors();
            echo json_encode([
                'code' => 0,
                'error' => $errors
            ]);
        } else {
            $data = [
                'numeroresoluciondian' => $this->request->getPost('numero_dian'),
                'fechadian' => $this->request->getPost('fecha_dian'),
                'rangoinicialdian' => $this->request->getPost('numero_inicial'),
                'rangofinaldian' => $this->request->getPost('numero_final'),
                'inicialestatica' => $this->request->getPost('prefijo_dian'),
                'finalestatica' => $this->request->getPost('prefijo_dian'),
                'texto_inicial' => $this->request->getPost('texto_inicial'),
                'texto_final' => $this->request->getPost('tipo_de_solicitud'),
                'id_modalidad' => $this->request->getPost('modalidad_dian'),
                'vigencia' => $this->request->getPost('fecha_caducidad'),
                'id_caja' => 1,
                'vigencia_mes' => $this->request->getPost('vigencia'),
                'alerta_facturacion' => $this->request->getPost('alerta')
            ];

            $model = model('dianModel');
            $dian = $model->set($data);
            $dian = $model->where('iddian', $_POST['id_resolucion']);
            $dian = $model->update();

            $model = model('consecutivosModel');
            $consecutivo = $model->set('numeroconsecutivo', $this->request->getPost('prefijo_dian'));
            $consecutivo = $model->where('idconsecutivos', 7);
            $consecutivo = $model->update();

            if ($dian) {
                $resoluciones_dian = model('resolucionDianModel')->findAll();

                echo json_encode([
                    'code' => 1,
                    'resoluciones' => view('empresa/tbody_resolucion_facturacion', [
                        'resoluciones_dian' => $resoluciones_dian,
                        'id_dian' => $_POST['id_resolucion']
                    ])

                ]);
            }
        }
    }
    function activacion_resolucion_facturacion()
    {
        $id_dian = $this->request->getPost('id_resolucion');
        //$id_dian = 7;

        $datos_resolucion = model('dianModel')->where('iddian', $id_dian)->first();



        $ultima_id = model('facturaVentaModel')->selectMax('id')->first();



        if (!empty($ultima_id['id'])) {
            $numero_factura = model('facturaVentaModel')->select('numerofactura_venta')->where('id', $ultima_id['id'])->first();
            $factura = $numero_factura['numerofactura_venta'];
        }
        if (empty($ultima_id['id'])) {
            $numero_factura = model('consecutivosModel')->select('numeroconsecutivo')->where('idconsecutivos', 8)->first();
            $factura = $numero_factura['numeroconsecutivo'];
        }


        echo json_encode([
            'texto' => $datos_resolucion['texto_inicial'] . "-" . $datos_resolucion['inicialestatica'] . " desde " . $datos_resolucion['inicialestatica'] . " hasta " . $datos_resolucion['rangofinaldian'] . " fecha " . $datos_resolucion['fechadian'] . " vigencia 6 meses ",
            'resultado' => 1,
            'numero_factura' => $factura,
            'prefijo' => $datos_resolucion['inicialestatica'],
            'id_dian' => $id_dian
        ]);
    }

    function activar_resolucion_dian()
    {
        $id_dian = $this->request->getPost('id_dian_guardar');
        $numeracion_factura = $this->request->getPost('continuacion_factura');

        $id_dian = [
            'numeroconsecutivo' => $id_dian
        ];
        $model = model('consecutivosModel');
        $actualizar_cantidad = $model->set($id_dian);
        $actualizar_cantidad = $model->where('idconsecutivos', 6);
        $actualizar_cantidad = $model->update();

        $consecutivos = [
            'numeroconsecutivo' => $numeracion_factura
        ];

        $model = model('consecutivosModel');
        $actualizar_cantidad = $model->set($consecutivos);
        $actualizar_cantidad = $model->where('idconsecutivos', 8);
        $actualizar_cantidad = $model->update();

        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('empresa/resolucion_facturacion'))->with('mensaje', 'Activacion de resolucion exitosa ');
    }

    function comprobante_transaccion()
    {
        $comprobante = model('cajaModel')->select('imp_comprobante_transferencia')->first();


        return view('empresa/configuracion_impresion_transferencias', [
            'imprimir' => $comprobante['imp_comprobante_transferencia']
        ]);
    }

    function configuracion_impresion()
    {
        $impresion = $this->request->getVar('impresion');

        $data = [
            'imp_comprobante_transferencia' => $impresion

        ];

        $model = model('cajaModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('numerocaja', 1);
        $actualizar = $model->update();

        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');
        return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', 'Configuración exitosa');
    }

    function agregar_resolucion_electronica()
    {
        $data = [
            'numero' => $this->request->getPost('numero'),
            'date_begin' => $this->request->getPost('fecha_inicial'),
            'vigency' => $this->request->getPost('vigencia'),
            'date_end' => $this->request->getPost('fecha_final'),
            'prefijo' => $this->request->getPost('prefijo'),
            'number_begin' => $this->request->getPost('numero_inicial'),
            'number_end' => $this->request->getPost('numero_final'),
            'consecutive' => $this->request->getPost('consecutivo'),
            'flexible' => 'true',
            'alerta' => $this->request->getPost('alerta')

        ];

        $insert = model('resolElectronicaModel')->insert($data);

        if ($insert) {

            $resoluciones_dian = model('resolElectronicaModel')->findAll();

            $returnData = array(
                "resultado" => 1, //Falta plata  
                "resoluciones" => view('empresa/resoluciones_electronicas', [
                    'resoluciones_dian' => $resoluciones_dian
                ])
            );
            echo  json_encode($returnData);
        }
    }

    function editar_resolucion_electronica()
    {
        $id_resolucion = $this->request->getPost('id_resolucion');


        $resolucion = model('resolElectronicaModel')->where('id', $id_resolucion)->first();


        $returnData = array(
            "resultado" => 1, //Falta plata  
            "id_resolucion" => $resolucion['id'],
            "numero" => $resolucion['numero'],
            "fecha_inicial" => $resolucion['date_begin'],
            "fecha_final" => $resolucion['date_end'],
            "vigencia" => $resolucion['vigency'],
            "vigencia" => $resolucion['vigency'],
            "prefijo" => $resolucion['prefijo'],
            "prefijo" => $resolucion['prefijo'],
            "inicial" => $resolucion['number_begin'],
            "final" => $resolucion['number_end'],
            "consecutivo" => $resolucion['consecutive'],
            "alerta" => $resolucion['alerta'],
        );
        echo  json_encode($returnData);
    }


    function actualizar_resolucion_electronica()
    {
        $data = [
            'numero' => $this->request->getPost('numero'),
            'date_begin' => $this->request->getPost('fecha_inicial'),
            'vigency' => $this->request->getPost('vigencia'),
            'date_end' => $this->request->getPost('fecha_final'),
            'prefijo' => $this->request->getPost('prefijo'),
            'number_begin' => $this->request->getPost('numero_inicial'),
            'number_end' => $this->request->getPost('numero_final'),
            'consecutive' => $this->request->getPost('consecutivo'),
            'alerta' => $this->request->getPost('alerta')

        ];


        $model = model('resolElectronicaModel');
        $act_reso = $model->set($data);
        $act_reso = $model->where('id', $this->request->getPost('id'));
        $act_reso = $model->update();
        $resoluciones_dian = model('resolElectronicaModel')->findAll();
        if ($act_reso) {
            $returnData = array(
                "resultado" => 1,

            );
            echo  json_encode($returnData);
        }
    }

    function validarResolucion()
    {

        //$datosResolucion = model('resolucionElectronicaModel')->select('date_end,number_end,alerta')->

        $json = $this->request->getJSON();
        $documento = $json->documento;

        if ($documento != 6) {
            $hoy = date('Y-m-d');



            $datosResolucion = model('resolucionElectronicaModel')
                ->select('date_end, number_end, alerta,consecutive')
                ->orderBy('id', 'DESC') // ordena por id descendente
                ->first();

            $faltantes = $datosResolucion['number_end'] - $datosResolucion['consecutive'];

            if ($hoy > $datosResolucion['date_end']) {
                //return "Vencida por fecha.";
                return $this->response->setJSON([
                    'response' => 'false',
                    'message' => "Resolución vencida por fecha.",
                ]);
            }

            if ($faltantes <= 0) {
                //return "Vencida por número.";
                return $this->response->setJSON([
                    'response' => 'false',
                    'message' => "Resolucion vencida por consecutivos",
                ]);
            }

            if ($faltantes <= $datosResolucion['alerta']) {
                //return "Alerta: quedan $faltantes números.";

                return $this->response->setJSON([
                    'response' => 'alerta',
                    'message' => "quedan $faltantes números.",
                ]);
            }

            return $this->response->setJSON([
                'response' => 'success'
            ]);
        } else if ($documento == 6) {

            return $this->response->setJSON([
                'response' => 'success'
            ]);
        }
    }

    function actualizarNombreConsecutivo()
    {

        $json = $this->request->getJSON();
        $nombre = $json->nombre;
        $id = $json->id;


        $actualizar = model('consecutivosModel')->set('conceptoconsecutivo', $nombre)->where('idconsecutivos', $id)->update();
    }

    function actualizarSerieConsecutivo()
    {

        $json = $this->request->getJSON();
        $valor = $json->valor;
        $actualizar = model('consecutivosModel')->actualizarSerie($valor);
    }

    function crearConsecutivo()
    {

        $json = $this->request->getJSON();
        $concepto  = $json->concepto;
        $valor  = $json->valor;

        $data = [
            'conceptoconsecutivo' => $concepto,
            'numeroconsecutivo' => $valor
        ];

        $insert = model('consecutivosModel')->insert($data);

        return $this->response->setJSON([
            'response' => 'success',

        ]);
    }

    function bono()
    {
        return view('configuracion/gestionBonos');
    }

    function ventas_mesero()
    {
        $usuarios = model('usuariosModel')->select('idusuario_sistema as id_usuario ,nombresusuario_sistema')->where('estadousuario_sistema', true)->findAll();
        $productos = model('productoModel')->select('nombreproducto,id')->findAll();
        $categorias = model('categoriasModel')->select('nombrecategoria,id')->findAll();

        $fecha_inicial = date('Y-m-d');
        $fecha_final = date('Y-m-d');

        $where[] = "kardex.fecha BETWEEN '{$fecha_inicial}' AND '{$fecha_final}'";
        $whereSql = implode(' AND ', $where);

        $sql = model('kardexModel')->getVentasPorProducto($whereSql);

        $ventas = model('reporteProductoModel')->reporteKardex($sql);


        //dd($ventas);

        $sqlTotal = model('kardexModel')->getTotalVentasPorProducto($whereSql);

        $total_ventas = model('reporteProductoModel')->reporteKardex($sqlTotal);

        // dd($ventas);

        $fechas = model('kardexModel')->getFechas();
        return view('reportes/ventas_mesero_producto', [
            'usuarios' => $usuarios,
            'productos' => $productos,
            'categorias' => $categorias,
            'ventas' => $ventas,
            'total_ventas' => $total_ventas[0]['total_ventas'],
            'fecha_inicial' => $fechas[0]['fecha_inicial'],
            'fecha_final' => $fechas[0]['fecha_final']
        ]);
    }

    function filtro_fecha()
    {
        $json = $this->request->getJSON();
        $filtro  = $json->filtro;

        $html = "";
        $abreModal = "";

        switch ($filtro) {
            case 'tT':
                $fechas = model('kardexModel')->getFechas();
                $html = view('fechas/todos_los_tiempos', [
                    'fecha_inicial' => $fechas[0]['fecha_inicial'],
                    'fecha_final' => $fechas[0]['fecha_final']
                ]);
                $abreModal = false;
                break;
            case 'f':
                $html = view('fechas/fecha');
                $abreModal = false;
                break;
            case 'p':
                $html = view('fechas/periodo');
                $abreModal = false;
                break;
            case 'mC':
                $aperturas = model('aperturaModel')->getAperturas();
                $html = view('fechas/movimientos_de_caja', [
                    'aperturas' => $aperturas
                ]);
                $abreModal = true;
                break;
        }

        return $this->response->setJSON([
            'response' => true,
            'fecha' => $html,
            'abreModal' => $abreModal

        ]);
    }


    public function reporteVentasKardex()
    {

        $fecha_inicial   = $this->request->getPost('fecha_inicial');
        $fecha_final     = $this->request->getPost('fecha_final');
        $codigo_producto = trim($this->request->getPost('codigo_producto') ?? '');
        $id_categoria    = trim($this->request->getPost('id_categoria') ?? '');
        $id_usuario      = trim($this->request->getPost('id_usuario') ?? '');
        $id_apertura     = trim($this->request->getPost('id_apertura') ?? '');


    

        /*       $fecha_inicial   = date('Y-m-d);
        $fecha_final     = date('Y-m-d);
        $codigo_producto = 326;
        $id_categoria    = "";
        $id_usuario      = "";
        $id_apertura     = ""; */


        $where = [];

        // Filtro por fechas, solo si vienen
        if (!empty($fecha_inicial) && !empty($fecha_final)) {

            $where[] = "kardex.fecha BETWEEN '{$fecha_inicial}' AND '{$fecha_final}'";
        } elseif (!empty($fecha_inicial)) {

            $where[] = "kardex.fecha >= '{$fecha_inicial}'";
        } elseif (!empty($fecha_final)) {

            $where[] = "kardex.fecha <= '{$fecha_final}'";
        }


        // Filtro por apertura, si viene
        if (!empty($id_apertura)) {
            $where[] = "kardex.id_apertura = '{$id_apertura}'";
        }


        // Solo puede venir producto o categoría
        if (!empty($codigo_producto)) {

            $where[] = "kardex.codigo = '{$codigo_producto}'";
        } elseif (!empty($id_categoria)) {

            $where[] = "kardex.id_categoria = '{$id_categoria}'";
        }


        // Usuario opcional
        if (!empty($id_usuario)) {
            $where[] = "kardex.idusuario = '{$id_usuario}'";
        }


        // Construir WHERE
        $whereSql = !empty($where)
            ? implode(' AND ', $where)
            : '1=1';

        $sql = model('kardexModel')->getVentasPorProducto($whereSql);

        echo $sql;  exit();

        $ventas = model('reporteProductoModel')->reporteKardex($sql);

        $temp_total_ventas = model('kardexModel')->getTotalVentasPorProducto($whereSql);


        $total_ventas = model('kardexModel')->reporteKardex($temp_total_ventas);



        return $this->response->setJSON([
            'response' => true,
            'ventas' => view('fechas/ventas_mesero', [
                'ventas' => $ventas,
                'total_ventas' => 0
            ]),
            'total_ventas' => number_format($total_ventas[0]['total_ventas'], 0, ',', '.')
        ]);
    }

    public function cortesias()
    {

        /* $fechas = model('kardexModel')->fechaMinMax();
        $fecha_inicial = $fechas[0]['fecha_inicial'];
        $fecha_final   = $fechas[0]['fecha_final'];

        $where = [];
        $where[] = "pagos.fecha BETWEEN '{$fecha_inicial}' AND '{$fecha_final}'";
        $whereSql = implode(' AND ', $where);
 */


        $fechas = model('kardexModel')->fechaMinMax();

        $where = [];

        if (!empty($fechas) && isset($fechas[0])) {

            $fecha_inicial = $fechas[0]['fecha_inicial'] ?? date('Y-m-d');
            $fecha_final   = $fechas[0]['fecha_final'] ?? date('Y-m-d');

            if (!empty($fecha_inicial) && !empty($fecha_final)) {
                $where[] = "pagos.fecha BETWEEN '{$fecha_inicial}' AND '{$fecha_final}'";
            }
        }

        $whereSql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';



        if (!empty($where)) {
            $whereSql = implode(' AND ', $where);
        }



        $kardexConcepto = model('KardexConceptoModel');

        $sql = $kardexConcepto->sqlReporteVentas($whereSql);
        $cortesias = model('kardexModel')->reporteKardex($sql);

        /*    $total_cortesias = model('pagosModel')->total_cortesias(
            $fecha_inicial,
            $fecha_final
        ); */


        $total_cortesias = model('pagosModel')->total_cortesias($whereSql);


        $total_registros = $kardexConcepto->totalRegistros($whereSql);

        return view('reportes/cortesias', [
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' =>  $fecha_final,
            //'documentos' => $documentos,
            'cortesias' => $cortesias,
            'total' => number_format($total_cortesias[0]['total_cortesias'], 0, ',', '.'),
            'total_registros' => $total_registros[0]['total_cortesias']
        ]);
    }

    public function reporteCortesias()
    {

        /*  $fecha_inicial   = "2026-08-19";
        $fecha_final     = "2026-08-19";
        $id_apertura     = ""; */

        $fecha_inicial   = $this->request->getPost('fecha_inicial');
        $fecha_final     = $this->request->getPost('fecha_final');
        $id_apertura     = trim($this->request->getPost('id_apertura') ?? '');


        $filtro_fechas = new Inventario();

        $where = $filtro_fechas->fitro_fechas(
            $fecha_inicial,
            $fecha_final,
            $id_apertura
        );

        $kardexConcepto = model('KardexConceptoModel');
        $sql = $kardexConcepto->sqlReporteVentas($where);
        $cortesias = model('kardexModel')->reporteKardex($sql);
        $total_registros = $kardexConcepto->totalRegistros($where);
        $total_cortesias = model('pagosModel')->total_cortesias($where);



        return $this->response->setJSON([
            'response' => true,
            'cortesias' => view('cortesias/cortesias', [
                'cortesias' => $cortesias,
            ]),
            'total_registros' => $total_registros[0]['total_cortesias'],
            'total' => number_format($total_cortesias[0]['total_cortesias'], 0, ',', '.')

        ]);
    }

    public function reporteMeseros()
    {

        $datos_empresa = model('empresaModel')->datosEmpresa();
    }



    public function reporte_meseros()
    {
        try {

            // ========================================
            // DATOS Y FILTROS
            // ========================================

            $fecha_inicial = $this->request->getPost('fecha_inicial');
            $fecha_final   = $this->request->getPost('fecha_final');

            $id_producto  = trim($this->request->getPost('id_producto') ?? '');
            $id_categoria = trim($this->request->getPost('id_categoria') ?? '');
            $id_usuario   = trim($this->request->getPost('id_usuario') ?? '');
            $id_apertura  = trim($this->request->getPost('id_apertura') ?? '');

            $agrupar_por = $this->request->getPost('agrupar_por');

            $db = \Config\Database::connect();

            $where = [];

            // ========================================
            // FILTRO POR FECHAS
            // ========================================

            if (!empty($fecha_inicial) && !empty($fecha_final)) {

                $where[] = "kardex.fecha BETWEEN "
                    . $db->escape($fecha_inicial)
                    . " AND "
                    . $db->escape($fecha_final);
            } elseif (!empty($fecha_inicial)) {

                $where[] = "kardex.fecha >= "
                    . $db->escape($fecha_inicial);
            } elseif (!empty($fecha_final)) {

                $where[] = "kardex.fecha <= "
                    . $db->escape($fecha_final);
            }

            // ========================================
            // FILTRO POR APERTURA
            // ========================================

            if (!empty($id_apertura)) {

                $where[] = "kardex.id_apertura = "
                    . $db->escape($id_apertura);
            }

            // ========================================
            // FILTRO POR PRODUCTO O CATEGORÍA
            // ========================================

            if (!empty($id_producto)) {

                $producto = model('productoModel')
                    ->select('codigointernoproducto')
                    ->where('id', $id_producto)
                    ->first();

                if (!$producto) {

                    return $this->response->setJSON([
                        'response' => false,
                        'mensaje'  => 'El producto seleccionado no existe.'
                    ]);
                }

                $where[] = "kardex.codigo = "
                    . $db->escape($producto['codigointernoproducto']);
            } elseif (!empty($id_categoria)) {

                $where[] = "kardex.id_categoria = "
                    . $db->escape($id_categoria);
            }

            // ========================================
            // FILTRO POR USUARIO
            // ========================================

            if (!empty($id_usuario)) {

                $where[] = "kardex.idusuario = "
                    . $db->escape($id_usuario);
            }

            // ========================================
            // CONSTRUIR WHERE
            // ========================================

            $whereSql = !empty($where)
                ? implode(' AND ', $where)
                : '1=1';

            // ========================================
            // CONSULTAR VENTAS
            // ========================================

            $sql = model('kardexModel')
                ->getVentasPorProducto($whereSql);

            $ventas = model('reporteProductoModel')
                ->reporteKardex($sql);

            // ========================================
            // CONSULTAR TOTAL DE VENTAS
            // ========================================

            $temp_total_ventas = model('kardexModel')
                ->getTotalVentasPorProducto($whereSql);

            $total_ventas = model('kardexModel')
                ->reporteKardex($temp_total_ventas);

            $total = $total_ventas[0]['total_ventas'] ?? 0;

            // ========================================
            // NORMALIZAR RESULTADOS
            // ========================================

            $ventas = array_map(function ($fila) {
                return (array) $fila;
            }, $ventas ?? []);

            // ========================================
            // CREAR EXCEL
            // ========================================

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            $hoja = $spreadsheet->getActiveSheet();

            $hoja->setTitle('Reporte de ventas');

            // ========================================
            // ENCABEZADO DE EMPRESA
            // DATOS FICTICIOS
            // ========================================

            $hoja->setCellValue('A1', 'DISTRIBUIDORA EL PROGRESO S.A.S.');
            $hoja->mergeCells('A1:F1');

            $hoja->setCellValue('A2', 'NIT: 900.123.456-7');
            $hoja->mergeCells('A2:F2');

            $hoja->setCellValue(
                'A3',
                'Dirección: Calle 10 # 15-25 - Pereira, Risaralda'
            );
            $hoja->mergeCells('A3:F3');

            $hoja->setCellValue('A4', 'Teléfono: (606) 123 4567');
            $hoja->mergeCells('A4:F4');

            $hoja->setCellValue(
                'A5',
                'Correo: ventas@elprogreso.com'
            );
            $hoja->mergeCells('A5:F5');

            // ESTILO NOMBRE DE EMPRESA

            $hoja->getStyle('A1:F1')->applyFromArray([
                'font' => [
                    'bold'  => true,
                    'size'  => 16,
                    'color' => [
                        'rgb' => '198754'
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical'   => 'center'
                ]
            ]);

            // ESTILO DATOS DE EMPRESA

            $hoja->getStyle('A2:F5')->applyFromArray([
                'font' => [
                    'size'  => 10,
                    'color' => [
                        'rgb' => '555555'
                    ]
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical'   => 'center'
                ]
            ]);

            // ALTURA DE FILAS

            $hoja->getRowDimension(1)->setRowHeight(28);
            $hoja->getRowDimension(2)->setRowHeight(18);
            $hoja->getRowDimension(3)->setRowHeight(18);
            $hoja->getRowDimension(4)->setRowHeight(18);
            $hoja->getRowDimension(5)->setRowHeight(18);

            // ========================================
            // TÍTULO DEL REPORTE
            // ========================================

            $hoja->setCellValue(
                'A7',
                'REPORTE DE VENTAS KARDEX'
            );

            $hoja->mergeCells('A7:F7');

            $hoja->getStyle('A7:F7')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical'   => 'center'
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'E9F5EE'
                    ]
                ]
            ]);

            $hoja->getRowDimension(7)->setRowHeight(25);

            // ========================================
            // INFORMACIÓN DE FILTROS
            // ========================================

            $hoja->setCellValue('A9', 'Fecha inicial:');
            $hoja->setCellValue('B9', $fecha_inicial ?: 'Todas');

            $hoja->setCellValue('C9', 'Fecha final:');
            $hoja->setCellValue('D9', $fecha_final ?: 'Todas');

            $hoja->setCellValue('A10', 'Total ventas:');
            $hoja->setCellValue('B10', (float) $total);

            $hoja->getStyle('A9:D10')
                ->getFont()
                ->setBold(true);

            // FORMATO TOTAL DE VENTAS

            $hoja->getStyle('B10')->getNumberFormat()
                ->setFormatCode('#,##0.00');

            // ========================================
            // ENCABEZADOS DINÁMICOS
            // ========================================

            $filaInicio = 12;

            if (!empty($ventas)) {

                $columnas = array_keys($ventas[0]);

                $columna = 1;

                foreach ($columnas as $nombre) {

                    $letra = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                        $columna
                    );

                    $titulo = ucfirst(
                        str_replace('_', ' ', $nombre)
                    );

                    $hoja->setCellValue(
                        $letra . $filaInicio,
                        $titulo
                    );

                    $columna++;
                }

                // ========================================
                // DATOS
                // ========================================

                $filaExcel = $filaInicio + 1;

                foreach ($ventas as $venta) {

                    $columna = 1;

                    foreach ($columnas as $campo) {

                        $valor = $venta[$campo] ?? '';

                        $letra = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                            $columna
                        );

                        $hoja->setCellValue(
                            $letra . $filaExcel,
                            $valor
                        );

                        $columna++;
                    }

                    $filaExcel++;
                }

                // ========================================
                // FORMATO ENCABEZADOS DE TABLA
                // ========================================

                $ultimaColumna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    count($columnas)
                );

                $hoja->getStyle(
                    "A{$filaInicio}:{$ultimaColumna}{$filaInicio}"
                )->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => [
                            'rgb' => 'FFFFFF'
                        ]
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '198754'
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical'   => 'center'
                    ]
                ]);

                // ========================================
                // BORDES DE LA TABLA
                // ========================================

                $hoja->getStyle(
                    "A{$filaInicio}:{$ultimaColumna}" . ($filaExcel - 1)
                )->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(
                        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    );
            } else {

                $hoja->setCellValue(
                    'A12',
                    'No se encontraron registros para los filtros seleccionados.'
                );
            }

            // ========================================
            // AJUSTAR ANCHO DE COLUMNAS
            // ========================================

            $ultimaColumnaHoja = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString(
                $hoja->getHighestColumn()
            );

            for ($i = 1; $i <= $ultimaColumnaHoja; $i++) {

                $columna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                    $i
                );

                $hoja->getColumnDimension($columna)
                    ->setAutoSize(true);
            }

            // ========================================
            // FUENTE GENERAL
            // ========================================

            $hoja->getStyle(
                'A1:' . $hoja->getHighestColumn() . $hoja->getHighestRow()
            )->getFont()
                ->setName('Aptos Narrow')
                ->setSize(11);

            // ========================================
            // GUARDAR ARCHIVO TEMPORAL
            // ========================================

            $nombreArchivo = 'Reporte_Ventas_Kardex_' . date('Ymd_His') . '.xlsx';

            $directorio = WRITEPATH . 'uploads/';

            $ruta = $directorio . $nombreArchivo;

            if (!is_dir($directorio)) {

                mkdir($directorio, 0775, true);
            }

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
                $spreadsheet
            );

            $writer->save($ruta);

            $spreadsheet->disconnectWorksheets();

            unset($spreadsheet);

            // ========================================
            // DESCARGAR ARCHIVO
            // ========================================

            return $this->response
                ->download($ruta, null)
                ->setFileName($nombreArchivo);
        } catch (\Throwable $e) {

            log_message(
                'error',
                'Error exportando Kardex: ' . $e->getMessage()
            );

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'response' => false,
                    'mensaje'  => 'Ocurrió un error al generar el archivo Excel.'
                ]);
        }
    }
}
