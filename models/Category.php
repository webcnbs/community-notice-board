<?php
// models/Category.php
require_once __DIR__ . '/../includes/database.php';

class Category {
    private $pdo;
    public function __construct() { $this->pdo = Database::getInstance()->pdo(); }

    public function all() {
        return $this->pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    }

    public function create(string $name, string $description, string $color) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, description, color_code) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $color]);
    }

    public function delete(int $id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE category_id=?");
        $stmt->execute([$id]);
    }
}