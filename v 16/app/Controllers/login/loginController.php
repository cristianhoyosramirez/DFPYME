<?php

namespace App\Controllers\login;

use App\Controllers\BaseController;

class loginController extends BaseController
{
    public function index()
    {
        return view('home/home');
    }

    public function login()
    {

        if (!$this->validate([
            'pin' => [
                'rules' => 'required|is_not_unique[usuario_sistema.pinusuario_sistema]|max_length[4]',
                'errors' => [
                    'required' => 'El pin es requerido',
                    'is_not_unique' => 'Pin inexistente',
                    'max_length' => 'Longitud máxima de 4 digitos'

                ]
            ],

        ])) {
            return redirect()->to(base_url('/'))->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT table_name 
            FROM information_schema.tables 
            WHERE table_schema = 'public' 
              AND table_name = 'estado_licencia'
        ");
        // $resultado = $query->getRow();



        if ($query->getNumRows() === 0) {

            return view('actualizaciones/generarActualizaciones');
        }


        $estadoLicencia = model('licenciaModel')->select('estado_licencia')->first();





        //if ($estadoLicencia['estado'] == 't') {
        $pin = $this->request->getVar('pin');
        $usuario = model('usuariosModel')->usuario_valido($pin);
        #$usuario = model('usuariosModel')->select('*')->where('pinusuario_sistema', $pin)->first();


        if (!empty($usuario)) {
            $tipo_permiso = model('tipoPermisoModel')->select('*')->where('idusuario_sistema', $usuario[0]['idusuario_sistema'])->find();



            if ($usuario) {
                $datosSesion = [
                    'id_usuario' => $usuario[0]['idusuario_sistema'],
                    'usuario' => $usuario[0]['usuariousuario_sistema'],
                    'nombre_usuario' => $usuario[0]['nombresusuario_sistema'],
                    'logged_in' => TRUE,
                    'tipo' => $usuario[0]['idtipo'],
                    'tipo_permiso' => $tipo_permiso
                ];
                $sesion = session();
                $sesion->set($datosSesion);

                if (in_array($usuario[0]['idtipo'], [0, 1, 3, 4, 5])) {
                    $estado = $estadoLicencia['estado_licencia'];

                    if ($estado == "Activa") {
                        return redirect()->to(base_url('pedidos/mesas'));
                    }

                    if ($estado == "En Mora") {
                        $mensajeLicencia = model('licenciaModel')->select('mensaje_licencia,estado_licencia')->first();

                        $session = session();
                        $session->setFlashdata('iconoMensaje', 'warning');
                        session()->setFlashdata('estado', $mensajeLicencia['estado_licencia']);
                        return redirect()->to(base_url('pedidos/mesas'))->with('mensaje', $mensajeLicencia['mensaje_licencia']);
                    }

                    if ($estado == "Suspendida" || $estado == "Inactiva") {
                        $mensajeLicencia = model('licenciaModel')->select('mensaje_licencia,estado_licencia')->first();
                        $session = session();
                        $session->setFlashdata('iconoMensaje', 'error');
                        $session->setFlashdata('estado', "Estado licencia: " . $mensajeLicencia['estado_licencia']);
                        $session->setFlashdata('mensaje', $mensajeLicencia['mensaje_licencia']);
                        return redirect()->to(base_url());
                    }
                }


                if ($usuario[0]['idtipo'] == 2) {
                    return redirect()->to(base_url('pedidos/gestion_pedidos'));
                }
            } else {
                $datosSesion = [
                    'id_usuario' => $usuario['idusuario_sistema'],
                    'usuario' => $usuario['usuariousuario_sistema'],
                    'nombre_usuario' => $usuario['nombresusuario_sistema'],
                    'logged_in' => FALSE,
                    'tipo' => $usuario['idtipo']
                ];
                $sesion = session();
                $sesion->set($datosSesion);
                return redirect()->to(base_url('/'))->withInput()->with('errors', $this->validator->getErrors());
            }
        } else if (empty($usuario)) {

            $session = session();
            $session->setFlashdata('iconoMensaje', 'Error');
            $session->setFlashdata('titulo', '');
            return redirect()->to(base_url())->with('mensaje', 'Usuario inactivo o no existe');
        }
    }
    public function closeSesion()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url() . '/');
    }

    public function recetas()
    {
        $recetas = model('productoModel')
            ->select('codigointernoproducto,id,nombreproducto,precio_costo,valorventaproducto,id_tipo_inventario')
            //->where('id_tipo_inventario', 3)
            //->where('id_tipo_inventario', 6)
            ->whereIn('id_tipo_inventario', [3, 7])
            ->where('estadoproducto', true)
            ->orderBy('nombreproducto', 'asc')
            ->findAll();




        //$insumos = model('productoModel')->select('codigointernoproducto,id,nombreproducto,precio_costo,valorventaproducto')->where('id_tipo_inventario', 4)->orderBy('nombreproducto', 'asc')->findAll();
        $insumos = model('productoModel')
            ->select('codigointernoproducto,id,nombreproducto,precio_costo,valorventaproducto')
            //->where('id_tipo_inventario', 4)
            ->whereIn('id_tipo_inventario', [4, 7, 1])
            ->orderBy('nombreproducto', 'asc')->findAll();
        $openModal = model('configuracionPedidoModel')->select('recetasmodal')->first();

        return view('producto/recetas', [
            'recetas' => $recetas,
            'insumos' => $insumos,
            'modal' => $openModal['recetasmodal']
        ]);
    }

    public function Searchrecetas()
    {
        $returnData = array();
        $valor = $this->request->getVar('valorBusqueda');

        $resultado = model('productoModel')->GetRecetas($valor);

        if (!empty($resultado)) {
            return $this->response->setJSON([
                'response' => 'success',

                'productos' =>  view('recetas/listaRecetas', [
                    'productos' => $resultado
                ]),

            ]);
        } else if (empty($resultado)) {

            return $this->response->setJSON([
                'response' => 'empty',
            ]);
        }
    }

    function SearchInsumosRecetas()
    {

        $json = $this->request->getJSON();
        $codigo = $json->codigointernoproducto;
        //$codigo = '201';

        $productos = model('productoFabricadoModel')->GetInsumos($codigo);
        $costo = model('productoFabricadoModel')->GetCosto($codigo);
        $receta = model('productoModel')->GetReceta($codigo);
        $precioVenta = model('productoModel')->GetValVenta($codigo);

        if (!empty($productos)) {
            return $this->response->setJSON([
                'response' => 'success',

                'productos' =>  view('recetas/insumos', [
                    'productos' => $productos
                ]),
                'receta' => '<span class="text-primary">Componentes receta:</span> <span class="text-orange">' . htmlspecialchars($receta[0]['nombreproducto'], ENT_QUOTES, 'UTF-8') . '</span>',

                'costo' => $costo[0]['costo'],
                'precioVenta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.'),
                'rentabilidad' => number_format($precioVenta[0]['valorventaproducto'] - $costo[0]['costo'], 0, ',', '.'),
                'codigo' => $codigo,
                'nombreReceta' => $receta[0]['nombreproducto']


            ]);
        } else if (empty($productos)) {
            return $this->response->setJSON([
                'response' => 'fail',
                'receta' => '<span class="text-primary">Componentes receta:</span> <span class="text-orange">' . htmlspecialchars($receta[0]['nombreproducto'], ENT_QUOTES, 'UTF-8') . '</span>',
                'costo' => number_format($costo[0]['costo'], 0, ',', '.'),
                'precioVenta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.'),
                'rentabilidad' => number_format($precioVenta[0]['valorventaproducto'] - $costo[0]['costo'], 0, ',', '.'),
                'codigo' => $codigo,
                'nombreReceta' => $receta[0]['nombreproducto']
            ]);
        }
    }

    function deleteInsumo()
    {

        $json = $this->request->getJSON();
        $id = $json->id;

        $receta = model('productoFabricadoModel')->select('prod_fabricado')->where('id', $id)->first();
        $codigoReceta = $receta['prod_fabricado'];

        $borrar = model('productoFabricadoModel')->where('id', $id)->delete();

        if ($borrar) {

            $costo = model('productoFabricadoModel')->GetCosto($codigoReceta);
            $precioVenta = model('productoModel')->GetValVenta($codigoReceta);

            return $this->response->setJSON([
                'response' => 'success',
                'id' => $id,
                'rentabilidad' => number_format(
                    ($precioVenta[0]['valorventaproducto'] ?? 0) - ($costo[0]['costo'] ?? 0),
                    0,
                    ',',
                    '.'
                ),

                'costo' => number_format($costo[0]['costo'], 0, ',', '.'),
                'precio_venta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.')

            ]);
        }
    }

    function SearchInsumos()
    {
        $json = $this->request->getJSON();
        $valor = $json->valorBusqueda;

        $insumos = model('productoModel')->GetInsumos($valor);

        if (!empty($insumos)) {
            return $this->response->setJSON([
                'response' => 'success',

                'productos' =>  view('recetas/listaInsumos', [
                    'productos' => $insumos
                ]),

            ]);
        }
        if (empty($insumos)) {
            return $this->response->setJSON([
                'response' => 'empty',

                'productos' =>  view('recetas/listaInsumos', [
                    'productos' => $insumos
                ]),

            ]);
        }
    }

    function addInsumo()
    {
        $json = $this->request->getJSON();
        $codigoReceta = $json->codigoReceta;
        //$codigoReceta = 107;
        $codigoInsumo = $json->codigoInsumo;
        // $codigoInsumo = 14;
        $cantidad = $json->cantidad;
        //$cantidad = 4;

        //$existeInsumo = model('productoFabricadoModel')->select('prod_proceso')->where('prod_proceso', $codigoInsumo)->first();
        $existeInsumo = model('productoFabricadoModel')->existeInsumo($codigoReceta, $codigoInsumo);

        //dd($existeInsumo);

        if (!empty($existeInsumo)) {

            return $this->response->setJSON([
                'response' => 'exist',

            ]);
        }
        if (empty($existeInsumo)) {

            $data = [
                'prod_fabricado' => $codigoReceta,
                'prod_proceso' => $codigoInsumo,
                'cantidad' => $cantidad
            ];

            $insert = model('productoFabricadoModel')->insert($data);

            if ($insert) {

                $insumos = model('productoFabricadoModel')->GetInsumos($codigoReceta);
                $costo = model('productoFabricadoModel')->GetCosto($codigoReceta);
                $precioVenta = model('productoModel')->GetValVenta($codigoReceta);

                return $this->response->setJSON([
                    'response' => 'success',
                    'insumos' =>  view('recetas/insumos', [
                        'productos' => $insumos
                    ]),
                    'rentabilidad' => number_format(
                        ($precioVenta[0]['valorventaproducto'] ?? 0) - ($costo[0]['costo'] ?? 0),
                        0,
                        ',',
                        '.'
                    ),

                    'costo' => number_format($costo[0]['costo'], 0, ',', '.'),
                    'precio_venta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.')
                ]);
            }
        }
    }


    function updateCantidadInsumo()
    {

        $json = $this->request->getJSON();
        $id = $json->id;
        //$id = 39;
        $valor = $json->valor;
        //$valor = 22;

        $actualizar = model('productoFabricadoModel')->set('cantidad', $valor)->where('id', $id)->update();
        $prod_proceso = model('productoFabricadoModel')->select('prod_proceso')->where('id', $id)->first();

        $prod_fabricado = model('productoFabricadoModel')->select('prod_fabricado')->where('id', $id)->first();
        $codigoReceta = $prod_fabricado['prod_fabricado'];
        $costo = model('productoFabricadoModel')->GetCosto($codigoReceta);

        $costoUnitario = model('productoModel')->GetCostoUnitario($prod_proceso['prod_proceso']);

        $precioVenta = model('productoModel')->GetValVenta($codigoReceta);

        return $this->response->setJSON([
            'response' => 'success',

            'rentabilidad' => number_format(
                ($precioVenta[0]['valorventaproducto'] ?? 0) - ($costo[0]['costo'] ?? 0),
                0,
                ',',
                '.'
            ),

            'costo' => $costo[0]['costo'],
            'precio_venta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.'),
            //'costoTotal' => ($costoUnitario[0]['precio_costo'] * $valor),
            //'costoTotal' => round($costoUnitario[0]['precio_costo'] * $valor, 2),
            'costoTotal' => number_format($costoUnitario[0]['precio_costo'] * $valor, 0, '.', ','),

            'id' => $id
        ]);
    }

    /*  function updateCosto()
    {

        $json = $this->request->getJSON();
        $codigointerno = $json->codigointerno;
        //$id = 39;
        $id = $json->id;
        $valor = $json->valor;
        //$valor = 22;

        $valor = str_replace(['.', ','], '', $valor);
        $actualizar = model('productoModel')->set('precio_costo', $valor)->where('codigointernoproducto', $codigointerno)->update();

        if ($actualizar) {

            $cantidad = model('productoFabricadoModel')->select('cantidad')->where('id', $id)->first();

            $costoTotal = $cantidad['cantidad'] * $valor;


            return $this->response->setJSON([
                'response' => 'success',
                'id' => $id,
                'costoTotal'=>number_format($costoTotal, 0, ',', '.')
            ]);
        }
    } */

    public function updateCosto()
    {
        $json = $this->request->getJSON();
        $codigointerno = $json->codigointerno ?? null;
        $id            = $json->id ?? null;
        $valor         = $json->valor ?? 0;

        // Sanitizar y convertir a número
        $valor = str_replace(['.', ','], '', $valor);
        $valor = (float) $valor;

        if (!$codigointerno || !$id) {
            return $this->response->setJSON([
                'response' => 'error',
                'message'  => 'Datos incompletos para actualizar el costo.'
            ]);
        }

        // Actualizar el costo del producto
        $actualizar = model('productoModel')
            ->set('precio_costo', $valor)
            ->where('codigointernoproducto', $codigointerno)
            ->update();

        if (!$actualizar) {
            return $this->response->setJSON([
                'response' => 'error',
                'message'  => 'No fue posible actualizar el costo del producto.'
            ]);
        }

        // Consultar la cantidad del producto fabricado
        $cantidad = model('productoFabricadoModel')
            ->select('cantidad')
            ->where('id', $id)
            ->first();

        if (!$cantidad) {
            return $this->response->setJSON([
                'response' => 'error',
                'message'  => 'No se encontró el producto fabricado con el ID proporcionado.'
            ]);
        }

        // Calcular costo total
        $costoTotal = (float) $cantidad['cantidad'] * $valor;

        $codigProductoFabricado = model('productoFabricadoModel')->select('prod_fabricado')->where('id', $id)->first();

        $costoReceta = model('productoFabricadoModel')->GetCosto($codigProductoFabricado['prod_fabricado']);
        $precioVenta = model('productoModel')->GetValVenta($codigProductoFabricado['prod_fabricado']);

        return $this->response->setJSON([
            'response'   => 'success',
            'id'         => $id,
            'costoTotal' => number_format($costoTotal, 0, ',', '.'),
            'costoReceta' => number_format($costoReceta[0]['costo'], 0, ',', '.'),
            'precio_venta' => number_format($precioVenta[0]['valorventaproducto'], 0, ',', '.'),
            'rentabilidad' => number_format(
                ($precioVenta[0]['valorventaproducto'] ?? 0) - ($costoReceta[0]['costo'] ?? 0),
                0,
                ',',
                '.'
            ),

        ]);
    }




    function updateMedida()
    {

        $json = $this->request->getJSON();
        $idUnidad = $json->idUnidad;
        $codigProducto = $json->codigoInternoProducto;

        $update = model('productoMedidaModel')->set('idvalor_unidad_medida', $idUnidad)->where('codigointernoproducto', $codigProducto)->update();

        if ($update) {
            return $this->response->setJSON([
                'response' => 'success',
            ]);
        }
    }

    function allRecetas()
    {
        $resultado = model('productoModel')->select('codigointernoproducto,id,nombreproducto,precio_costo,valorventaproducto,id_tipo_inventario')->where('id_tipo_inventario', 3)->orderBy('nombreproducto', 'asc')->findAll();

        return $this->response->setJSON([
            'response' => 'success',
            'productos' =>  view('recetas/listaRecetas', [
                'productos' => $resultado
            ]),

        ]);
    }

    function AllInsumos()
    {



        //$insumos = model('productoModel')->GetaLLInsumos();

        $insumos = model('productoModel')
            ->select('codigointernoproducto,id,nombreproducto,precio_costo,valorventaproducto')
            //->where('id_tipo_inventario', 4)
            ->whereIn('id_tipo_inventario', [4, 7, 1])
            ->orderBy('nombreproducto', 'asc')->findAll();

        if (!empty($insumos)) {
            return $this->response->setJSON([
                'response' => 'success',

                'productos' =>  view('recetas/listaInsumos', [
                    'productos' => $insumos
                ]),

            ]);
        }
        if (empty($insumos)) {
            return $this->response->setJSON([
                'response' => 'empty',

                'productos' =>  view('recetas/listaInsumos', [
                    'productos' => $insumos
                ]),

            ]);
        }
    }

    function actualizarNombre()
    {

        $json = $this->request->getJSON();
        $codigo = $json->codigo;
        //$codigo = 2;
        $nombre = $json->nombre;
        //$nombre = 'aaaa';

        //$actualizar = model('productoModel')->set('nombreproducto', $nombre)->where('codigointernoproducto', string $codigo)->update();



        //$actualizar = model('productoModel')->update($codigo, ['nombreproducto' => $nombre]);
        $actualizar = model('productoModel')->editarNombre($nombre, $codigo);

        return $this->response->setJSON([
            'response' => true,
        ]);
    }

    /*  function eliminarProducto()
    {

        $json = $this->request->getJSON();
        $codigo = $json->codigo;
        //$codigo = 26;

        $codigo = (string)  $codigo;


        $idTipoInventario = model('productoModel')->select('id_tipo_inventario')->where('codigointernoproducto', $codigo)->first();

        $idTipoInventario['id_tipo_inventario'];



        if ($idTipoInventario['id_tipo_inventario'] == 1 or $idTipoInventario['id_tipo_inventario'] == 4  or $idTipoInventario['id_tipo_inventario'] == 7) {

            //Es un insumo y se puede borrar 

            $eliminarKardex = model('kardexModel')->where('codigo', $codigo)->delete();
            $itemDocumentoElectronico = model('itemFacturaElectronicaModel')->where('codigo', $codigo)->delete();
            $inventario = model('inventarioModel')->where('codigointernoproducto', $codigo)->delete();
            $insumo = model('productoFabricadoModel')->where('prod_proceso', $codigo)->delete();

            $eliminar = model('productoModel')
                ->where('codigointernoproducto', (string) $codigo)
                ->delete();


            return $this->response->setJSON([
                'response' => 'success',
            ]);
        } else if (($idTipoInventario['id_tipo_inventario'] == 3)) {

            $tieneMovimientos = model('itemFacturaElectronicaModel')->where('codigo', $codigo)->first();

            if (!empty($tieneMovimientos)) {
                $seActualiza = model('productoModel')->set('estadoproducto', FALSE)->where('codigointernoproducto', $codigo)->update();
                return $this->response->setJSON([
                    'response' => 'success',

                ]);
            } else if (empty($tieneMovimientos)) {

                $eliminar = model('productoModel')
                    ->where('codigointernoproducto', (string) $codigo)
                    ->delete();


                return $this->response->setJSON([
                    'response' => 'success',

                ]);
            }
        }
    } */

    public function eliminarProducto()
    {
        $json = $this->request->getJSON();
        $codigo = (string) ($json->codigo ?? '');
        //$codigo = (string) (220 ?? '');

        if (empty($codigo)) {
            return $this->response->setJSON(['response' => 'error', 'message' => 'Código no proporcionado']);
        }

        $productoModel = model('productoModel');
        $idTipoInventario = $productoModel
            ->select('id_tipo_inventario')
            ->where('codigointernoproducto', $codigo)
            ->first();

        if (empty($idTipoInventario)) {
            return $this->response->setJSON(['response' => 'error', 'message' => 'Producto no encontrado']);
        }

        $tipo = (int) $idTipoInventario['id_tipo_inventario'];

        switch ($tipo) {
            // Tipos de insumo o productos simples
            case 1:
            case 4:
            case 7:
                $idProducto = model('productoModel')->select('id')->where('codigointernoproducto', $codigo)->first();
                model('EntradasSalidasManualesModel')->where('id_producto', $idProducto['id'])->delete();
                model('inventarioFisicoModel')->where('codigointernoproducto', $codigo)->delete();

                model('kardexModel')->where('codigo', $codigo)->delete();
                model('itemFacturaElectronicaModel')->where('codigo', $codigo)->delete();
                model('inventarioModel')->where('codigointernoproducto', $codigo)->delete();
                model('productoFabricadoModel')->where('prod_proceso', $codigo)->delete();
                $productoModel->where('codigointernoproducto', $codigo)->delete();
                break;

            // Tipo 3: producto con posibles movimientos
            case 3:
                $tieneMovimientos = model('itemFacturaElectronicaModel')
                    ->where('codigo', $codigo)
                    ->first();

                if (!empty($tieneMovimientos)) {
                    $productoModel
                        ->set('estadoproducto', false)
                        ->where('codigointernoproducto', $codigo)
                        ->update();
                } else {
                    $productoModel->where('codigointernoproducto', $codigo)->delete();
                }
                break;

            default:
                return $this->response->setJSON(['response' => 'error', 'message' => 'Tipo de inventario no manejado']);
        }

        return $this->response->setJSON(['response' => 'success']);
    }
}
