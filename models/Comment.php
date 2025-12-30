<?php
require_once __DIR__ . '/../includes/database.php';

class Comment {
    private $pdo;
    public function __construct() {
        $this->pdo = Database::getInstance()->pdo();
    }

    // Create a new comment (defaults to pending)
    public function create(int $noticeId, int $userId, string $content, ?string $imagePath = null) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO comments (notice_id, user_id, content, status, created_at) 
             VALUES (?, ?, ?, ?, 'pending', NOW())"
        );
        $stmt->execute([$noticeId, $userId, $content, $imagePath]);
    }

    // List approved comments for a notice
    public function listApproved(int $noticeId) {
        $stmt = $this->pdo->prepare(
            "SELECT c.comment_id, c.notice_id, c.user_id, c.content,
                    c.status, c.created_at, u.username
             FROM comments c
             JOIN users u ON c.user_id = u.user_id
             WHERE c.notice_id = ? AND c.status = 'approved'
             ORDER BY c.created_at DESC"
        );
        $stmt->execute([$noticeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Moderate a comment (approve/reject/pending)
    public function moderate(int $commentId, string $status) {
        $stmt = $this->pdo->prepare(
            "UPDATE comments SET status = ? WHERE comment_id = ?"
        );
        $stmt->execute([$status, $commentId]);
    }

    public function allPending() {
        $stmt = $this->pdo->prepare(
            "SELECT c.comment_id, c.notice_id, c.user_id, c.content, c.created_at,
                    u.username, n.title AS notice_title
         FROM comments c
         JOIN users u ON c.user_id = u.user_id
         JOIN notices n ON c.notice_id = n.notice_id
         WHERE c.status = 'pending'
         ORDER BY c.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}