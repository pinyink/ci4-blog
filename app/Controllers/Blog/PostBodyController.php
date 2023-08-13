<?php

namespace App\Controllers\Blog;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models\Blog\PostBodyModel;
use App\Models\Blog\PostModel;

class PostBodyController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form', 'Post']);
        $this->tema = new Tema();
    }

    public function index($postId)
    {
        $postModel = new PostModel();
        $query = $postModel->find($postId);
        $this->tema->setJudul('Post Body');
        $this->tema->loadTema('/blog/postbody', ['post' => $query]);
    }

    public function getList($postId)
    {
        $postBodyModel = new PostBodyModel();
        $query = $postBodyModel->select('post_body_content as content, post_body_categori as syntac')->where(['post_id' => $postId])->orderBy('post_body_order', 'asc')->findAll();
        $array = [];
        foreach ($query as $key => $value) {
            $array[] = categoriesEncode($value['syntac'], $value['content']);
        }
        return $this->response->setJSON(['data' => $query, 'content' => $array]);
    }

    public function ajaxList()
    {
        $postBodyModel = new PostBodyModel();
        $postId = $this->request->getPost('postId');

        $postBodyModel->setWhere(['a.post_id' => $postId]);
        $postBodyModel->setRequest($this->request);
        $lists = $postBodyModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->post_body_id;
            $aksi = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Lihat Data" onclick="lihat_data('.$id.')"><i class="fa fa-search"></i></a>';
            if(enforce(3, 3)) {
                $aksi .= '<a href="javascript:;" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a>';
            }

            if(enforce(3, 4)) {
                $aksi .= '<a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            }
            $action = $aksi;
            
            $row[] = $action;
			$row[] = categoriesEncode($list->post_body_categori, $list->post_body_content);
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $postBodyModel->countAll(),
                "recordsFiltered" => $postBodyModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $postBodyModel = new PostBodyModel();

        $method = $this->request->getPost('method');
        

        $validation = [
            'val_post_id' => 'required','val_post_body_content' => 'required','val_post_body_categori' => 'required','val_post_body_order' => 'required',
        ];

        
        $validated = $this->validate($validation);
        if ($validated === false) {
            $errors = $this->validator->getErrors();
            $message = '<ul>';
            foreach ($errors as $key => $value) {
                $message .= '<li>'.$value.'</li>';
            }
            
            $message .= '</ul>';

            $log['errorCode'] = 2;
            $log['errorMessage'] = $message;
            return $this->response->setJSON($log);
        }

        $id = $this->request->getPost('post_body_id');
		$data['post_id'] = $this->request->getPost('val_post_id');
		$data['post_body_content'] = $this->request->getPost('val_post_body_content');
		$data['post_body_categori'] = $this->request->getPost('val_post_body_categori');
		$data['post_body_order'] = $this->request->getPost('val_post_body_order');

        if ($method == 'save') {
            $postBodyModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $postBodyModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $postBodyModel = new PostBodyModel();
        $query = $postBodyModel->select("post_body_id, post_id, post_body_content, post_body_categori, post_body_order")->find($id);
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $postBodyModel = new PostBodyModel();
        $query = $postBodyModel->delete($id);
        if ($query) {
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Delete Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $log['errorCode'] = 2;
            $log['errorMessage'] = 'Delete Data Gagal';
            $log['errorType'] = 'warning';
            return $this->response->setJSON($log);
        }
    }

    
}
