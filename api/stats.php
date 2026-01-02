<?php
// api/stats.php
require_once __DIR__ . '/../includes/database.php';
header('Content-Type: application/json');

$pdo = Database::getInstance()->pdo();
$data = [
    'total_users'   => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'total_notices' => $pdo->query("SELECT COUNT(*) FROM notices")->fetchColumn(),
    'total_comments'=> $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn(),
    'active_notices'=> $pdo->query("SELECT COUNT(*) FROM notices WHERE expiry_date IS NULL OR expiry_date >= CURDATE()")->fetchColumn(),
];
echo json_encode($data);