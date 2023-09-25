<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\Blog\PostBodyModel;
use App\Models\Blog\PostModel;

class ArticleController extends BaseController
{
    private $postModel;
    private $postBodyModel;
    private $tema;

    public function __construct() {
        $this->postModel = new PostModel();
        $this->tema = new Tema();
        $this->postBodyModel = new PostBodyModel();
    }

    public function index($keyword)
    {
        $query = $this->postModel
        ->join('profil', 'profil.user_id = post_created_by', 'left')
        ->where(['post_url' => $keyword])->first();
        if (empty($query)) {
            return redirect('404');
        } else {
            $body = $this->postBodyModel->where(['post_id' => $query['post_id']])->orderBy('post_body_order', 'asc')->findAll();
            $recent = $this->postModel->limit(2)->orderBy('post_id', 'desc')->findAll();
            $this->tema->loadTema('frontend/article', ['detail' => $query, 'body' => $body, 'recent' => $recent]);
        }
    }
}
