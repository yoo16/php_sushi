<?php
require_once '../../app.php';

use App\Models\Category;

$categoryModel = new Category();
$categories = $categoryModel->get();

echo json_encode([
    'status' => 'success',
    'data' => $categories
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
