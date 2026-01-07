<?php
// models/Bookmark.php

// Include the database connection class
require_once __DIR__ . '/../includes/database.php';

// Define the Bookmark model class
class Bookmark {
    // Private property to hold the PDO instance
    private $pdo;

    // Constructor initializes the PDO connection using the singleton Database class
    public function __construct() {
        $this->pdo = Database::getInstance()->pdo();
    }

    /**
     * Add a bookmark for a user and notice
     * 
     * @param int $userId - ID of the user creating the bookmark
     * @param int $noticeId - ID of the notice being bookmarked
     * 
     * Note: Uses INSERT IGNORE to avoid duplicate entries
     */
    public function add(int $userId, int $noticeId) {
        // Prepare SQL statement to insert a bookmark (ignores duplicates)
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO bookmarks (user_id, notice_id) VALUES (?, ?)");
        // Execute the statement with provided values
        $stmt->execute([$userId, $noticeId]);
    }

    /**
     * Remove a bookmark for a user and notice
     * 
     * @param int $userId - ID of the user
     * @param int $noticeId - ID of the notice
     */
    public function remove(int $userId, int $noticeId) {
        // Prepare SQL statement to delete a bookmark
        $stmt = $this->pdo->prepare("DELETE FROM bookmarks WHERE user_id = ? AND notice_id = ?");
        // Execute the statement with provided values
        $stmt->execute([$userId, $noticeId]);
    }

    /**
     * List all bookmarks for a specific user
     * 
     * @param int $userId - ID of the user
     * @return array - List of bookmarks with notice ID, creation time, and notice title
     */
    public function list(int $userId): array {
        // Prepare SQL statement to select bookmarks and join with notices table for titles
        $stmt = $this->pdo->prepare("
            SELECT b.notice_id, b.created_at, n.title
            FROM bookmarks b
            JOIN notices n ON b.notice_id = n.notice_id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        // Execute the statement with the user ID
        $stmt->execute([$userId]);
        // Return all results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Check if a bookmark exists for a user and notice
     * 
     * @param int $userId - ID of the user
     * @param int $noticeId - ID of the notice
     * @return bool - True if bookmark exists, false otherwise
     */
    public function exists(int $userId, int $noticeId): bool {
        // Prepare SQL statement to count bookmarks for given user and notice
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM bookmarks WHERE user_id = ? AND notice_id = ?");
        // Execute the statement with provided values
        $stmt->execute([$userId, $noticeId]);
        // Return true if count > 0, meaning bookmark exists
        return $stmt->fetchColumn() > 0;
    }
}