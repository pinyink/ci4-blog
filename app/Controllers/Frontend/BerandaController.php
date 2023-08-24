<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Libraries\Tema;
use App\Models\Blog\PostModel;

class BerandaController extends BaseController
{
    public function index()
    {
        $page = 1;

        $postModel = new PostModel();
        $queryPost = $postModel->select('*, (select group_concat(c.categories_desc SEPARATOR "|") from categories_trans b 
        left join categories c on b.categories_id = c.categories_id where b.post_id = post.post_id group by b.post_id) cate')->join('profil', 'profil.user_id = post_created_by', 'left')->paginate(8, 'default', $page);

        $html = "";
        foreach ($queryPost as $key => $value) {
            $image = $value['post_image'] != null ? base_url($value['post_image']) : base_url('assets/admincast/dist/assets/img/file.png');
            $categories = "";
            $explodeCate = explode("|", $value['cate']);
            if (!empty($explodeCate)) {
                $categories = "<div class=\"div-categories\">";
                foreach ($explodeCate as $kCate => $vCate) {
                    $categories .= "<a href=\"#\" class=\"categories\">".$vCate."</a>";
                }
                $categories .= "</div>";
            }
            $html .= "<div class=\"box\">
                <img src=\"".$image."\" alt=\"\">
                <div class=\"box-body\">
                    <div class=\"user-date\">
                        <span>
                            <i class=\"fa fa-user-alt\"></i> ".$value['profil_firstname']." ".$value['profil_lastname']."
                        </span>
                        <span>
                            <i class=\"fa fa-clock\"></i> ".date('d-M-Y', strtotime($value['post_created_at']))."
                        </span>
                    </div>
                    <div class=\"div-title\">
                        <a href=\"#\" class=\"title\">".$value['post_title']."</a>
                        <a href=\"#\" target=\"_blank\"><i class=\"fa fa-external-link\"></i></a>
                    </div>
                    <div class=\"description\">
                        ".$value['post_desc']."
                    </div>
                    ".$categories."
                </div>
            </div>";
        }

        $pager = $postModel->pager;
        $pager_links = $pager->links('default', 'pager_blog');

        $data = [
            'post' => $html ,
            'pager' => $pager_links
        ];
        $tema = new Tema();
        $tema->loadTema('frontend/beranda', $data);
    }
}
