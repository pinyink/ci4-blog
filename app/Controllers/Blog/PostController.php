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

        $method = $this->request->getPost('method');
        
		$imgpost_image = $this->request->getFile('val_post_image');

        $validation = [
            'val_post_url' => 'required','val_post_title' => 'required','val_post_desc' => 'required',
        ];

        
		if (!empty($_FILES['val_post_image']['name'])) {
			$validation['val_post_image'] = 'uploaded[val_post_image]'
			. '|is_image[val_post_image]'
			. '|mime_in[val_post_image,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
			. '|max_size[val_post_image,2048]';
		}
        $validated = $this->validate($validation);
        if ($validated === false) {
            $errors = $this->validator->getErrors();
            $message = '<ul>';
            foreach ($errors as $key => $value) {
                $message .= '<li>'.$value.'</li>';
            }
            
			if (!empty($_FILES['val_post_image']['name'])) {
				$type = $imgpost_image->getClientMimeType();
				$message .= '<li>'.$imgpost_image->getErrorString() . '(' . $imgpost_image->getError() . ' Type File ' . $type . ' )</li>';
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
		if (!empty($_FILES['val_post_image']['name'])) {
			$th = date('Y') . '/' . date('m').'/'.date('d');
			$path = 'uploads/blog/post/';
			$_dir = $path . $th;
			$dir = ROOTPATH.'public/' . $path . $th;
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			$newName = $imgpost_image->getRandomName();
			$imgpost_image->move($dir, $newName);
			$data['post_image'] = $_dir.'/'.$newName;
		}

        if ($method == 'save') {
            $data['post_created_by'] = session('user_id');
            $postModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            $postModel->update($id, $data);
            $categoriesTransModel = new CategoriesTransModel();
            $cate = $this->request->getPost('val_post_categories');
            $arr = [];
            foreach ($cate as $key => $value) {
                $trans = [];
                $trans['id'] = sprintf('%05d', $id).sprintf('%05d', $value);
                $trans['categories_id'] = $value;
                $trans['post_id'] = $id;
                array_push($arr, $trans);
            }
            if (!empty($arr)) {
                $categoriesTransModel->where('post_id', $id)->delete();
                $categoriesTransModel->insertBatch($arr);
            }
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $postModel = new PostModel();
        $query = $postModel->select("post_id, post_url, post_title, post_desc, post_image")->find($id);
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
