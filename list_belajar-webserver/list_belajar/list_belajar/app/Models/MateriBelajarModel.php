<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriBelajarModel extends Model
{
    protected $table = 'materi';
    protected $primaryKey = 'id_materi';
    protected $allowedFields = ['id_playlist', 'id_tipe', 'nama_materi'];
}
