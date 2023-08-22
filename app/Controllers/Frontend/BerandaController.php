<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Libraries\Tema;

class BerandaController extends BaseController
{
    public function index()
    {
        $tema = new Tema();
        $tema->loadTema('frontend/beranda');
    }
}
