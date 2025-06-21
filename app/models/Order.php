<?php

namespace App\Models;

use PDO;
use PDOException;
use Database;

class Order
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
     * 注文を追加
     *
     * @param array $orderData 注文データの連想配列
     * @return bool 成功した場合は true、失敗した場合は false
     */
    public function add($data)
    {
        try {
            $sql = "INSERT INTO orders (visit_id, product_id, price, quantity) 
                    VALUES (:visit_id, :product_id, :price, :quantity)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
