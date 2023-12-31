<?php

namespace App\Controllers\Blog;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use CodeIgniter\Database\RawSql;
use App\Models\Blog\FileManagerModel;


class FileManagerController extends BaseController
{
    private $tema;

    function __construct()
    {
        helper(['form']);
        $this->tema = new Tema();
    }

    public function index()
    {
        $this->tema->setJudul('File Manager');
        $this->tema->loadTema('/blog/filemanager');
    }

    public function ajaxList()
    {
        $fileManagerModel = new FileManagerModel();
        $fileManagerModel->setRequest($this->request);
        $lists = $fileManagerModel->getDatatables();
        $data = [];
        $no = $this->request->getPost("start");
        foreach ($lists as $list) {
            $no++;
            $row = [];
            $id = $list->files_id;
            $aksi = '<a href="javascript:;" class="" data-toggle="tooltip" data-placement="top" title="Lihat Data" onclick="lihat_data('.$id.')"><i class="fa fa-search"></i></a>';
            if(enforce(2, 3)) {
                $aksi .= '<a href="javascript:;" class="ml-2" data-toggle="tooltip" data-placement="top" title="Edit Data" onclick="edit_data('.$id.')"><i class="fa fa-edit"></i></a>';
            }

            if(enforce(2, 4)) {
                $aksi .= '<a href="javascript:;" class="text-danger ml-2" data-toggle="tooltip" data-placement="top" title="Delete Data" onclick="delete_data('.$id.')"><i class="fa fa-trash"></i></a>';
            }
            $action = $aksi;
            
            $row[] = $action;
            $row[] = $no;
			$row[] = $list->files_name;
			$row[] = $list->files_desc;
			$row[] = $list->files_path;
            $data[] = $row;
        }
        $output = [
                "draw" => $this->request->getPost('draw'),
                "recordsTotal" => $fileManagerModel->countAll(),
                "recordsFiltered" => $fileManagerModel->countFiltered(),
                "data" => $data
            ];
        echo json_encode($output);
    }

    public function getList()
    {
        $fileManagerModel = new FileManagerModel();
        $limit = 10;
        $id = $this->request->getPost('id');
        $search = $this->request->getPost('search');
        // $query = $fileManagerModel->findAll($limit);
        if ($search == null) {
            $query = $fileManagerModel->paginate($limit, 'default', $id);
        } else {
            $query = $fileManagerModel->like('files_name', $search)->paginate($limit, 'default', $id);
        }
        $html = "";
        foreach ($query as $key => $value) {
            $img = in_array($value['files_ext'], ['jpg', 'jpeg', 'png']) ? base_url($value['files_path'].'/'.$value['files_file']) : base_url('assets/admincast/dist/assets/img/file.png');
            $html .= "<div class=\"col-md-3 col-xs-4\">
            <div class=\"card mb-3\">
                <img class=\"card-img-top\" src=\"".$img."\" style=\"max-height: 320px\"/>
                <div class=\"card-body\">
                    <h4 class=\"card-title\">".$value['files_name']."</h4>
                    <div>".$value['files_desc']."</div>
                </div>
                <div class=\"card-footer\">
                    <button class=\"btn btn-default btn-sm\" onclick=\"edit_data(".$value['files_id'].")\"><i class=\"fa fa-edit\"></i></button>
                    <button class=\"btn btn-danger btn-sm\" onclick=\"delete_data(".$value['files_id'].")\"><i class=\"fa fa-trash\"></i></button>
                    <span class=\"pull-right text-muted font-13\"><?=date('Y-m-d H:i:s')?></span>
                </div>
            </div>
        </div>";
        }
        
        $pager = $fileManagerModel->pager;
        $pager_links = $pager->links('default', 'front_full');

        return $this->response->setJSON(['html' => $html, 'pager' => $pager_links]);
    }

