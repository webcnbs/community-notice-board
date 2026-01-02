<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($data['title']) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($data['title']) ?></h1>
        <div class="meta">
            <p><strong>Category:</strong> <?= htmlspecialchars($data['category_name'] ?? 'General') ?></p>
            <p><strong>Priority:</strong> <span class="badge"><?= htmlspecialchars($data['priority']) ?></span></p>
            <p><strong>Views:</strong> <?= (int)$data['views'] ?></p>
        </div>

        <div class="content">
            <?= nl2br(htmlspecialchars($data['content'])) ?>
        </div>

        <hr>

        <?php if (is_logged_in()): ?>
            <form method="post" action="route.php?action=bookmark">
                <input type="hidden" name="notice_id" value="<?= (int)$data['notice_id'] ?>">
                <?php if ($isBookmarked): ?>
                    <button type="submit" name="action" value="remove" class="btn-danger">Unbookmark</button>
                <?php else: ?>
                    <button type="submit" name="action" value="add" class="btn-primary">Bookmark This</button>
                <?php endif; ?>
            </form>
        <?php endif; ?>

        <h3>Comments</h3>
        <div id="comment-list">
            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $c): ?>
                    <div class="comment-box">
                        <strong><?= htmlspecialchars($c['username']) ?></strong>
                        <p><?= nl2br(htmlspecialchars($c['content'])) ?></p>
                        <small><?= $c['created_at'] ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to say something!</p>
            <?php endif; ?>
        </div>

        <?php if (is_logged_in()): ?>
            <form method="post" action="route.php?action=add-comment" class="mt-2">
                <input type="hidden" name="notice_id" value="<?= (int)$data['notice_id'] ?>">
                <textarea name="content" placeholder="Add a comment..." required></textarea><br>
                <button type="submit" class="btn">Post Comment</button>
            </form>
        <?php endif; ?>

        <p><a href="index.php">← Back to Board</a></p>
    </div>
</body>
</html>