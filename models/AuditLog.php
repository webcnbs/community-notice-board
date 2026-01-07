<?php
// models/AuditLog.php

// Include the database connection class
require_once __DIR__ . '/../includes/database.php';

// Define the AuditLog model class
class AuditLog {
    // Private property to hold the PDO instance
    private $pdo;

    // Constructor initializes the PDO connection using the singleton Database class
    public function __construct() { 
        $this->pdo = Database::getInstance()->pdo(); 
    }

    /**
     * Record a new audit log entry
     * 
     * @param int|null $userId - ID of the user performing the action (nullable for guest/system actions)
     * @param string $action - Short description of the action performed
     * @param string $details - Optional detailed information about the action
     */
    public function record(?int $userId, string $action, string $details = '') {
        // Prepare SQL statement to insert a new audit log entry
        $stmt = $this->pdo->prepare(
            "INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)"
        );
        // Execute the statement with provided values, including IP address (fallback to 'unknown')
        $stmt->execute([
            $userId,
            $action,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    }

    /**
     * Fetch audit logs with a limit (default 50)
     * 
     * @param int $limit - Maximum number of logs to retrieve
     * @return array - List of logs with associated usernames
     */
    public function list(int $limit = 50) {
        // Prepare SQL statement to select logs and join with users table for usernames
        $stmt = $this->pdo->prepare("
            SELECT l.*, u.username 
             FROM audit_logs l 
             LEFT JOIN users u ON l.user_id = u.user_id 
             ORDER BY timestamp DESC 
             LIMIT ?
        ");
        // Bind the limit parameter securely as an integer
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        // Execute the query
        $stmt->execute();
        // Return all results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Alias for dashboard compatibility
     * 
     * @param int $limit - Number of recent logs to fetch (default 10)
     * @return array - List of recent logs
     */
    public function recent(int $limit = 10) {
        // Reuse the list() method to fetch limited logs
        return $this->list($limit);
    }
}