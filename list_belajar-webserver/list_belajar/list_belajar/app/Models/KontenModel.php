<?php

namespace App\Models;

use CodeIgniter\Model;

class KontenModel extends Model
{
    protected $table = 'konten';
    protected $primaryKey = 'id_materi';
    protected $allowedFields = ['id_materi', 'id_tipe', 'nama_file'];
}
