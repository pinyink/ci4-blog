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

    public function getList(int $id = 1)
    {
        $fileManagerModel = new FileManagerModel();
        $limit = 10;
        $query = $fileManagerModel->findAll($limit);
        $html = "";
        foreach ($query as $key => $value) {
            $img = in_array($value['files_ext'], ['jpg', 'jpeg', 'png']) ? base_url($value['files_path']) : base_url('assets/admincast/dist/assets/img/file.png');
            $html .= "<div class=\"col-md-2\">
            <div class=\"card\">
                <img class=\"card-img-top\" src=\"".$img."\" />
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

        $pager = \Config\Services::pager();
        $page    = (int) $id;
        $perPage = $limit;
        $total   = $fileManagerModel->countAllResults();

        // Call makeLinks() to make pagination links.
        $pager_links = $pager->makeLinks($page, $perPage, $total);

        return $this->response->setJSON(['html' => $html, 'pager' => $pager_links]);
    }

    public function saveData()
    {
        $fileManagerModel = new FileManagerModel();

        $method = $this->request->getPost('method');
        
		$imgfiles_path = $this->request->getFile('val_files_path');

        $validation = [
            'val_files_name' => 'required','val_files_desc' => 'required','val_files_path' => 'required',
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
		if (!empty($_FILES['val_files_path']['name'])) {
			$th = date('Y') . '/' . date('m').'/'.date('d');
			$path = 'uploads/blog/filemanager/';
			$_dir = $path . $th;
			$dir = ROOTPATH.'public/' . $path . $th;
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			$newName = $imgfiles_path->getRandomName();
			$imgfiles_path->move($dir, $newName);
			$data['files_path'] = $_dir.'/'.$newName;
            
            $file = new \CodeIgniter\Files\File(ROOTPATH.'public/'.$data['files_path']);
            $data['files_size'] = $file->getSizeByUnit('kb');
            $data['files_mime'] = $file->getMimeType();
            $data['files_ext'] = $file->guessExtension();
            if ($method != 'save') {
                $oldQuery = $fileManagerModel->find($id);
                if (!empty($oldQuery['files_path'])) {
                    unlink(ROOTPATH.'public/'.$oldQuery['files_path']);
                }
            }
		}

        if ($method == 'save') {
            $fileManagerModel->insert($data);
            $log['errorCode'] = 1;
            $log['errorMessage'] = 'Simpan Data Berhasil';
            $log['errorType'] = 'success';
            return $this->response->setJSON($log);
        } else {
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
        $query = $fileManagerModel->select("files_id, files_name, files_desc, files_path")->find($id);
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
