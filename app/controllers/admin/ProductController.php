<?php
namespace App\Controllers\Admin;

require_once __DIR__ . '/../../../app.php';

use App\Models\Product;
use App\Models\Category;

class ProductController
{
    public function __construct() {}

    /**
     * 商品一覧の表示
     * 
     * @return void
     */
    public function index()
    {
        // Modelで商品情報を取得
        $productModel = new Product();
        if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
            // GETパラメータに category_id があれば、該当商品のみ取得
            $category_id = (int) $_GET['category_id'];
            $products = $productModel->fetchByCategoryId($category_id);
        } else {
            // 全件取得
            $products = $productModel->fetch();
        }

        // Modelでカテゴリ情報を取得
        $categoryModel = new Category();
        $category_names = $categoryModel->map();

        // Viewの表示
        require VIEW_DIR . 'admin/product/index.php';
    }

    /**
     * 商品の登録処理
     * 
     * @return void
     */
    public function create()
    {
        // Modelでカテゴリ情報を取得
        $categoryModel = new Category();
        $categories = $categoryModel->fetch();

        // Viewの表示
        require VIEW_DIR . 'admin/product/create.php';
    }

    /**
     * 商品の登録処理
     * 
     * @return void
     */
    public function edit()
    {
        // GETリクエストでidを取得
        $id = $_GET['id'] ?? null;

        // Modelで商品情報を取得
        $productModel = new Product();
        $product = $productModel->find($id);

        // 商品が存在しない場合はトップページへリダイレクト
        if (!$product) {
            header("Location: ./");
            exit;
        }

        // Modelでカテゴリ情報を取得
        $categoryModel = new Category();
        $categories = $categoryModel->fetch();

        // Viewの表示
        require VIEW_DIR . 'admin/product/edit.php';
    }

    /**
     * 商品登録の実行
     */
    public function add()
    {
        // POSTリクエストでなければ終了
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit;
        }

        // Modelで商品情報を登録
        $productModel = new Product();
        $id = $productModel->insert($_POST);

        // 商品トップへリダイレクト
        header("Location: ./");
    }

    /**
     * 商品更新の実行
     */
    public function update()
    {
        // POSTリクエストでなければ終了
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit;
        }

        // Modelで商品情報を更新
        $productModel = new Product();
        $result = $productModel->update($_POST);

        if ($result) {
            // 更新に成功した場合は一覧画面へリダイレクト
            header("Location: ./");
            exit;
        } else {
            // 更新に失敗した場合は編集画面へリダイレクト
            header("Location: edit.php?id=" . $_POST['id']);
            exit;
        }
    }

    public function delete()
    {
        // POSTリクエストでなければ終了
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit;
        }
        // Modelで商品情報を削除
        $productModel = new Product();
        $productModel->delete($_POST['id']);

        // 商品トップへリダイレクト
        header("Location: ./");
    }
}
