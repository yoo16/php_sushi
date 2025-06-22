<?php   
require_once '../../app.php';

use App\Models\Order;

$vidit = $_GET['visit_id'] ?? null;
if (!$visit) {
    http_response_code(400);
    echo json_encode(["error" => "Missing visit_id"]);
    exit;
}

$orderModel = new Order();
$orders = $orderModel->fetchByVisitId($visit);

echo json_encode([
    'status' => 'success',
    'products' => $products,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);