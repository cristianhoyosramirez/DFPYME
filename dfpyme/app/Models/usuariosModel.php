<?php

namespace App\Models;

use CodeIgniter\Model;

class usuariosModel extends Model
{
    protected $table      = 'usuario_sistema';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['idtipo','cedulausuario_sistema','nombresusuario_sistema','usuariousuario_sistema','contraseniausuario_sistema','estadousuario_sistema',
    'telefonousuario_sistema','direccion_sistema',
    'pinusuario_sistema', 'estadousuario_sistema', 'nombresusuario_sistema'];

    public function id_usuario($pin_eliminacion)
    {
        $datos = $this->db->query("
        SELECT
        idusuario_sistema
    FROM
        usuario_sistema
    WHERE
        pinusuario_sistema = '$pin_eliminacion';
        ");
        return $datos->getResultArray();
    }
    public function usuario_permiso_eliminacion($id_usuario)
    {
        $datos = $this->db->query("
        SELECT
        idusuario_sistema
    FROM
        tipo_permiso
    WHERE
        idpermiso = 90 AND idusuario_sistema = '$id_usuario';
        ");
        return $datos->getResultArray();
    }
    public function usuario_pin($pin)
    {
        $datos = $this->db->query("
        SELECT idusuario_sistema
        FROM   usuario_sistema
        WHERE  pinusuario_sistema = '$pin' 
        ");
        return $datos->getResultArray();
    }
}
