<?php

namespace App\Models;

use PDO;
use PDOException;
use Database;
use File;

class Product
{
    public $pdo;

    /**
     * コンストラクタ
     *
     * インスタンス生成時にプロパティ等の初期化が必要であれば行います。
     */
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * データ取得
     *
     * @return array|null 投稿データの連想配列、もしくは該当する投稿がなければ null
     */
    public function get($limit = 50)
    {
        try {
            $sql = "SELECT * FROM products LIMIT :limit;";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['limit' => $limit]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo ($e->getMessage());
            return null;
        }
    }

    /**
     * カテゴリーで検索して取得
     *
     * @param int $category_id カテゴリーID
     * @return array|null 
     */
    public function getByCategoryId($category_id, $limit = 50)
    {
        try {
            $sql = "SELECT * FROM products 
                JOIN categories ON products.category_id = categories.id
                ORDER BY categories.id ASC 
                WHERE products.category_id = :category_id
                LIMIT :limit;";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'category_id' => $category_id,
                'limit' => $limit
            ]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo ($e->getMessage());
            return null;
        }
    }

    /**
     * IDでデータ取得
     *
     * @param int $id ID
     * @return array|null 
     */
    public function find(int $id)
    {
        try {
            $sql = "SELECT * FROM products WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $value = $stmt->fetch(PDO::FETCH_ASSOC);
            return $value;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * データをDBに登録する
     *
     * @param int $user_id ユーザID
     * @param array $data 登録する投稿データ
     * @return mixed 登録成功時は投稿ID、失敗時は null
     */
    public function insert($data)
    {
        try {
            $data['image_path'] = $this->uploadImage();

            $sql = "INSERT INTO products (name, category_id, price, image_path) 
                    VALUES (:name, :category_id, :price, :image_path)";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($data);
            if ($result) {
                return $this->pdo->lastInsertId();
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
        return;
    }

    /**
     * データ削除
     *
     * @param int $id ID
     * @return mixed 
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
        return;
    }

    /**
     * アップロード画像を取得
     *
     * @param 
     * @return bool 成功した場合は画像ファイルパス、失敗した場合は null
     */
    public function uploadImage()
    {
        return File::upload(PRODUCTS_IMAGE_DIR);
    }
}
