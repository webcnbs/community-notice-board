<?php
// api/comments.php

//loads database class and functions
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';


session_name(SESSION_NAME); session_start(); //setting custom session name
header('Content-Type: application/json'); //setting the response type to JSON

$pdo = Database::getInstance()->pdo(); //access the shared database instance
$method = $_SERVER['REQUEST_METHOD']; //checks if it is GET or POST request method 

//if the request is GET 
if ($method === 'GET') {
    $noticeId = (int)($_GET['notice_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT c.comment_id, c.content, c.created_at, u.username
                           FROM comments c JOIN users u ON c.user_id=u.user_id
                           WHERE c.notice_id=? AND c.status='approved' ORDER BY c.created_at DESC");
    $stmt->execute([$noticeId]);
    echo json_encode(['data' => $stmt->fetchAll()]);
    exit;
}

//if the request is POST 
if ($method === 'POST') {
    if (!is_logged_in()) { http_response_code(401); echo json_encode(['error'=>'auth']); exit; }
    $payload = json_decode(file_get_contents('php://input'), true);
    $noticeId = (int)($payload['notice_id'] ?? 0);
    $content  = sanitize($payload['content'] ?? '');
    $stmt = $pdo->prepare("INSERT INTO comments (notice_id, user_id, content, status) VALUES (?, ?, ?, 'pending')");
    $stmt->execute([$noticeId, $_SESSION['user']['user_id'], $content]);
    echo json_encode(['ok' => true, 'status' => 'pending']);
    exit;
}