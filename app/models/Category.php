<?php

namespace App\Models;

use PDO;
use PDOException;
use Database;

class Category
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
    public function get()
    {
        try {
            $sql = "SELECT * FROM categories ORDER BY id ASC;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $values;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo ($e->getMessage());
            return null;
        }
    }

    public function map($key = "id", $column = "name")
    {
        $categories = $this->get();
        $map = array_column($categories, $column, $key);
        return $map;
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
            $sql = "SELECT * FROM categories WHERE id = :id";
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
            $sql = "INSERT INTO categories (name, category_id, image_path) 
                    VALUES (:name, :category_id, :image_path)";

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
            $sql = "DELETE FROM categories WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
        return;
    }
}
