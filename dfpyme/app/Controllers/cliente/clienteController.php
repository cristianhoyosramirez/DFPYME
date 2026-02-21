<?php

namespace App\Controllers\cliente;

use App\Controllers\BaseController;

class ClienteController extends BaseController
{
    public $db;
    public function __construct()
    {

        $this->db = \Config\Database::connect();
    }
    public function index()
    {
        if (!isset($_POST['palabraClave'])) {
            $clientes = model('clientesModel')->orderBy('id', 'desc')->find();
        } else {
            $search = $_POST['palabraClave'];
            $clientes = model('clientesModel')->clientes($search);
        }
        $response = array();
        foreach ($clientes as $detalle) {
            $response[] = array(
                "id" => $detalle['nitcliente'],
                "text" => $detalle['nombrescliente']
            );
        }
        echo json_encode($response);
    }


     public function agregar()
    {
        if (
            !$this->validate([
                'tipo_persona' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Tipo de persona es obligatorio.',
                    ],
                ],
                'identificacion' => [
                    'rules' => 'required|is_unique[cliente.nitcliente]',
                    'errors' => [
                        'required' => 'La identificación es obligatoria.',
                        'is_unique' => 'El cliente ya existe.',
                    ],
                ],
                'dv' => [
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => 'El dígito de verificación es obligatorio.',
                        'numeric' => 'El DV debe ser numérico.',
                    ],
                ],
                'direccion' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'La dirección es obligatoria.',
                    ],
                ],
                'departamento' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'El departamento es obligatorio.',
                    ],
                ],
                'ciudad' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'La ciudad es obligatoria.',
                    ],
                ],


                'correo_electronico' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'El correo electrónico es obligatorio.',
                        'valid_email' => 'Correo electrónico inválido.',
                    ],
                ],
                'telefono' => [
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => 'El teléfono es obligatorio.',
                        'numeric' => 'El teléfono debe ser numérico.',
                    ],
                ],
            ])
        ) {
            echo json_encode(['code' => 0, 'error' => $this->validator->getErrors()]);
            return;
        }

        $id_ciudad = $this->request->getPost('ciudad');

        // $code = model('municipiosModel')->select('code')->where('id', $this->request->getPost('municipios'))->first();
        //  $code = model('municipiosModel')->select('code')->where('id', $this->request->getPost('municipios'))->first();


        if (empty($_POST['nombre_comercial'])) {
            $nombreComercial = "X";
        } else if (!empty($_POST['nombre_comercial'])) {
            $nombreComercial = $_POST['nombre_comercial'];
        }

        $identicacacion = $_POST['identificacion'];

        $tipoPersona = $_POST['tipo_persona'];
        $apellidos = $_POST['apellidos'];

        if ($tipoPersona == 2) {  // Tipo de persona Natural 
            $nombre = $_POST['nombres']." ".$apellidos;
        }
        if ($tipoPersona == 1) {  // Tipo de persona Natural 
            $nombre = $_POST['razon_social'];
        }





        $data = [
            'nitcliente' => $identicacacion,
            'idregimen' => $_POST['regimen'],
            'nombrescliente' => $nombre,
            'telefonocliente' => $_POST['telefono'],
            'celularcliente' => $_POST['telefono'],
            'emailcliente' => $_POST['correo_electronico'],
            'idciudad' =>  $id_ciudad,
            'direccioncliente' => $_POST['direccion'],
            'estadocliente' => true,
            'idtipo_cliente' => 1,
            'id_clasificacion' => 1,
            'name' => $_POST['nombres'],
            'last_name' => $_POST['apellidos'],
            'dv' => $_POST['dv'],
            'type_person' => $tipoPersona,
            'type_document' => $_POST['tipo_documento'],
            'name_comercial' => $nombreComercial,
            'is_customer' => true
        ];



        $insert = model('clientesModel')->insert($data);


      /*   $model = model('ciudadModel');
        $ciudad = $model->set('code', '834');
        $ciudad = $model->set('code_postal', '050001');
        $ciudad = $model->where('idciudad', $id_ciudad);
        $ciudad = $model->update();*/


        $impuestos = [
            'nit_cliente' => $identicacacion,
            'codigo' => 'ZZ',
            'nombre' => 'No aplica',
            'descripcion' => 'Otros tributos, tasas, contribuciones, y similares'
        ];



        $insertar_impuestos = model('detallesTributariosModel')->insert($impuestos);


        $rut = [
            'nit_cliente' => $identicacacion,
            'codigo' => 'R-99-PN',
            'descripcion' => 'ORDINARIO'
        ];

        $insert = model('detallesRutModel')->insert($rut);
 

        $regimen = model('regimenModel')->orderBy('idregimen', 'desc')->findAll();
        $tipo_cliente = model('tipoClienteModel')->orderBy('id', 'asc')->findAll();
        $clasificacion_cliente = model('clasificacionClienteModel')->orderBy('id', 'asc')->findAll();
        $departamento = model('departamentoModel')->select('*')->where('idpais', 49)->find();
        $id_departamento_empresa = model('empresaModel')->select('iddepartamento')->first();
        $id_ciudad_empresa = model('empresaModel')->select('idciudad')->first();
        $ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $id_ciudad_empresa['idciudad'])->first();
        $municipios = model('municipiosModel')->findAll();

        $returnData = array(
            "code" => 1,
            "cliente" => $identicacacion . " " . $nombre . " " . $apellidos,
            "nit_cliente" => $identicacacion,
            "clientes" => view('clientes/listado', [
                'regimen' => $regimen,
                'tipo_cliente' => $tipo_cliente,
                'clasificacion_cliente' => $clasificacion_cliente,
                'departamentos' => $departamento,
                "id_departamento" => $id_departamento_empresa['iddepartamento'],
                "id_ciudad" => $id_ciudad_empresa['idciudad'],
                "ciudad" => $ciudad['nombreciudad'],
            ])

        );
        echo  json_encode($returnData);
    } 


    


    public function clientes_autocompletadoNota()
    {
        $returnData = array();
        $valor = $this->request->getVar('term');
        //$valor = 'cr';


        $resultado = model('clientesModel')->clientesNota($valor);



        if (!empty($resultado)) {
            foreach ($resultado as $row) {
                $data['value'] =  $row['telefonocliente'] . " " . "/" . " " . $row['nombrescliente']." / ".$row['nitcliente'];
              $data['datos'] = "Cliente: " . $row['nombrescliente'] . "\n" .
                 "Teléfono: " . $row['telefonocliente'] . "\n" .
                 "Dirección: " . $row['direccioncliente'];



                array_push($returnData, $data);
            }
            echo json_encode($returnData);
        } else {
            $data['value'] = "No hay resultados";
            array_push($returnData, $data);
            echo json_encode($returnData);
        }
    }


    public function clientes_autocompletado()
    {
        $returnData = array();
        $valor = $this->request->getVar('term');
        //$valor = 'cr';


        $resultado = model('clientesModel')->clientes($valor);



        if (!empty($resultado)) {
            foreach ($resultado as $row) {
                $data['value'] =  $row['nitcliente'] . " " . "/" . " " . $row['nombrescliente'];
                $data['nit_cliente'] = $row['nitcliente'];


                array_push($returnData, $data);
            }
            echo json_encode($returnData);
        } else {
            $data['value'] = "No hay resultados";
            array_push($returnData, $data);
            echo json_encode($returnData);
        }
    }

    function todos_los_clientes()
    {
        $valor_buscado = $_GET['search']['value'];
        //$valor_buscado = 'CLIE';

        $sql_count = '';
        $sql_data = '';


        $table_map = [
            0 => 'id',
            1 => 'nitcliente',
            2 => 'nombrescliente',
            3 => 'descripcionestado',
            4 => 'fecha_factura_venta',
            5 => 'horafactura_venta',
            6 => 'fechalimitefactura_venta',
        ];


        $sql_count = "SELECT
                        COUNT(id) AS total
                        FROM
                    cliente
                    inner join regimen on cliente.idregimen = regimen.idregimen 
                    ";

        $sql_data = " SELECT *
        FROM
            cliente
            inner join regimen on cliente.idregimen = regimen.idregimen
            ";
        $condition = "";
        if (!empty($valor_buscado)) {

            $condition .= " WHERE cliente.nitcliente ILIKE '%" . $valor_buscado . "%'";
            $condition .= " OR cliente.nombrescliente ILIKE '%" . $valor_buscado . "%'";
        }

        $sql_count = $sql_count . $condition;
        $sql_data = $sql_data . $condition;

        $total_count = $this->db->query($sql_count)->getRow();

        $sql_data .= " ORDER BY " . $table_map[$_GET['order'][0]['column']] . " " . $_GET['order'][0]['dir'] . " " . "LIMIT " . $_GET['length'] . " OFFSET " . $_GET['start'];

        $datos = $this->db->query($sql_data)->getResultArray();


        if (!empty($datos) && !empty($total_count)) {

            foreach ($datos as $detalle) {
                $sub_array = array();
                $sub_array[] = $detalle['nitcliente'];
                $sub_array[] = $detalle['nombrescliente'];
                $sub_array[] = $detalle['celularcliente'];
                $sub_array[] = $detalle['direccioncliente'];
                $sub_array[] = $detalle['nombreregimen'];




                $sub_array[] = '<div class="d-flex align-items-center">
                <a class="btn btn-outline-success btn-icon me-2" onclick="editar_cliente(' . $detalle['id'] . ')">Editar</a>
                <a class="btn btn-outline-danger btn-icon" onclick="eliminar_cliente(' . $detalle['id'] . ')">Eliminar</a>
            </div>';


                $data[] = $sub_array;
            }

            $json_data = [
                //'draw' => intval($this->request->getGEt(index: 'draw')),
                'draw' => intval($this->request->getGEt(index: 'draw')),
                'recordsTotal' => $total_count->total,
                'recordsFiltered' => $total_count->total,
                'data' => $data,

            ];
            echo  json_encode($json_data);
        } else {
            $sub_array = array();
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $sub_array[] = 'NO HAY DATOS ';
            $data[] = $sub_array;
            $json_data = [
                //'draw' => intval($this->request->getGEt(index: 'draw')),
                'draw' => intval($this->request->getGEt(index: 'draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => $data,

            ];
            echo  json_encode($json_data);
        }
    }

    function tabla_todos_los_clientes()
    {
        $regimen = model('regimenModel')->orderBy('idregimen', 'desc')->findAll();
        $tipo_cliente = model('tipoClienteModel')->orderBy('id', 'asc')->findAll();
        $clasificacion_cliente = model('clasificacionClienteModel')->orderBy('id', 'asc')->findAll();
        $departamento = model('departamentoModel')->select('*')->where('idpais', 49)->find();
        $id_departamento_empresa = model('empresaModel')->select('iddepartamento')->first();
        $id_ciudad_empresa = model('empresaModel')->select('idciudad')->first();
        $ciudad = model('ciudadModel')->select('nombreciudad')->where('idciudad', $id_ciudad_empresa['idciudad'])->first();
        $municipios = model('municipiosModel')->findAll();


        return view('clientes/listado', [
            'regimen' => $regimen,
            'tipo_cliente' => $tipo_cliente,
            'clasificacion_cliente' => $clasificacion_cliente,
            'departamentos' => $departamento,
            "id_departamento" => $id_departamento_empresa['iddepartamento'],
            "id_ciudad" => $id_ciudad_empresa['idciudad'],
            "ciudad" => $ciudad['nombreciudad'],
        ]);
    }

    function nuevo_cliente()
    {

        if (!$this->validate([
            'identificacion_cliente' => [
                'rules' => 'required|is_unique[cliente.nitcliente]',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Identificación ya existe '

                ]
            ],
            'nombres_cliente' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'regimen_cliente' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'tipo_cliente' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Dato necesario',
                ]
            ],
            'clasificacion_cliente' => [
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
                'nitcliente' => $_POST['identificacion_cliente'],
                'idregimen' => $_POST['regimen_cliente'],
                'nombrescliente' => $_POST['nombres_cliente'],
                'telefonocliente' => $_POST['telefono_cliente'],
                'celularcliente' => $_POST['celular_cliente'],
                'emailcliente' => $_POST['e-mail'],
                'idciudad' => $_POST['municipios'],
                'direccioncliente' => $_POST['direccion_cliente'],
                'estadocliente' => true,
                'idtipo_cliente' => $_POST['tipo_cliente'],
                'id_clasificacion' => $_POST['clasificacion_cliente']
            ];

            $insert = model('clientesModel')->insert($data);
            if ($insert) {
                echo json_encode(['code' => 1, 'msg' => 'Usuario creado']);
            }
        }
    }

    function editar_cliente()
    {
        //$id_cliente = 660;
        $id_cliente = $_POST['id_cliente'];

        $regimen = model('regimenModel')->orderBy('idregimen', 'desc')->findAll();
        $tipo_cliente = model('tipoClienteModel')->orderBy('id', 'asc')->findAll();
        $clasificacion_cliente = model('clasificacionClienteModel')->orderBy('id', 'asc')->findAll();
        $departamento = model('departamentoModel')->select('*')->where('idpais', 49)->find();


        $municipios = model('municipiosModel')->findAll();



        //Datos de cliente 
        $datos_cliente = model('clientesModel')->select('*')->where('id', $id_cliente)->first();
        $id_departamento = model('ciudadModel')->select('iddepartamento')->where('idciudad', $datos_cliente['idciudad'])->first();

        $codigo_postal = model('ciudadModel')->select('code_postal')->where('idciudad', $datos_cliente['idciudad'])->first();


  
        //$tipos_persona = model('clientesModel')->get_tipos_persona();
        //$tipos_documento = model('clientesModel')->get_tipos_documento();

        $detallles_tributarios = model('detallesTributariosModel')->where('nit_cliente', $datos_cliente['nitcliente'])->first();
    
        //dd($detallles_tributarios);

        $detallles_rut = model('detallesRutModel')->where('nit_cliente', $datos_cliente['nitcliente'])->findAll();
  
        $returnData = array(
            "resultado" => 1,
            "datos_cliente" => view('clientes/formulario_editar_cliente', [
                'regimen' => $regimen,
                'tipo_cliente' => $tipo_cliente,
                'clasificacion_cliente' => $clasificacion_cliente,
                'departamentos' => $departamento,
                "datos_cliente" => $datos_cliente,
                //"tipos_persona" => $tipos_persona,
                "id_departamento" => $id_departamento['iddepartamento'],
                "codigo_postal" => $codigo_postal['code_postal'],
                "detalles_tributarios" => $detallles_tributarios,
                "detalles_rut" => $detallles_rut,
                "id_cliente" => $id_cliente
            ])
        );
        echo  json_encode($returnData);
    }

    function actualizar_datos_cliente()
    {


        //$identificacion="10897202353";
        $identificacion = $this->request->getPost('identificacion');

        /*  $data = [
            'nitcliente' => $identificacion,
            'idregimen' => 1,
            'nombrescliente' => '',
            'telefonocliente' => "00000000",
            'celularcliente' => "00000000",
            'emailcliente' => "correo@corre.com",
            'idciudad' => 1013,
            'direccioncliente' => "CENTRO COMERCIAL NUESTRO CARTAGO",
            'estadocliente' => 'true',
            'idtipo_cliente' => 1,
            'punto' => 0,
            'id_clasificacion' => 1,
            'name' => 'X',
            'last_name' => 'X',
            'dv' => 8,
            'type_person' => 1,
            'type_document' => 31,
            'name_comercial' => 'x',
            'is_customer' => 'true'
        ]; */



        $tipoPersona = $_POST['tipo_depersona'];
        //$tipoPersona = 1;
        if ($tipoPersona == 2) {  // Tipo de persona Natural 
            $nombre = $_POST['nombres'] . " " . $_POST['apellidos_edicion'];
            $apellidos = $_POST['apellidos_edicion'];
        }
        if ($tipoPersona == 1) {  // Tipo de persona Natural 
            //$nombre = $_POST['razon_social'];
            $nombre = $_POST['razon_social_edicion'];
            $apellidos = "";
        }



        $data = [
            'nitcliente' => $identificacion,
            'idregimen' => $this->request->getPost('regimen'),
            'nombrescliente' => $nombre,
            'telefonocliente' => $this->request->getPost('telefono'),
            'celularcliente' => $this->request->getPost('telefono'),
            'emailcliente' => $this->request->getPost('correo_electronico'),
            'idciudad' => $this->request->getPost('ciudad_edicion'),
            'direccioncliente' => $this->request->getPost('direccion'),
            'estadocliente' => 'true',
            'idtipo_cliente' => 1,
            'punto' => 0,
            'id_clasificacion' => 1,
            'name' => $nombre,
            'last_name' => $apellidos,
            'dv' => $this->request->getPost('dv'),
            'type_person' => $this->request->getPost('tipo_depersona'),
            'type_document' => $this->request->getPost('tipo_documento'),
            'name_comercial' => $nombre,
            'is_customer' => 'true'
        ];



        /* 
        $data = [
            'nitcliente'        => "1089720235",                                 // ["nitcliente"]
            'idregimen'         => "1",                                          // ["idregimen"]
            'nombrescliente'    => "HOSPITAL de ",                               // ["nombrescliente"]
            'telefonocliente'   => "00000000",                                   // ["telefonocliente"]
            'celularcliente'    => "00000000",                                   // ["celularcliente"]
            'emailcliente'      => "correo@corre.com",                           // ["emailcliente"]
            'idciudad'          => "1013",                                       // ["idciudad"]
            'direccioncliente'  => "CENTRO COMERCIAL NUESTRO CARTAGO",          // ["direccioncliente"]
            'estadocliente'     => "true",                                       // ["estadocliente"]
            'idtipo_cliente'    => 1,                                         // ["idtipo_cliente"]
            'punto'             => 0,                                            // ["punto"]
            'id_clasificacion'  => 1,                                         // ["id_clasificacion"]
            'name'              => "HOSPITAL de ",                               // ["name"]
            'last_name'         => "bbb",                                         // ["last_name"]
            'dv'                => "8",                                          // ["dv"]
            'type_person'       => "1",                                          // ["type_person"]
            'type_document'     => "31",                                         // ["type_document"]
            'name_comercial'    => "HOSPITAL de ",                                         // ["name_comercial"]
            'is_customer'       => "true"                                        // ["is_customer"]
        ]; */






        $id_cliente = $this->request->getPost('id_cliente');



        $model = model('clientesModel');
        $cliente = $model->set($data);
        $cliente = $model->where('id', $id_cliente);
        $cliente = $model->update();



        /* 
        $model = model('detallesTributariosModel');
        $cliente = $model->where('nit_cliente', $this->request->getPost('identificacion'));
        $cliente = $model->delete(); */

        //$descripcion_impuesto = model('impuestosModel')->select('descripcion')->where('codigo', $_POST['impuestos_cliente'])->first();



        /*    $impuestos = [
            'nit_cliente' => $identificacion,
            'codigo' => 'ZZ',
            'nombre' => 'No aplica',
            'descripcion' => 'Otros tributos, tasas, contribuciones, y similares'
        ];; 


        $insertar_impuestos = model('detallesTributariosModel')->insert($impuestos);


        $model = model('detallesRutModel');
        $cliente_res = $model->where('nit_cliente', $this->request->getPost('identificacion'));
        $cliente_res = $model->delete();

        $responsabilidad_rut = $this->request->getPost('responsabilidad_fiscal');

        foreach ($responsabilidad_rut as $detalle) {

            $descripcion = model('responsabilidadFiscalModel')->select('descripcion')->where('codigo', $detalle)->find();


            //$codigo = model('responsabilidadFiscalModel')->select('codigo')->where('id',$responsabilidad_rut)->first();

            $rut = [
                'nit_cliente' => $_POST['identificacion'],
                'codigo' => $detalle,
                'descripcion' => $descripcion[0]['descripcion']
            ];

            $insert = model('detallesRutModel')->insert($rut);
        }


*/


        if ($cliente) {
            echo json_encode(['code' => 1, 'msg' => 'Datos cambiados ']);
        }
        //}
    }

    function deleteCliente()
    {
        $json = $this->request->getJSON();
        $id = $json->id;

        $nitCliente = model('clientesModel')->select('nitcliente')->where('id', $id)->first();

        $movimientos = model('pagosModel')->select('nit_cliente')->where('nit_cliente', $nitCliente)->first();

        if (empty($movimientos)) {

            $delete = model('clientesModel')->where('id', $id)->delete();

            return $this->response->setJSON([
                'response' => 'success'
            ]);
        }
        if (!empty($movimientos)) {

            return $this->response->setJSON([
                'response' => 'fail'
            ]);
        }
    }
}
