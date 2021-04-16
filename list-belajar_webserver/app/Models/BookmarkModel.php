<?php

namespace App\Models;

use CodeIgniter\Model;

class BookmarkModel  extends Model
{
    protected $table = 'bookmark';
    protected $primaryKey = 'id_bookmark';
    protected $allowedFields = ['id_akun', 'id_playlist'];

    public function countdata($id = null)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('bookmark');
        $builder->where('id_playlist', $id);
        $data_count = $builder->countAllResults();
        return $data_count;
    }
}
