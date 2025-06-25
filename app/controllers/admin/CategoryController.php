<?php

namespace App\Controllers\Admin;

require_once __DIR__ . '/../../../app.php';

use App\Models\Category;

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {

    }

    public function index()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->fetch();
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

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit;
        }
        $posts = $_POST;
        $categoryModel = new Category();
        $result = $categoryModel->update($posts);
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
        $categoryModel = new Category();
        $categoryModel->delete($_POST['id']);
        header("Location: ./");
    }
}
