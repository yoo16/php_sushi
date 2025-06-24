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
        $categories = $this->categoryModel->fetch();

        require __DIR__ . '/../../views/admin/product/create.php';
    }

    /**
     * 商品の登録処理
     * 
     * @return void
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $product = $this->productModel->find($id);
        if (!$product) {
            header("Location: ./");
            exit;
        }
        $categories = $this->categoryModel->fetch();

        require __DIR__ . '/../../views/admin/product/edit.php';
    }

    /**
     * 商品登録の実行
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit;
        }
        $posts = $_POST;
        $id = $this->productModel->insert($posts);
        if ($id) {
            header("Location: index.php");
            exit;
        } else {
            echo "登録に失敗しました";
        }
    }

    /**
     * 商品更新の実行
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit;
        }
        $posts = $_POST;
        $result = $this->productModel->update($posts);
        if ($result) {
            header("Location: ./");
            exit;
        } else {
            echo "更新に失敗しました";
        }
    }
}
