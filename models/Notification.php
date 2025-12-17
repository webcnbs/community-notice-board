<?php
// models/Notification.php
require_once __DIR__ . '/../includes/database.php';

class Notification {
    private $pdo;
    public function __construct() { $this->pdo = Database::getInstance()->pdo(); }

    public function create(int $userId, string $message) {
        $stmt = $this->pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$userId, $message]);
    }

    public function unread(int $userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM notifications WHERE user_id=? AND is_read=0 ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function markRead(int $id) {
        $stmt = $this->pdo->prepare("UPDATE notifications SET is_read=1 WHERE notification_id=?");
        $stmt->execute([$id]);
    }
}