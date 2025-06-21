<?php   
require_once '../../app.php';

use App\Models\Product;
use App\Models\Category;

$categoryModel = new Category();
$categories = $categoryModel->get();

$productModel = new Product();
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

$products = $category_id > 0 ?
    $productModel->getByCategoryId($category_id, $limit) :
    $productModel->get($limit);

echo json_encode([
    'status' => 'success',
    'products' => $products,
    'categories' => $categories
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);