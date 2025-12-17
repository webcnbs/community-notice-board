<?php
// models/Attachment.php
require_once __DIR__ . '/../includes/database.php';

class Attachment {
    private $pdo;
    public function __construct() { 
        $this->pdo = Database::getInstance()->pdo(); 
    }

    public function add(int $noticeId, string $filename, string $filepath) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO attachments (notice_id, filename, filepath) VALUES (?, ?, ?)"
        );
        $stmt->execute([$noticeId, $filename, $filepath]);
        return $this->pdo->lastInsertId();
    }

    public function listByNotice(int $noticeId) {
        $stmt = $this->pdo->prepare("SELECT * FROM attachments WHERE notice_id=? ORDER BY uploaded_at DESC");
        $stmt->execute([$noticeId]);
        return $stmt->fetchAll();
    }

    public function delete(int $id) {
        $stmt = $this->pdo->prepare("DELETE FROM attachments WHERE attachment_id=?");
        $stmt->execute([$id]);
    }
}