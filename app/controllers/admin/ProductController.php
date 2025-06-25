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
    }

    /**
     * 商品一覧の表示
     * 
     * @return void
     */
    public function index()
    {
        $productModel = new Product();
        $products = $productModel->fetch();

        $categoryModel = new Category();
        $category_names = $categoryModel->map();

        require VIEW_DIR . 'admin/product/index.php';
    }

    /**
     * 商品の登録処理
     * 
     * @return void
     */
    public function create()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->fetch();

        require VIEW_DIR . 'admin/product/create.php';
    }

    /**
     * 商品の登録処理
     * 
     * @return void
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $productModel = new Product();
        $product = $productModel->find($id);
        if (!$product) {
            header("Location: ./");
            exit;
        }
        $categoryModel = new Category();
        $categories = $categoryModel->fetch();

        require VIEW_DIR . 'admin/product/edit.php';
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
        $productModel = new Product();
        $id = $productModel->insert($posts);
        header("Location: index.php");
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
        $productModel = new Product();
        $result = $productModel->update($posts);
        if ($result) {
            header("Location: ./");
            exit;
        } else {
            header("Location: edit.php?id=" . $posts['id']);
            exit;
        }
    }


    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit;
        }
        $productModel = new Product();
        $productModel->delete($_POST['id']);
        header("Location: ./");
    }
}
