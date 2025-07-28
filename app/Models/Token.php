<?php

namespace App\Models;

use App\Core\Model;
use PDO;
use DateTime;

class Token extends Model
{
    public $id;
    public $user_id;
    public $token;
    public $created_at;
    public $expires_at;

    public static function create(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $now = (new DateTime())->format('Y-m-d H:i:s');
        $expiresAt = (new DateTime('+7 days'))->format('Y-m-d H:i:s');

        $stmt = self::db()->prepare("
            INSERT INTO tokens (user_id, token, created_at, expires_at)
            VALUES (:user_id, :token, :created_at, :expires_at)
        ");

        $stmt->execute([
            ':user_id' => $userId,
            ':token' => $token,
            ':created_at' => $now,
            ':expires_at' => $expiresAt,
        ]);

        return $token;
    }

    public static function findValid(string $tokenValue): ?self
    {
        $stmt = self::db()->prepare("
        SELECT * FROM tokens WHERE token = :token AND expires_at > NOW()");
        $stmt->bindParam(':token', $tokenValue);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $t = new self();
            foreach ($data as $key => $value) {
                $t->$key = $value;
            }
            return $t;
        }

        return null;
    }
}
