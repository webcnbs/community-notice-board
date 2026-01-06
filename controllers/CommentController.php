<!-- controller/CommentController -->
<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Comment.php';

//session_name(SESSION_NAME);
//session_start();

$commentModel = new Comment();

// Handle form submission to add a new comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'add') {
    $noticeId = (int)$_POST['notice_id'];
    $userId   = $_SESSION['user']['user_id'] ?? 0;
    $content  = trim($_POST['content']);
    $imagePath = null;

     // Check if an image was uploaded with the comment
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/comments/';

        // Ensure the directory exists; if not, create i
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        // Generate a unique filename to prevent overwriting existing files
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $filename;
        // Move the uploaded file from temporary storage to the final folder
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = 'uploads/comments/' . $filename;
        }
    }
    // Validate that all required fields are present before saving to Database
    if ($noticeId && $userId && $content !== '') {
        $commentModel->create($noticeId, $userId, $content, $imagePath);
    }
    // Redirect the user back to the notice they were viewing
    header("Location: ../view-notice.php?id=$noticeId");
    exit;
}