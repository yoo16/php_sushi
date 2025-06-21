<?php

namespace App\Controllers;

class HomeController
{
    /**
     * 管理画面のトップページを表示
     *
     * @return void
     */
    public function index()
    {
        // ビューを読み込む
        require APP_DIR . 'views/home/index.php';
    }

    public function menu()
    {
        // ビューを読み込む
        require APP_DIR . 'views/home/menu.php';
    }

}