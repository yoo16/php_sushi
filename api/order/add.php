<?php   
require_once '../../app.php';

use App\Models\Product;
use App\Models\Category;
use App\Models\Seat;
use App\Models\Order;

$orderModel = new Order();
$orderModel->add($_POST);

echo json_encode([
    'status' => 'success',
    'products' => $products,
    'categories' => $categories
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);