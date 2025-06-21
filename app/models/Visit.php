<?php

namespace App\Models;

use PDO;
use PDOException;
use Database;

class Visit
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * 新しい来店セッションを作成する
     *
     * @param int $seatId
     * @return int|null 作成された visit ID、失敗時は null
     */
    public function create(int $seatId): ?int
    {
        try {
            // まだ「未会計」のセッションが存在するかチェック
            $sql = "SELECT id FROM visits WHERE seat_id = :seat_id AND status != 'paid' LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['seat_id' => $seatId]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                return (int)$existing['id']; // 既存のセッションを使う
            }

            // 新規セッション作成
            $sql = "INSERT INTO visits (seat_id, status) VALUES (:seat_id, 'seated')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['seat_id' => $seatId]);

            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}
