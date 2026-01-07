<?php
// models/Attachment.php

// Include the database connection class
require_once __DIR__ . '/../includes/database.php';

// Define the Attachment model class
class Attachment {
    // Private property to hold the PDO instance
    private $pdo;

    // Constructor initializes the PDO connection using the singleton Database class
    public function __construct() { 
        $this->pdo = Database::getInstance()->pdo(); 
    }

    /**
     * Add a new attachment record to the database
     * 
     * @param int $noticeId - ID of the related notice
     * @param string $filename - Name of the uploaded file
     * @param string $filepath - Path where the file is stored
     * @return int - ID of the newly inserted attachment
     */
    public function add(int $noticeId, string $filename, string $filepath) {
        // Prepare SQL statement to insert attachment data
        $stmt = $this->pdo->prepare(
            "INSERT INTO attachments (notice_id, filename, filepath) VALUES (?, ?, ?)"
        );
        // Execute the statement with provided values
        $stmt->execute([$noticeId, $filename, $filepath]);
        // Return the ID of the newly inserted row
        return $this->pdo->lastInsertId();
    }

    /**
     * Retrieve all attachments related to a specific notice
     * 
     * @param int $noticeId - ID of the notice
     * @return array - List of attachments ordered by upload time (newest first)
     */
    public function listByNotice(int $noticeId) {
        // Prepare SQL statement to select attachments by notice ID
        $stmt = $this->pdo->prepare("SELECT * FROM attachments WHERE notice_id=? ORDER BY uploaded_at DESC");
        // Execute the statement with the notice ID
        $stmt->execute([$noticeId]);
        // Return all matching rows as an array
        return $stmt->fetchAll();
    }

    /**
     * Delete an attachment by its ID
     * 
     * @param int $id - ID of the attachment to delete
     */
    public function delete(int $id) {
        // Prepare SQL statement to delete attachment by ID
        $stmt = $this->pdo->prepare("DELETE FROM attachments WHERE attachment_id=?");
        // Execute the statement with the attachment ID
        $stmt->execute([$id]);
    }
}