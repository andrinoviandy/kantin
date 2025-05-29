<?php

namespace App\Models;

use CodeIgniter\Model;

class Transaksi_detail_tempModel extends Model
{
    protected $table            = 'transaksi_detail_temp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['id', 'id_siswa', 'id_guru', 'id_barang', 'modal', 'harga', 'jumlah'];
}
