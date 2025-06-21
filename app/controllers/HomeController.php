<?php

namespace App\Controllers;

class HomeController
{
    /**
     * 管理画面のトップページを表示
     *
     * @return void
     */
    public function top()
    {
        require APP_DIR . 'views/home/index.php';
    }

    public function menu()
    {
        if (isset($_GET['seat'])) {
            $_SESSION['seat'] = $_GET['seat'];
        }
        $seat = $_SESSION['seat'] ?? null;
        if (!$seat) {
            header('Location: ./');
            exit;
        }
        require APP_DIR . 'views/home/menu.php';
    }
}