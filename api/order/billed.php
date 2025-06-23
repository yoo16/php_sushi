<?php
use App\Models\Order;
use App\Models\Visit;

$orderModel = new Order();
$total = $orderModel->total($visit_id);

// 会計済みのセッションを更新
$visitModel = new Visit();
$result = $visitModel->billed($visit_id, $total);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to update visit status"]);
    exit;
}
echo json_encode(["success" => true, "total" => $total]);
