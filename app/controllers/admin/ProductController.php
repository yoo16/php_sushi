<?php
namespace App\Controllers\Admin;

require_once __DIR__ . '/../../../app.php';

use App\Models\Product;
use App\Models\Category;

class ProductController
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    /**
     * 商品一覧の表示
     * 
     * @return void
     */
    public function index()
    {
        $products = $this->productModel->fetch();
        $category_names = $this->categoryModel->map();

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