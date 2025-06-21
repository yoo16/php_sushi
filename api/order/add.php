<?php

require_once '../../app.php';

use App\Models\Order;

header("Content-Type: application/json");

// POST 以外は拒否
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     http_response_code(405);
//     echo json_encode(["error" => "Method Not Allowed"]);
//     exit;
// }

// JSONデータを取得してデコード
$input = json_decode(file_get_contents("php://input"), true);
$productId = $input['product_id'] ?? null;
$quantity = $input['quantity'] ?? null;
$visitId = $input['visit_id'] ?? null;
$price = $input['price'] ?? null;

if (!$productId || !$quantity || !$visitId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$data = [
    'visit_id'   => $visitId,
    'product_id' => $productId,
    'price'   => $price,
    'quantity'   => $quantity,
];

$orderModel = new Order();
$id = $orderModel->add($data);

if (!$id) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to add order"]);
    exit;
}
echo json_encode(["success" => true, "data" => $data]);