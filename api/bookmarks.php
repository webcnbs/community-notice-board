<?php
// api/bookmarks.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Bookmark.php';

session_name(SESSION_NAME);
session_start();

$bookmark = new Bookmark();

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'auth']);
    exit;
}

$noticeId = (int)($_POST['notice_id'] ?? 0);
$action   = $_POST['action'] ?? '';

if ($action === 'add') {
    $bookmark->add($_SESSION['user']['user_id'], $noticeId);
    header('Location: ../user/bookmarks.php?added=1');
    exit;
} elseif ($action === 'remove') {
    $bookmark->remove($_SESSION['user']['user_id'], $noticeId);
    header('Location: ../user/bookmarks.php?removed=1');
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'invalid']);