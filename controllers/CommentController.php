<!-- controller/CommentController -->
<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Comment.php';

//session_name(SESSION_NAME);
//session_start();

$commentModel = new Comment();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
    $noticeId = (int)$_POST['notice_id'];
    $userId   = $_SESSION['user']['user_id'] ?? 0;
    $content  = trim($_POST['content']);
    $imagePath = null;

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/comments/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = 'uploads/comments/' . $filename;
        }
    }

    if ($noticeId && $userId && $content !== '') {
        $commentModel->create($noticeId, $userId, $content, $imagePath);
    }

    header("Location: ../view-notice.php?id=$noticeId");
    exit;
}