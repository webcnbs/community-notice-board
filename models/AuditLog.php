<?php
// models/AuditLog.php
require_once __DIR__ . '/../includes/database.php';

class AuditLog {
    private $pdo;

    public function __construct() { 
        $this->pdo = Database::getInstance()->pdo(); 
    }

    //  Record a new audit log entry
    public function record(?int $userId, string $action, string $details = '') {
        $stmt = $this->pdo->prepare(
            "INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $userId,
            $action,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    }

    //  Fetch logs with a limit (default 50)
    public function list(int $limit = 50) {
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.username 
             FROM audit_logs l 
             LEFT JOIN users u ON l.user_id = u.user_id 
             ORDER BY timestamp DESC 
             LIMIT ?
        ");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Alias for dashboard compatibility
    public function recent(int $limit = 10) {
        return $this->list($limit);
    }
}