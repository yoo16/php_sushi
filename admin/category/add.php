<?php
require_once __DIR__ . '/../../app.php';

use App\Models\Category;

$categoryModel = new Category();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $price = $_POST['price'] ?? '';
    $image_path = '';

    // アップロード処理
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'images/products/';
        $filename = uniqid() . "_" . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image_path = $targetPath;
        } else {
            echo "画像のアップロードに失敗しました。";
            exit;
        }
    }

    $data = [
        'name' => $name,
        'category_id' => $category_id,
        'image_path' => $image_path,
    ];

    $id = $categoryModel->insert($data);
    if ($id) {
        header("Location: admin_product_form.php?success=1");
        exit;
    } else {
        echo "登録に失敗しました";
    }
}