    public function saveData()
    {
        $fileManagerModel = new FileManagerModel();

        $method = $this->request->getPost('method');
        
		$imgfiles_path = $this->request->getFile('val_files_path');

        $validation = [
            'val_files_name' => 'required','val_files_desc' => 'required',
        ];

        
		if (!empty($_FILES['val_files_path']['name'])) {
			$validation['val_files_path'] = 'uploaded[val_files_path]'
			. '|is_image[val_files_path]'
			. '|mime_in[val_files_path,image/jpg,image/jpeg,image/gif,image/png,image/webp]'
			. '|max_size[val_files_path,2048]';
		}
        $validated = $this->validate($validation);
        if ($validated === false) {
            $errors = $this->validator->getErrors();
            $message = '<ul>';
            foreach ($errors as $key => $value) {
                $message .= '<li>'.$value.'</li>';
            }
            
			if (!empty($_FILES['val_files_path']['name'])) {
				$type = $imgfiles_path->getClientMimeType();
				$message .= '<li>'.$imgfiles_path->getErrorString() . '(' . $imgfiles_path->getError() . ' Type File ' . $type . ' )</li>';
			}
            $message .= '</ul>';

            $log['errorCode'] = 2;
            $log['errorMessage'] = $message;
            return $this->response->setJSON($log);
        }

        $id = $this->request->getPost('files_id');
		$data['files_name'] = $this->request->getPost('val_files_name');
		$data['files_desc'] = $this->request->getPost('val_files_desc');
        $data['files_file'] = $this->request->getPost('val_files_file');

        if ($method != 'save') {
            $oldQuery = $fileManagerModel->find($id);
        }

		if (!empty($_FILES['val_files_path']['name'])) {
			$th = date('Y') . '/' . date('m').'/'.date('d');
			$path = 'uploads/blog/filemanager/';
			$_dir = $path . $th;
			$dir = ROOTPATH.'public/' . $path . $th;
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			$newName = $data['files_file'];
			$imgfiles_path->move($dir, $newName);
			$data['files_path'] = $_dir;
            
            $file = new \CodeIgniter\Files\File(ROOTPATH.'public/'.$_dir.'/'.$newName);
            $data['files_size'] = $file->getSizeByUnit('kb');
            $data['files_mime'] = $file->getMimeType();
            $data['files_ext'] = $file->guessExtension();
            
            if (!empty($oldQuery['files_path'])) {
                unlink(ROOTPATH.'public/'.$oldQuery['files_path'].'/'.$data['files_file']);
            }
		}

        if ($method == 'save') {
            $fileManagerModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
            if (!file_exists(ROOTPATH.'public/'.$oldQuery['files_path'].'/'. $data['files_file'])) {
                $file = new \CodeIgniter\Files\File(ROOTPATH.'public/'.$oldQuery['files_path'].'/'.$oldQuery['files_file']);
                $file->move(ROOTPATH.'public/'.$oldQuery['files_path'].'/', $data['files_file']);
            }
            $fileManagerModel->update($id, $data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Update Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        }
    }

    public function getData($id)
    {
        $fileManagerModel = new FileManagerModel();
        $query = $fileManagerModel->select("files_id, files_name, files_desc, files_path, files_file")->find($id);
        $query['path'] = base_url($query['files_path']);
        return $this->response->setJSON($query);
    }

    public function deleteData($id)
    {
        $fileManagerModel = new FileManagerModel();
        $oldQuery = $fileManagerModel->find($id);
        if (!empty($oldQuery['files_path'])) {
            unlink(ROOTPATH.'public/'.$oldQuery['files_path']);
        }

        $query = $fileManagerModel->delete($id);
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

    public function filesnameExist()
    {
        $fileManagerModel = new FileManagerModel();
        $files_id = $this->request->getPost('files_id');
        $files_name = $this->request->getPost('files_name');
        $query = $fileManagerModel->where(['files_id !=' => $files_id, 'files_name' => $files_name])->first();
        if (!empty($query)) {
            return $this->response->setJSON(false);
        }
        return $this->response->setJSON(true);
    }
}
