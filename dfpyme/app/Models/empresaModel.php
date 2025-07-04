<?php

namespace App\Models;

use CodeIgniter\Model;

class empresaModel extends Model
{
    protected $table      = 'empresa';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = [ 'nitempresa',
    'idregimen',
    'nombrecomercialempresa',
    'nombrejuridicoempresa',
    'telefonoempresa',
    'celularempresa',
    'faxempresa',
    'emailempresa',
    'pagwebempresa',
    'representantelegalempresa',
    'iddepartamento',
    'idciudad',
    'direccionempresa',
    'estadoempresa',
    'descripcion',
    'recauda_iva',
    'fk_tipo_empresa'
];

    public function datosEmpresa()
    {
        $datos = $this->db->query("
        SELECT
        regimen.descripcion as nombreregimen,
        nitempresa,
        nombrecomercialempresa,
        telefonoempresa,
        emailempresa,
        pagwebempresa,
        direccionempresa,
        representantelegalempresa,
        nombrejuridicoempresa,
        ciudad.nombreciudad,
        departamento.nombredepartamento,
        empresa.idregimen,
        empresa.iddepartamento,
        empresa.idciudad
    FROM
    empresa
    inner join regimen on empresa.idregimen=regimen.idregimen
    inner join ciudad on ciudad.idciudad =empresa.idciudad
    inner join departamento on departamento.iddepartamento =empresa.iddepartamento

        ");
        return $datos->getResultArray();
    }
    public function Empresa()
    {
        $datos = $this->db->query("
        select nombreregimen,
            nombrecomercialempresa,
            nombredepartamento,
            direccionempresa,
            nombreciudad
            from empresa  inner join regimen on regimen.idregimen= empresa.idregimen 
            inner join departamento on departamento.iddepartamento= empresa.iddepartamento
            inner join ciudad on ciudad.idciudad = empresa.idciudad

        ");
        return $datos->getResultArray();
    }
}
