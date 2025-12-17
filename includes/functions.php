<?php
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function is_logged_in(): bool {
    return isset($_SESSION['user']);
}

function user_role(): string {
    // Return empty string if not set, instead of 'resident'.
    return $_SESSION['user']['role'] ?? '';
}

function require_role(array $roles) {
    if (!is_logged_in() || !in_array(user_role(), $roles, true)) {
        header('Location: login.php');
        exit;
    }
}

function log_action($pdo, $userId, $action, $details) {
    $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $action, $details, $_SERVER['REMOTE_ADDR'] ?? '']);
}