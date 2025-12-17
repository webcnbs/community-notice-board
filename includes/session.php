<?php
// Start session only if none is active
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// Optional: Session timeout (30 minutes of inactivity)
if (isset($_SESSION['user'])) {
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
    } elseif (time() - $_SESSION['last_activity'] > 1800) {
        session_unset();
        session_destroy();
        header('Location: login.php'); exit;
    } else {
        $_SESSION['last_activity'] = time();
    }
}

// Optional: Auto-login via remember_token
if (!isset($_SESSION['user']) && isset($_COOKIE[REMEMBER_COOKIE])) {
    require_once __DIR__ . '/../models/User.php';
    require_once __DIR__ . '/../includes/database.php';

    $token = $_COOKIE[REMEMBER_COOKIE];
    $pdo = Database::getInstance()->pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['status'] === 'active') {
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        $_SESSION['last_activity'] = time();
    }
}