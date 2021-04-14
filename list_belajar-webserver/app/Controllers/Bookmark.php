<?php

namespace App\Controllers;

use App\Models\BookmarkModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Bookmark extends ResourceController
{
    use ResponseTrait;
    // get all data
    public function index()
    {
        $model = new BookmarkModel();
        $data = $model->findAll();
        return $this->respond($data);
    }

    // counting result
    public function show($id = null)
    {
        $model = new BookmarkModel();
        $data = $model->countdata($id);
        $data_count = [
            'counting_result' => $data
        ];

        if ($data) {
            // return $data;
            return $this->respond($data_count, 200);
        } else {
            return $this->failNotFound('No Data Found with playlist id' . $id);
        }
    }

    // create a playlist_belajar
    public function create()
    {
        $model = new BookmarkModel();
        $data = [
            'id_akun' => $this->request->getVar('id_akun'),
            'id_playlist' => $this->request->getVar('id_playlist')
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
    }

    // Delete playlist_belajar
    public function delete($id = null)
    {
        $model = new BookmarkModel();
        $data = $model->find($id);
        if ($data) {
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
