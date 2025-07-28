<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Item extends Model
{
    /**
     * @var int|null The unique identifier of the item.
     */
    public $id;

    /**
     * @var string|null The name of the item.
     */
    public $name;

    /**
     * @var bool|null The checked status of the item.
     */
    public $is_checked;

    /**
     * @var string|null The creation timestamp of the item.
     */
    public $created_at;

    /**
     * Retrieve all items from the database, ordered by creation date.
     *
     * @return array An array of Item objects.
     */
    public static function all(): array
    {
        $stmt = self::db()->query("SELECT * FROM items ORDER BY created_at DESC");

        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Find an item by its ID.
     *
     * @param int $id The ID of the item to find.
     * @return mixed The Item object if found, or false if not found.
     */
    public static function find(int $id): mixed
    {
        $stmt = self::db()->prepare("SELECT * FROM items WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);

        return $stmt->fetch();
    }

    /**
     * Save the item to the database (insert or update).
     *
     * @return void
     */
    public function save(): void
    {
        $isChecked = filter_var($this->is_checked, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        if (isset($this->id)) {
            $stmt = self::db()->prepare("UPDATE items SET name = ?, is_checked = ? WHERE id = ?");
            $stmt->execute([$this->name, $isChecked, $this->id]);
        } else {
            $stmt = self::db()->prepare("INSERT INTO items (name, is_checked) VALUES (?, ?)");
            $stmt->execute([$this->name, $isChecked]);
            $this->id = self::db()->lastInsertId();
        }
    }

    /**
     * Delete the item from the database.
     *
     * @return void
     */
    public function delete(): void
    {
        $stmt = self::db()->prepare("DELETE FROM items WHERE id = ?");
        $stmt->execute([$this->id]);
    }
}
