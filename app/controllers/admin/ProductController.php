<?php
namespace App\Controllers\Admin;

require_once __DIR__ . '/../../../app.php';

use App\Models\Product;

class ProductController
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function index()
    {
        $products = $this->productModel->get();
        require __DIR__ . '/../../views/admin/product/index.php';
    }

    /**
     * 商品の登録処理
     * 
     * @return void
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $category_id = $_POST['category_id'] ?? '';
            $price = $_POST['price'] ?? 0;
            $image = $_FILES['image'] ?? null;

            $data = [
                'name' => $name,
                'category_id' => $category_id,
                'price' => $price,
                'image' => $image
            ];

            $id = $this->productModel->insert($data);
            if ($id) {
                header("Location: index.php?success=1");
                exit;
            } else {
                echo "登録に失敗しました";
            }
        }

        require __DIR__ . '/../../views/admin/product/create.php';
    }
}