<?php

namespace App\Controllers\Blog;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\Blog\CategoriesTransModel;
use CodeIgniter\Database\RawSql;
use App\Models\Blog\PostModel;


class PostController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form']);
        $this->tema = new Tema();
    }

    public function index()
    {
        $this->tema->setJudul('Post');
        $this->tema->loadTema('/blog/post');
    }

    public function ajaxList()
    {
        $postModel = new PostModel();
        $postModel->setRequest($this->request);
        $lists = $postModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->post_id;
            $aksi = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Lihat Data" onclick="lihat_data('.$id.')"><i class="fa fa-search"></i></a>';
            if(enforce(3, 3)) {
                $aksi .= '<a href="javascript:;" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a>';
            }

            if(enforce(3, 4)) {
                $aksi .= '<a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            }
            $aksi .= '<a href="'.base_url('blog/postbody/'.$id.'/index').'" class="text-info ml-2" data-toggle="tooltip" data-placement="top" title="Blog Post"><i class="fa fa-book"></i></a>';
            $action = $aksi;
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->post_url;
			$row[] = $list->post_title;
			$row[] = $list->post_desc;
			$row[] = $list->post_visited;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $postModel->countAll(),
                "recordsFiltered" => $postModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function saveData()
    {
        $postModel = new PostModel();
        $categoriesTransModel = new CategoriesTransModel();

        $method = $this->request->getPost('method');
        

        $validation = [
            'val_post_url' => 'required','val_post_title' => 'required','val_post_desc' => 'required',
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

        $id = $this->request->getPost('post_id');
		$data['post_url'] = $this->request->getPost('val_post_url');
		$data['post_title'] = $this->request->getPost('val_post_title');
		$data['post_desc'] = $this->request->getPost('val_post_desc');

        if ($method == 'save') {
            $postModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $categories = $this->request->getPost('val_post_categories');
            if (is_array($categories)) {
                $arrayCate = [];
                foreach ($categories as $key => $value) {
                    $arrayCate[] = [
                        'categories_id' => $value,
                        'post_id' => $id,
                        'id' => sprintf('%05d', $id).sprintf('%05d', $value)
                    ];
                }
                if (!empty($arrayCate)) {
                    $categoriesTransModel->where(['post_id' => $id])->delete();
                    $categoriesTransModel->insertBatch($arrayCate);
                }
            }
            $postModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            $log['categories'] = $categories;
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $postModel = new PostModel();
        $query = $postModel->select("post_id, post_url, post_title, post_desc")->find($id);
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $postModel = new PostModel();
        $query = $postModel->delete($id);
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

    public function posturlExist()
    {
        $postModel = new PostModel();
        $post_id = $this->request->getPost('post_id');
        $post_url = $this->request->getPost('post_url');
        $query = $postModel->where(['post_id !=' => $post_id, 'post_url' => $post_url])->first();
        if (!empty($query)) {
            return $this->response->setJSON(false);
        }
        return $this->response->setJSON(true);
    }
}
