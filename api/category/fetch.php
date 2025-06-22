<?php
require_once '../../app.php';

use App\Models\Category;

$categoryModel = new Category();
$categories = $categoryModel->fetch();

echo json_encode([
    'status' => 'success',
    'categories' => $categories
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
