<?php

namespace App\Controllers;

use App\Models\MateriBelajarModel;
use App\Models\KontenModel;
use App\Models\PlaylistBelajarModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;

class MateriBelajar extends ResourceController
{

    use ResponseTrait;

    // get all data
    public function index()
    {
        $model = new MateriBelajarModel();
        $data = $model->findAll();
        $model_konten = new KontenModel();
        $data1 = $model_konten->findAll();
        return $this->respond([$data, $data1]);
    }

    // get single data
    public function show($id = null)
    {
        $model = new MateriBelajarModel();
        $data = $model->getWhere(['id_materi' => $id])->getResult();
        $model_konten = new KontenModel();
        $data1 = $model_konten->getWhere(['id_materi' => $id])->getResult();
        if ($data) {
            return $this->respond([$data, $data1]);
        } else {
            return $this->failNotFound('No Data Found with playlist id' . $id);
        }
    }

    // create a playlist_belajar
    public function create()
    {
        // Set timezone
        $myTime = new Time('now', 'Asia/Jakarta', 'en_US');

        // connect database
        $db = \Config\Database::connect();

        $model = new MateriBelajarModel();
        $data = [
            'id_playlist' => $this->request->getVar('id_playlist'),
            'id_tipe' => $this->request->getPost('id_tipe'),
            'nama_materi' => $this->request->getVar('nama_materi'),
            'updated_at' => $myTime
        ];
        $model->insert($data);

        $id_playlist = $data['id_playlist'];
        $id_tipe = $data['id_tipe'];
        $id_materi = $db->query("SELECT * FROM materi WHERE id_playlist = $id_playlist AND id_tipe = $id_tipe ORDER BY id_materi desc LIMIT 1")->getRowArray();

        // Set time playlist
        $data_playlist = [
            'updated_at' => $myTime
        ];
        $model_playlist = new PlaylistBelajarModel();
        $model_playlist->update($id_playlist, $data_playlist);

        // Get the File
        $file = $this->request->getFile('nama_file');
        if ($file != '') {
            // Guess an extension 
            $type = $file->guessExtension();
            // Move File into Specific Folder
            $file->move('../public/files');
        }

        $model1 = new KontenModel();
        $data_konten = [
            'id_materi' => $id_materi['id_materi'],
            'id_tipe' => $id_tipe,
            'nama_file' => $file->getName(),
            'link' => $this->request->getVar('link')
        ];
        $model1->insert($data_konten);

        $modelplaylist = new PlaylistBelajarModel();
        $data_playlist = [
            'updated_at' => $id_materi['updated_at']
        ];
        $modelplaylist->updatecreated($id_playlist, $data_playlist);

        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'Data Saved ' . $file
            ]
        ];
        return $this->respondCreated($response);
    }

    // Update playlist_belajar
    public function update($id = null)
    {
        $db = \config\Database::connect();

        $model = new MateriBelajarModel();
        $model1 = new KontenModel();
        $data = $model->find($id);
        $data_konten = $model1->find($id);

        if ($data && $data_konten) {
            $input = $this->request->getVar();
            $data = [
                'id_playlist' => $input['id_playlist'],
                'id_tipe' => $input['id_tipe'],
                'nama_materi' => $input['nama_materi'],
            ];
            $model->update($id, $data);

            // looking for old filename
            $nama_file = $db->query("SELECT * FROM konten WHERE id_materi = $id")->getRowArray();
            $file_lama = $nama_file['nama_file'];

            // Get the File
            $file = $this->request->getFile('nama_file');
            if ($file != '' || $file_lama != '') {
                // Guess an extension 
                $type = $file->guessExtension();
            }
            $filename = $file->getName();

            // check the file is already filled or not
            if ($filename != '' || $filename != $file_lama) {
                if ($filename != $file_lama && $file_lama != '') {
                    // Delete Old Files
                    unlink("../public/files/$file_lama");
                }
                if ($filename != '') {
                    // Move File into Specific Folder
                    $file->move('../public/files');
                }
            }

            // $input = $this->request->getRawInput();
            $data_konten = [
                'nama_file' => $file->getName(),
                'link' => $this->request->getVar('link')
            ];
            $model1->update($id, $data_konten);



            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data Updated ' . $file_lama
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

        $model = new MateriBelajarModel();
        $model_konten = new KontenModel();
        $data = $model->find($id);
        $data_konten = $model_konten->find($id);

        // looking for filename
        $nama_file = $db->query("SELECT nama_file FROM konten WHERE id_materi = $id")->getRowArray();
        $file = $nama_file['nama_file'];

        if ($data && $data_konten) {
            $nama_file = $db->query("SELECT nama_file FROM konten WHERE id_materi = $id")->getResultArray();
            unlink("../public/files/$file");
            $model->delete($id);
            $model_konten->delete($id);


            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data Deleted ' . $file
                ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with playlist id ' . $id);
        }
    }
}