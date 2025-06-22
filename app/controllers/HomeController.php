<?php
namespace App\Controllers;

use App\Models\Seat;
use App\Models\Visit;

class HomeController
{
    public function __construct() {}

    public function top()
    {
        $seatModel = new Seat();
        $seats = $seatModel->fetch();
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
        $existingVisit = $visitModel->exists($seatId);
        if ($existingVisit) {
            // 既に「未会計」のセッションが存在する場合
            $visitId = $existingVisit['id'];
        } else {
            $visitId = $visitModel->create($seatId);
        }
        // セッションに visit_id と seat_id を保存
        $_SESSION['visit_id'] = $visitId;
        $_SESSION['seat_id'] = $seatId;
        header("Location: menu.php");
    }

    public function menu()
    {
        $visit_id = $_SESSION['visit_id'];
        $seat_id = $_SESSION['seat_id'];

        $visitModel = new Visit();
        $visit = $visitModel->find($visit_id);

        $seatModel = new Seat();
        $seat = $seatModel->find($visit['seat_id']);
        require APP_DIR . 'views/home/menu.php';
    }
}
