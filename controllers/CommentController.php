<!-- controller/CommentController -->
<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Comment.php';

session_name(SESSION_NAME);
session_start();

$commentModel = new Comment();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
    $noticeId = (int)$_POST['notice_id'];
    $userId   = $_SESSION['user']['user_id'] ?? 0;
    $content  = trim($_POST['content']);

    if ($noticeId && $userId && $content !== '') {
        $commentModel->create($noticeId, $userId, $content);
    }
    header("Location: ../view-notice.php?id=$noticeId");
    exit;
}