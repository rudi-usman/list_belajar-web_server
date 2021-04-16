<?php

namespace App\Models;

use CodeIgniter\Model;

class PlaylistBelajarModel extends Model
{
    protected $table = 'playlist_belajar';
    protected $primaryKey = 'id_playlist';
    protected $allowedFields = ['id_akun', 'profile_pict', 'nama_playlist', 'kategori', 'deskripsi', 'views', 'created_at', 'updated_at'];

    public function updatecreated($id = null, $data = [])
    {
        $db = \Config\Database::connect();
        $builder = $db->table('playlist_belajar');
        $builder->where('id_playlist', $id);
        $result = $builder->update($data);
        return $result;
    }

    public function checkmateri($id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('materi');
        $builder->where('id_playlist', $id);
        $result = $builder->get();
        $row = $result->getRowArray();
        return $row;
    }

    public function deletekonten($id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('konten');
        $builder->where('id_playlist', $id);
        $result = $builder->delete();
        return $result;
    }
}
