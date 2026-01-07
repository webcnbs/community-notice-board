<?php
// models/User.php

// Include the database connection class
require_once __DIR__ . '/../includes/database.php';

// Define the User model class
class User {
    // Private property to hold the PDO instance
    private $pdo;

    // Constructor initializes the PDO connection using the singleton Database class
    public function __construct() {
        $this->pdo = Database::getInstance()->pdo();
    }

    /**
     * Find a user by email (used during login)
     * 
     * @param string $email - User's email address
     * @return array|null - User record if found, otherwise null
     */
    public function findByEmail(string $email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if an email already exists (used during registration)
     * 
     * @param string $email - Email to check
     * @return bool - True if email exists, false otherwise
     */
    public function exists(string $email): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Check if a username already exists (used during registration)
     * 
     * @param string $username - Username to check
     * @return bool - True if username exists, false otherwise
     */
    public function usernameExists(string $username): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Create a new user (used during registration)
     * 
     * @param string $username - New user's username
     * @param string $email - New user's email
     * @param string $password - New user's password (will be hashed)
     */
    public function create(string $username, string $email, string $password) {
        // Hash the password securely before storing
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Option A: Let database defaults handle role='resident' and status='pending'
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
        );
        $stmt->execute([$username, $email, $hash]);

        // Option B (explicit defaults) - commented out
        // $stmt = $this->pdo->prepare(
        //     "INSERT INTO users (username, email, password, role, status)
        //      VALUES (?, ?, ?, 'resident', 'pending')"
        // );
        // $stmt->execute([$username, $email, $hash]);
    }

    /**
     * Fetch all users (used in manage-users.php)
     * 
     * @return array - List of all users ordered by user ID
     */
    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY user_id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update a user's status (approve/disable)
     * 
     * @param int $userId - ID of the user
     * @param string $status - New status value
     */
    public function updateStatus(int $userId, string $status) {
        $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE user_id = ?");
        $stmt->execute([$status, $userId]);
    }

    /**
     * Update a user's role (admin panel)
     * 
     * @param int $userId - ID of the user
     * @param string $role - New role value
     */
    public function updateRole(int $userId, string $role) {
        $stmt = $this->pdo->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        $stmt->execute([$role, $userId]);
    }

    /**
     * Approve a user (helper for AdminController)
     * 
     * @param int $userId - ID of the user
     */
    public function approve(int $userId) {
        $this->updateStatus($userId, 'active');
    }

    /**
     * Disable a user (helper for AdminController)
     * 
     * @param int $userId - ID of the user
     */
    public function disable(int $userId) {
        $this->updateStatus($userId, 'disabled');
    }
}