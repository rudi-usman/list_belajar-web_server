<?php

namespace App\Controllers;

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
            $data = [
                'views' => $new_data + 1,
            ];
            $model->update($id, $data);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => [
                    'success' => 'Data Updated, Views : ' . $data['views']
                ]
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
}