<?php

namespace App\Models;

use CodeIgniter\Model;

class Biaya_adminModel extends Model
{
    protected $table            = 'biaya_admin';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'nominal_biaya'];
}
