<?php

namespace App\Controllers;

use App\Models\Seat;
use App\Models\Visit;

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
        $seatId = $_POST['seat_id'] ?? null;
        if (!isset($seatId)) {
            header('Location: top.php');
            exit;
        }

        $visitModel = new Visit();
        $visitId = $visitModel->create($seatId);

        if ($visitId) {
            // 成功時
            // セッションに訪問IDと座席IDを保存
            $_SESSION['visit_id'] = $visitId;
            $_SESSION['seat_id'] = $seatId;
            // 次のページに遷移
            header("Location: menu.php?visit_id={$visitId}");
            exit;
        } else {
            header('Location: top.php');
            exit;
        }
    }

    public function menu()
    {
        $visit_id = $_SESSION['visit_id'] ?? null;
        $seat_id = $_SESSION['seat_id'] ?? null;
        if (!isset($visit_id) || !isset($seat_id)) {
            header('Location: ./');
            exit;
        }
        $seatModel = new Seat();
        $seat = $seatModel->find($seat_id);
        require APP_DIR . 'views/home/menu.php';
    }
}
