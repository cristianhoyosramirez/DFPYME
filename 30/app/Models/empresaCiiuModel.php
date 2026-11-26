<?php

namespace App\Models;

use CodeIgniter\Model;

class empresaCiiuModel extends Model
{
    protected $table      = 'empresa_ciiu';
    // Uncomment below if you want add primary key
    // protected $primaryKey = 'id';
    protected $allowedFields = ['codigo'];
}
