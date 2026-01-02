<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/models/Notice.php';
require_once __DIR__ . '/models/Bookmark.php';
require_once __DIR__ . '/models/Comment.php';

session_name(SESSION_NAME);
session_start();

$notice = new Notice();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "Invalid notice ID.";
    exit;
}

$data = $notice->find($id);
if (!$data) {
    echo "Notice not found.";
    exit;
}

$notice->incrementViews($id);

// ✅ Check if logged in and already bookmarked
$isBookmarked = false;
if (is_logged_in()) {
    $bookmarkModel = new Bookmark();
    $isBookmarked = $bookmarkModel->exists($_SESSION['user']['user_id'], $id);
}

// ✅ Fetch approved comments
$commentModel = new Comment();
$comments = $commentModel->listApproved($id);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($data['title']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1><?= htmlspecialchars($data['title']) ?></h1>
    <p><strong>Category:</strong> <?= htmlspecialchars($data['category_name']) ?></p>
    <p><strong>Priority:</strong> <?= htmlspecialchars($data['priority']) ?></p>
    <p><strong>Expires:</strong> <?= htmlspecialchars($data['expiry_date']) ?></p>
    <p><strong>Posted:</strong> <?= htmlspecialchars($data['created_at']) ?></p>
    <p><?= nl2br(htmlspecialchars($data['content'])) ?></p>

    <?php if (is_logged_in()): ?>
        <form method="post" action="api/bookmarks.php">
            <input type="hidden" name="notice_id" value="<?= $id ?>">
            <?php if ($isBookmarked): ?>
                <button type="submit" name="action" value="remove" class="danger">Remove Bookmark</button>
            <?php else: ?>
                <button type="submit" name="action" value="add">Bookmark</button>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <p class="info">Login to bookmark this notice.</p>
    <?php endif; ?>

    <!-- ✅ Comment Section -->
    <h3>Comments</h3>
    <div id="comment-list">
        <?php if ($comments): ?>
            <?php foreach ($comments as $c): ?>
                <div class="comment">
                    <strong><?= htmlspecialchars($c['username']); ?>:</strong>
                    <?= nl2br(htmlspecialchars($c['content'])); ?>
                    
                     <?php if (!empty($c['image_path'])): ?>
                        <div>
                            <img src="<?= htmlspecialchars($c['image_path']); ?>" alt="Comment image" style="max-width:200px;">
                        </div>
                    <?php endif; ?> 
                    
                    <small>(<?= htmlspecialchars($c['created_at']); ?>)</small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="info">No comments yet.</p>
        <?php endif; ?>
    </div>

    <?php if (is_logged_in()): ?>
        <form method="post" action="controllers/CommentController.php" enctype="multipart/form-data">
            <input type="hidden" name="notice_id" value="<?= $id ?>">
            <textarea name="content" required></textarea>
            <input type="file" name="image" accept="image/*">
            <button type="submit" name="action" value="add">Add Comment</button>
        </form>
    <?php else: ?>
        <p class="info">Login to add a comment.</p>
    <?php endif; ?>

    <?php
    $role = $_SESSION['user']['role'] ?? '';
    if ($role === 'admin') {
        $dashboardUrl = 'admin/dashboard.php';
    } elseif ($role === 'manager') {
        $dashboardUrl = 'index2.php'; // ✅ now points to manager dashboard
    } else {
        $dashboardUrl = 'index.php';
    }
    ?>
    <p class="mt-2">
      <a href="<?= $dashboardUrl; ?>" class="btn secondary">← Back </a>
    </p>
</body>
</html>