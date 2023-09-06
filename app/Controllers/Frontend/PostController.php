<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\Blog\PostModel;

class PostController extends BaseController
{
    private $postModel;

    public function __construct(PostModel $postModel) {
        $this->postModel = $postModel;
    }

    public function index($keyword)
    {
        $query = $this->postModel
        ->join('profil', 'profil.user_id = post_created_by', 'left')
        ->where(['post_url' => $keyword])->first();
        if (empty($query)) {
            return redirect('404');
        } else {
            
        }
    }
}
