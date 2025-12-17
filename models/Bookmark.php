<?php
// models/Bookmark.php
require_once __DIR__ . '/../includes/database.php';

class Bookmark {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->pdo();
    }

    // ✅ Add a bookmark (ignore duplicates)
    public function add(int $userId, int $noticeId) {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO bookmarks (user_id, notice_id) VALUES (?, ?)");
        $stmt->execute([$userId, $noticeId]);
    }

    // ✅ Remove a bookmark
    public function remove(int $userId, int $noticeId) {
        $stmt = $this->pdo->prepare("DELETE FROM bookmarks WHERE user_id = ? AND notice_id = ?");
        $stmt->execute([$userId, $noticeId]);
    }

    // ✅ List all bookmarks for a user
    public function list(int $userId): array {
        $stmt = $this->pdo->prepare("
            SELECT b.notice_id, b.created_at, n.title
            FROM bookmarks b
            JOIN notices n ON b.notice_id = n.notice_id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Check if a bookmark exists
    public function exists(int $userId, int $noticeId): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM bookmarks WHERE user_id = ? AND notice_id = ?");
        $stmt->execute([$userId, $noticeId]);
        return $stmt->fetchColumn() > 0;
    }
}