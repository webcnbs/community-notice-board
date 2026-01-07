<?php
// models/Notification.php

// Include the database connection class
require_once __DIR__ . '/../includes/database.php';

// Define the Notification model class
class Notification {
    // Private property to hold the PDO instance
    private $pdo;

    // Constructor initializes the PDO connection using the singleton Database class
    public function __construct() { 
        $this->pdo = Database::getInstance()->pdo(); 
    }

    /**
     * Create a new notification for a user
     * 
     * @param int $userId - ID of the user receiving the notification
     * @param string $message - Notification message content
     */
    public function create(int $userId, string $message) {
        // Prepare SQL statement to insert a new notification
        $stmt = $this->pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        // Execute the statement with provided values
        $stmt->execute([$userId, $message]);
    }

    /**
     * Retrieve all unread notifications for a user
     * 
     * @param int $userId - ID of the user
     * @return array - List of unread notifications ordered by creation time (newest first)
     */
    public function unread(int $userId) {
        // Prepare SQL statement to select unread notifications for the user
        $stmt = $this->pdo->prepare("SELECT * FROM notifications WHERE user_id=? AND is_read=0 ORDER BY created_at DESC");
        // Execute the statement with the user ID
        $stmt->execute([$userId]);
        // Return all matching rows
        return $stmt->fetchAll();
    }

    /**
     * Mark a notification as read
     * 
     * @param int $id - ID of the notification to update
     */
    public function markRead(int $id) {
        // Prepare SQL statement to update notification status to 'read'
        $stmt = $this->pdo->prepare("UPDATE notifications SET is_read=1 WHERE notification_id=?");
        // Execute the statement with the notification ID
        $stmt->execute([$id]);
    }
}