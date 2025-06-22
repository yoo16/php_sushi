<?php
namespace App\Controllers\Admin;

require_once __DIR__ . '/../../../app.php';

use App\Models\Category;

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    public function index()
    {
        $categories = $this->categoryModel->fetch();
        require __DIR__ . '/../../views/admin/category/index.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $data = ['name' => $name];
            $id = $this->categoryModel->insert($data);
            if ($id) {
                header("Location: index.php?success=1");
                exit;
            } else {
                echo "登録に失敗しました";
            }
        }
        require __DIR__ . '/../../views/admin/category/create.php';
    }
}
