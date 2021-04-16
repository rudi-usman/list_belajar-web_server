<?php

namespace App\Controllers;

use App\Models\MateriBelajarModel;
use App\Models\PlaylistBelajarModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;



class PlaylistBelajar extends ResourceController
{
    use ResponseTrait;
    // get all data
    public function index()
    {
        $model = new PlaylistBelajarModel();
        $data = $model->findAll();

        return $this->respond($data);
    }

    // get single data
    public function show($id = null)
    {
        $model = new PlaylistBelajarModel();
        $data = $model->getWhere(['id_playlist' => $id])->getResult();

        // connect database
        $db = \Config\Database::connect();

        if ($data) {
            // looking for old filename
            $old_file = $db->query("SELECT * FROM playlist_belajar WHERE id_playlist = $id")->getRowArray();
            $new_data = $old_file['views'];

            $model_materi = new MateriBelajarModel();
            $materi = $model_materi->getWhere(['id_playlist' => $id])->getResult();

            $data = [
                'id_playlist' => $id,
                'id_akun' => $old_file['id_akun'],
                'profile_pict' => $old_file['profile_pict'],
                'nama_playlist' => $old_file['nama_playlist'],
                'kategori' => $old_file['kategori'],
                'deskripsi' => $old_file['deskripsi'],
                'views' => $new_data + 1,
                'materi' => $materi
            ];
            $model->update($id, $data);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => [
                    'success' => 'View Updated',
                ],
                'data_playlist' =>  $data,
            ];
            return $this->respond($response);
        } else {
            return $this->failNotFound('No Data Found with playlist id ' . $id);
        }
    }

    // create a playlist_belajar
    public function create()
    {
        // Get the File
        $file = $this->request->getFile('profile_pict');
        // Guess an extension 
        $type = $file->guessExtension();
        // Move File into Specific Folder
        $file->move('../public/files/profile');

        $model = new PlaylistBelajarModel();
        $data = [
            'id_akun' => $this->request->getVar('id_akun'),
            'profile_pict' => $file->getName(),
            'nama_playlist' => $this->request->getVar('nama_playlist'),
            'kategori' => $this->request->getVar('kategori'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'views' => '0'
        ];
        $model->insert($data);
        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'Data Saved'
            ]
        ];
        return $this->respondCreated($response);
    }

    // Update playlist_belajar
    public function update($id = null)
    {
        // Set timezone
        $myTime = new Time('now', 'Asia/Jakarta', 'en_US');

        // connect database
        $db = \Config\Database::connect();

        $model = new PlaylistBelajarModel();
        $data = $model->find($id);
        if ($data) {

            // looking for old filename
            $nama_file = $db->query("SELECT * FROM playlist_belajar WHERE id_playlist = $id")->getRowArray();
            $file_lama = $nama_file['profile_pict'];

            // Get the File
            $file = $this->request->getFile('profile_pict');
            $filename = $file->getName();
            // Guess an extension 
            $type = $file->guessExtension();

            $input = $this->request->getVar();
            $data = [
                'id_akun' => $input['id_akun'],
                'profile_pict' => $file->getName(),
                'nama_playlist' => $input['nama_playlist'],
                'kategori' => $input['kategori'],
                'deskripsi' => $input['deskripsi'],
                'updated_at' => $myTime
            ];
            $model->update($id, $data);

            // check the file is already filled or not
            if ($filename != $file_lama) {
                // Delete Old Files
                unlink("../public/files/profile/$file_lama");
                // Move File into Specific Folder
                $file->move('../public/files/profile');

                // $input = $this->request->getRawInput();
                $data['filename'] = $file->getName();
                $model->update($id, $data);
                $response = [
                    'status' => 200,
                    'error' => null,
                    'message' => [
                        'success' => 'Data Updated'
                    ]
                ];
                return $this->respond($response);
            }
            $response = [
                'status' => 200,
                'error' => null,
                'message' => [
                    'success' => 'Data Updated'
                ]
            ];
            return $this->respond($response);
        } else {
            return $this->failNotFound('No Data Found with playlist id ' . $id);
        }
    }

    // Delete playlist_belajar
    public function delete($id = null)
    {
        // connect database
        $db = \Config\Database::connect();

        $model = new PlaylistBelajarModel();
        $data = $model->find($id);

        // looking for filename
        $nama_file = $db->query("SELECT profile_pict FROM playlist_belajar WHERE id_playlist = $id")->getRowArray();
        $file = $nama_file['profile_pict'];


        if ($data) {
            $nama_file = $db->query("SELECT profile_pict FROM playlist_belajar WHERE id_playlist = $id")->getResultArray();
            unlink("../public/files/profile/$file");
            $model->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with playlist id ' . $id);
        }
    }

    public function index_playlist($id_akun = null)
    {
        $db = \Config\Database::connect();

        // Get Playlist Data and Bookmarked Status
        $query = "SELECT a.*, b.marked_at FROM `playlist_belajar` AS a LEFT JOIN `bookmark` AS b ON a.id_playlist = b.id_playlist AND b.id_akun = $id_akun ORDER BY a.id_playlist";
	    $data = $db->query($query)->getResultArray();

        // Count Total Bookmarked Playlist
        $count_bookmarked = "SELECT DISTINCT id_playlist, count(id_playlist) AS c FROM `bookmark` GROUP BY id_playlist ORDER BY id_playlist";
        $count = (array)$db->query($count_bookmarked)->getResultArray();

        // Convert to Array
        $data = (array)$data;
        
        // Assign 'bookmarked_count' Property to $data If Playlist Bookmarked By User or Another User
        for ($i = 0; $i < count($data); $i++)
        {
            for ($j = 0; $j < count($count); $j++)
            {
                if ($data[$i]['id_playlist'] == $count[$j]['id_playlist'])
                    $data[$i]['bookmarked_count'] = $count[$i]['c'];
            }
        }

        // Convert Back to Object
        $data = (object)$data;
        
        return $this->respond($data);
    }
}
