<?php
// models/Comment.php

// Include the database connection class
require_once __DIR__ . '/../includes/database.php';

// Define the Comment model class
class Comment {
    // Private property to hold the PDO instance
    private $pdo;

    // Constructor initializes the PDO connection using the singleton Database class
    public function __construct() {
        $this->pdo = Database::getInstance()->pdo();
    }

    /**
     * Create a new comment (defaults to 'pending' status in the database)
     * 
     * @param int $noticeId - ID of the notice the comment belongs to
     * @param int $userId - ID of the user posting the comment
     * @param string $content - Text content of the comment
     * @param string|null $imagePath - Optional image path attached to the comment
     */
    public function create(int $noticeId, int $userId, string $content, ?string $imagePath = null) {
        // Prepare SQL statement to insert a new comment
        $stmt = $this->pdo->prepare(
            "INSERT INTO comments (notice_id, user_id, content, image_path) 
             VALUES (?, ?, ?, ?)" // extra columns fix
        );
        // Execute the statement with provided values
        $stmt->execute([$noticeId, $userId, $content, $imagePath]);
    }

    /**
     * List all approved comments for a specific notice
     * 
     * @param int $noticeId - ID of the notice
     * @return array - List of approved comments with user details
     */
    public function listApproved(int $noticeId) {
        // Prepare SQL statement to select approved comments and join with users table
        $stmt = $this->pdo->prepare(
            "SELECT c.comment_id, c.notice_id, c.user_id, c.content,
                c.image_path, c.status, c.created_at, u.username
             FROM comments c
             JOIN users u ON c.user_id = u.user_id
             WHERE c.notice_id = ? AND c.status = 'approved'
             ORDER BY c.created_at DESC"
        );
        // Execute the statement with the notice ID
        $stmt->execute([$noticeId]);
        // Return results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Moderate a comment by updating its status
     * 
     * @param int $commentId - ID of the comment
     * @param string $status - New status ('approved', 'rejected', or 'pending')
     */
    public function moderate(int $commentId, string $status) {
        // Prepare SQL statement to update comment status
        $stmt = $this->pdo->prepare(
            "UPDATE comments SET status = ? WHERE comment_id = ?"
        );
        // Execute the statement with provided values
        $stmt->execute([$status, $commentId]);
    }

    /**
     * Retrieve all pending comments across notices
     * 
     * @return array - List of pending comments with user and notice details
     */
    public function allPending() {
        // Prepare SQL statement to select pending comments with user and notice info
        $stmt = $this->pdo->prepare(
            "SELECT c.comment_id, c.notice_id, c.user_id, c.content, c.created_at,
                    u.username, n.title AS notice_title
             FROM comments c
             JOIN users u ON c.user_id = u.user_id
             JOIN notices n ON c.notice_id = n.notice_id
             WHERE c.status = 'pending'
             ORDER BY c.created_at DESC"
        );
        // Execute the query
        $stmt->execute();
        // Return results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}