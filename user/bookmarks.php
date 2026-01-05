<?php
// user/bookmarks.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Bookmark.php';

session_name(SESSION_NAME);
session_start();

require_role(['resident','manager','admin']);

$bookmarkModel = new Bookmark();
$bookmarks = $bookmarkModel->list($_SESSION['user']['user_id']);

$role = $_SESSION['user']['role'] ?? '';
$dashboardUrl = ($role === 'admin') ? '../admin/dashboard.php' : (($role === 'manager') ? '../index2.php' : '../index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookmarks</title>
    <link rel="stylesheet" href="../assets/css/MDstyle.css">
</head>
<body class="MDbody">

    <header class="MDheader">
        <h1>Saved Bookmarks</h1>
        <nav class="MDnav">
            <a href="<?= $dashboardUrl; ?>" class="MDbtn">← Dashboard</a>
        </nav>
    </header>

    <main>
        <h2 class="MDh2">Your Saved Notices</h2>
        <p class="MDinfo">Access your bookmarked community updates below.</p>

        <?php if (isset($_GET['removed'])): ?>
            <p class="MDinfo" style="color: #b23a48; font-weight: bold;">🗑️ Bookmark removed successfully.</p>
        <?php endif; ?>

        <div class="MDdashboard-grid">
            <?php if (empty($bookmarks)): ?>
                <p class="MDinfo">You haven't bookmarked any notices yet.</p>
            <?php else: ?>
                <?php foreach ($bookmarks as $b): ?>
                    <div class="MDbtn" style="flex-direction: column; align-items: flex-start; text-align: left; height: auto;">
                        <span class="icon">🔖</span>
                        <div style="margin-top: 10px;">
                            <a href="../view-notice.php?id=<?= $b['notice_id']; ?>" style="color: white; text-decoration: none; font-size: 1.2rem; display: block;">
                                <?= htmlspecialchars($b['title']); ?>
                            </a>
                            <small style="opacity: 0.8; font-size: 0.8rem;">
                                Saved: <?= date('M d, Y', strtotime($b['created_at'])); ?>
                            </small>
                        </div>

                        <form method="post" action="../api/bookmarks.php" style="width: 100%; margin-top: 15px;">
                            <input type="hidden" name="notice_id" value="<?= $b['notice_id']; ?>">
                            <button type="submit" name="action" value="remove" class="MDbtn danger" 
                                    style="padding: 0.5rem; width: 100%; font-size: 0.9rem;"
                                    onclick="return confirm('Remove this bookmark?')">
                                Remove Bookmark
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="MDfooter">
        &copy; <?= date('Y'); ?> Community Notice Board
    </footer>

</body>
</html>