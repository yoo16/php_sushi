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

    public function find($id)
    {
        try {
            $sql = "SELECT * FROM visits WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function exists($seatId)
    {
        try {
            // まだ「未会計」のセッションが存在するかチェック
            $sql = "SELECT * FROM visits WHERE seat_id = :seat_id AND status != 'paid'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['seat_id' => $seatId]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            return $existing;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * 新しい来店セッションを作成する
     *
     * @param int $seatId
     * @return int|null 作成された visit ID、失敗時は null
     */
    public function create($seatId)
    {
        try {
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
