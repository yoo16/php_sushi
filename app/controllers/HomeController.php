<?php

namespace App\Controllers;

use App\Models\Seat;
use App\Models\Visit;
use App\Models\Order;
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
        // セッションに visit_id を保存
        $_SESSION['visit_id'] = $visitId;
        header("Location: menu.php");
    }

    public function menu()
    {
        $visit_id = $_SESSION['visit_id'] ?? null;
        if (!$visit_id) {
            header("Location: top.php");
            exit;
        }

        $visitModel = new Visit();
        $visit = $visitModel->find($visit_id);

        $seatModel = new Seat();
        $seat = $seatModel->find($visit['seat_id']);
        require APP_DIR . 'views/home/menu.php';
    }

    public function checkout()
    {
        $visit_id = $_SESSION['visit_id'] ?? null;
        $seat_id = $_SESSION['seat_id'] ?? null;

        if (!$visit_id || !$seat_id) {
            header("Location: top.php");
            exit;
        }

        $visitModel = new Visit();
        $visit = $visitModel->find($visit_id);

        $seatModel = new Seat();
        $seat = $seatModel->find($seat_id);

        $orderModel = new Order();
        $orders = $orderModel->fetchByVisitId($visit_id);

        $total = array_reduce($orders, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);
        $total_with_tax = round($total * TAX_RATE);

        require APP_DIR . 'views/home/checkout.php';
    }

    public function billed()
    {
        $visit_id = $_SESSION['visit_id'] ?? null;

        if (!$visit_id) {
            header("Location: menu.php");
            exit;
        }

        $orderModel = new Order();
        $total = $orderModel->total($visit_id);

        // 会計済みのセッションを更新
        $visitModel = new Visit();
        if ($visitModel->billed($visit_id, $total)) {
            header("Location: complete.php");
        } else {
            // エラー処理
            header("Location: checkout.php");
        }
    }

    public function complete()
    {
        // 完了画面を表示
        unset($_SESSION['visit_id']);
        require APP_DIR . 'views/home/complete.php';
    }

}
