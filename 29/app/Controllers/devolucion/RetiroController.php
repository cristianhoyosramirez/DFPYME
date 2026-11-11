<?php

namespace App\Controllers\devolucion;

use App\Controllers\BaseController;

class RetiroController extends BaseController
{
    public function crear_cuenta()
    {
        return view('cuentas_retiro/cuentas_retiro');
    }


    function agregar_cuenta()
    {

        if (!$this->validate([
            'nombre_cuenta' => [
                'rules' => 'required|is_unique[cuenta_retiro.nombre_cuenta]',
                'errors' => [
                    'required' => 'Dato necesario',
                    'is_unique' => 'Nombre de cuenta ya existe '

                ]
            ],

        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre_cuenta' => $this->request->getVar('nombre_cuenta'),

        ];
        $insert = model('cuentaRetiroModel')->insert($data);
        if ($insert) {
            $session = session();
            $session->setFlashdata('iconoMensaje', 'success');

            return redirect()->to(base_url('devolucion/listado'))->with('mensaje', 'Creación correcta');
        }
    }

    function listado()
    {
        $cuentas = model('cuentaRetiroModel')->orderBy('id', 'DESC')->findAll();
        return view('cuentas_retiro/listado', [
            'cuentas' => $cuentas
        ]);
    }


    function rubros_listado()
    {
        //$rubros = model('cuentaRetiroModel')->get_cuentas_rubros();
        $data = $this->request->getJSON(true);

        $id = $data['id'];
        //$id=1;

        $rubros = model('cuentaRetiroModel')->get_cuentas_rubros($id);

        /*  return view('cuentas_retiro_rubros/listado', [
            'rubros' => $rubros
        ]); */

        $nombre_cuenta = model('cuentaRetiroModel')->select('nombre_cuenta')->where('id', $id)->first()['nombre_cuenta'];


        return $this->response->setJSON([
            'status'   => true,
            'sub_cuentas'  => view('cuentas_retiro_rubros/listado', [
                'rubros'   => $rubros
            ]),
            'id' => $id,
            'nombre_cuenta' => $nombre_cuenta

        ]);
    }
    function crear_rubro()
    {
        $cuentas = model('cuentaRetiroModel')->orderBy('id', 'asc')->findAll();
        return view('cuentas_retiro_rubros/cuentas_retiro_rubros', [
            'cuentas' => $cuentas
        ]);
    }


    function agregar_rubro()
    {
        $json = $this->request->getJSON();
        $id_cuenta = $json->id_cuenta;
        $sub_cuenta = $json->sub_cuenta;

        $data = [
            'id_cuenta_retiro' => $id_cuenta,
            'nombre_rubro' => $sub_cuenta,

        ];
        $insert = model('rubrosModel')->insert($data);

        $rubros = model('cuentaRetiroModel')->get_cuentas_rubros($id_cuenta);

        $nombre_cuenta = model('cuentaRetiroModel')->select('nombre_cuenta')->where('id', $id_cuenta)->first()['nombre_cuenta'];

        return $this->response->setJSON([
            'status' => true,
            'sub_cuentas'  => view('cuentas_retiro_rubros/listado', [
                'rubros'   => $rubros
            ]),
            'nombre_cuenta' => "Cuenta: " . $nombre_cuenta

        ]);
    }



    function cuenta_rubro()
    {
        $rubros = model('rubrosModel')->where('id_cuenta_retiro', $this->request->getVar('cuenta_retiro'))->orderBy('id', 'ASC')->findAll();
        $returnData = array(
            "resultado" => 1,
            "rubros" => view('cuentas_retiro_rubros/select_cuentas_rubros', [
                'rubros' => $rubros
            ])
        );
        echo  json_encode($returnData);
    }

    function editar_rubro()
    {
        $id_rubro = $_POST['id'];
        $id_cuenta_retiro = model('rubrosModel')->select('id_cuenta_retiro')->where('id', $id_rubro)->first();
        $cuentas = model('cuentaRetiroModel')->orderBy('id', 'asc')->findAll();
        $nombre_rubro = model('rubrosModel')->select('nombre_rubro')->where('id', $id_rubro)->first();
        return view('cuentas_retiro_rubros/editar_cuentas_retiro', [
            'cuentas' => $cuentas,
            'id_cuenta_retiro' => $id_cuenta_retiro['id_cuenta_retiro'],
            'nombre_rubro' => $nombre_rubro['nombre_rubro'],
            'id_rubro' => $id_rubro
        ]);
    }

    function actualizar_rubro()
    {
        $data = [
            'id_cuenta_retiro' => $this->request->getVar('cuenta_rubro'),
            'nombre_rubro' => $this->request->getVar('nombre_rubro'),

        ];
        $model = model('rubrosModel');
        $actualizar = $model->set($data);
        $actualizar = $model->where('id', $_POST['id_rubro']);
        $actualizar = $model->update();


        $session = session();
        $session->setFlashdata('iconoMensaje', 'success');

        return redirect()->to(base_url('devolucion/rubros_listado'))->with('mensaje', 'Actualización correcta');
    }

    function deleteRubro()
    {

        $json = $this->request->getJSON();
        $id = $json->id;

        $delete = model('rubrosModel')->where('id', $id)->delete();

        return $this->response->setJSON([
            'response' => 'success',
            'id' => $id

        ]);
    }

    function consultarApertura()
    {


        $aperturaRegistro = model('aperturaRegistroModel')->first();


        if (!empty($aperturaRegistro)) {

            return $this->response->setJSON([
                'response' => true,

            ]);
        } else {

            return $this->response->setJSON([
                'response' => false,

            ]);
        }
    }

    function editar_cuenta()
    {
        $json = $this->request->getJSON();
        $id_cuenta = $json->id;
        $nombre_cuenta = $json->nombre_cuenta;

        $data = [
            'nombre_rubro' => $nombre_cuenta,

        ];
        $actualizar = model('rubrosModel')->set($data)->where('id', $id_cuenta)->update;

        //$rubros = model('cuentaRetiroModel')->get_cuentas_rubros($id_cuenta);

        // $nombre_cuenta = model('cuentaRetiroModel')->select('nombre_cuenta')->where('id',$id_cuenta)->first()['nombre_cuenta'];

        return $this->response->setJSON([
            'status' => true
        ]);
    }
}
