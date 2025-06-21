<?php

namespace App\Controllers;

use App\Models\Seat;

class HomeController
{
    public function top()
    {
        $seatModel = new Seat();
        $seats = $seatModel->get();

        require APP_DIR . 'views/home/top.php';
    }

    public function reserve()
    {
        // セッションに座席IDを保存
        $seat_id = $_POST['seat_id'] ?? null;
        if (!isset($seat_id)) {
            header('Location: top.php');
            exit;
        }

        $seatModel = new Seat();
        $seat = $seatModel->find($seat_id);
        if ($seatModel->reserved($seat_id)) {
            $_SESSION['seat'] = $seat;
            header('Location: menu.php?seat_id=' . $seat_id);
            exit;
        }

        header('Location: top.php');
        exit;
    }

    public function menu()
    {
        $seat = $_SESSION['seat'] ?? null;
        if (!isset($seat['id'])) {
            header('Location: ./');
            exit;
        }
        require APP_DIR . 'views/home/menu.php';
    }
}
