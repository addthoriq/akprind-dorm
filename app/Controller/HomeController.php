<?php

namespace Kang\AkprindDorm\Controller;

use Kang\AkprindDorm\App\View;

class HomeController
{

    function index(): void
    {
        $model = [
            "title" => "Belajar PHP MVC",
            "content" => "Selamat Belajar PHP MVC dari Programmer Zaman Now"
        ];

        View::render('Home/index', $model);
    }

}
