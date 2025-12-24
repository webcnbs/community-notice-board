<?php
// models/User.php
require_once __DIR__ . '/../includes/database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->pdo();
    }

    // ✅ Find user by email (used in login)
    public function findByEmail(string $email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Check if email already exists (used in registration)
    public function exists(string $email): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    // ✅ Create a new user (used in registration)
    public function create(string $username, string $email, string $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        // Option A: let DB default role='resident' and status='pending'
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
        );
        $stmt->execute([$username, $email, $hash]);

        /* Option B (explicit defaults):
        // $stmt = $this->pdo->prepare(
        //     "INSERT INTO users (username, email, password, role, status)
        //      VALUES (?, ?, ?, 'resident', 'pending')"
        // );
         $stmt->execute([$username, $email, $hash]);
         */
    }

    // ✅ Fetch all users (used in manage-users.php)
    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY user_id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Update user status (approve/disable)
    public function updateStatus(int $userId, string $status) {
        $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE user_id = ?");
        $stmt->execute([$status, $userId]);
    }

    // ✅ Update user role (admin panel)
    public function updateRole(int $userId, string $role) {
        $stmt = $this->pdo->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        $stmt->execute([$role, $userId]);
    }
}