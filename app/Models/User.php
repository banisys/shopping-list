<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{

    public $id;

    public $name;

    public $mobile;

    public $password;

    public $created_at;

    public static function findByMobile(string $mobile)
    {
        $stmt = self::db()->prepare("SELECT * FROM users WHERE mobile = :mobile LIMIT 1");
        $stmt->bindParam(':mobile', $mobile);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $item = new self();
            $item->id = $data['id'];
            $item->name = $data['name'];
            $item->mobile = $data['mobile'];
            $item->password = $data['password'];
            $item->created_at = $data['created_at'];
            return $item;
        }

        return null;
    }

    public static function findById(int $id): ?self
    {
        $stmt = self::db()->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $user = new self();
            foreach ($data as $key => $value) {
                if (property_exists($user, $key)) {
                    $user->$key = $value;
                }
            }
            return $user;
        }

        return null;
    }

    public function save(): bool
    {
        $db = self::db();

        $stmt = $db->prepare("
        INSERT INTO users (name, mobile, password, created_at)
        VALUES (:name, :mobile, :password, :created_at)
        ");

        $result = $stmt->execute([
            ':name' => $this->name,
            ':mobile' => $this->mobile,
            ':password' => $this->password,
            ':created_at' => $this->created_at,
        ]);

        if ($result) {
            $this->id = $db->lastInsertId();
            return true;
        }

        return false;
    }
}
