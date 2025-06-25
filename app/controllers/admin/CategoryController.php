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
        require VIEW_DIR . 'admin/category/index.php';
    }

    public function create()
    {
        require VIEW_DIR . 'admin/category/create.php';
    }

    public function edit()
    {
        $categoryModel = new Category();
        if (!isset($_GET['id'])) {
            exit;
        }
        $id = $_GET['id'];
        $category = $categoryModel->find($id);
        require VIEW_DIR . 'admin/category/edit.php';
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit;
        }
        $posts = $_POST;
        $categoryModel = new Category();
        $id = $categoryModel->insert($posts);
        if ($id) {
            header("Location: ./");
            exit;
        } else {
            header("Location: create.php");
            exit;
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit;
        }
        $this->categoryModel->delete($_POST['id']);
        header("Location: ./");
    }
}
