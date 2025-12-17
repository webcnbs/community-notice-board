<?php
// includes/auth.php

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/config.php';

session_name(SESSION_NAME);
session_start();

if (!isset($_SESSION['user']) && isset($_COOKIE[REMEMBER_COOKIE])) {
    $token = $_COOKIE[REMEMBER_COOKIE];
    $pdo = Database::getInstance()->pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = ? AND status = 'active' LIMIT 1");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    if ($user) {
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
    }
}