<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/controllers/AdminController.php';


if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/NoticeController.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        (new AuthController())->login();
        break;
    case 'register':
        (new AuthController())->register();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'manage-notices':
        (new NoticeController())->manage();
        break;
    case 'view-notice':
        (new NoticeController())->view();
        break;

        case 'admin-dashboard':
        (new AdminController())->dashboard();
        break;

    case 'manage-users':
        (new AdminController())->manageUsers();
        break;

    case 'manage-categories':
        include __DIR__ . '/admin/manage-categories.php';
        break;

        case 'index2':
       include __DIR__ . '/index2.php'; //index2 manager page fix
       break;


       // --- AJAX DATA ACTIONS ---
       case 'get-notices':
    require_once __DIR__ . '/models/Notice.php';
    $noticeModel = new Notice();

    // 1. Setup Pagination & Filters
    $limit  = (int)($_GET['limit'] ?? 10);
    $page   = max(1, (int)($_GET['page'] ?? 1));
    $offset = ($page - 1) * $limit;

    $filters = [
        'category_id' => $_GET['category_id'] ?? null,
        'priority'    => $_GET['priority'] ?? null,
        'q'           => $_GET['q'] ?? null,
        'active_only' => true
    ];

    // 2. Fetch Data
    $notices = $noticeModel->list($filters, $limit, $offset);
    $total   = $noticeModel->count($filters); // Works now because route.php is connected to DB
    $pages   = ceil($total / $limit);

    // 3. Send Response
    header('Content-Type: application/json');
    echo json_encode([
        'data'  => $notices,
        'total' => $total,
        'pages' => $pages,
        'page'  => $page
    ]);
    exit;
        
// Add these inside the switch ($action) block in route.php

case 'get-comments':
    $noticeId = (int)($_GET['notice_id'] ?? 0);
    // Assuming you have a Comment model or use PDO directly
    $stmt = Database::getInstance()->pdo()->prepare("
        SELECT c.*, u.username 
        FROM comments c 
        JOIN users u ON c.user_id = u.user_id 
        WHERE c.notice_id = ? AND c.status = 'approved'
        ORDER BY c.created_at ASC
    ");
    $stmt->execute([$noticeId]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($comments);
    exit;

    case 'add-comment':
    if (empty($_SESSION['user'])) {
        die('Unauthorized');
    }

    // Use standard $_POST instead of php://input for image support
    $noticeId = (int)($_POST['notice_id'] ?? 0);
    $content = sanitize($_POST['content'] ?? '');

    if ($noticeId > 0 && !empty($content)) {
        // Handle your Comment model call here
        $stmt = Database::getInstance()->pdo()->prepare("
            INSERT INTO comments (notice_id, user_id, content, status) 
            VALUES (?, ?, ?, 'pending')
        ");
        $stmt->execute([$noticeId, $_SESSION['user']['user_id'], $content]);
        
        // Redirect back to the notice page with a success message
        header("Location: route.php?action=view-notice&id=$noticeId&commented=1");    }
    exit;

    case 'bookmark':
    if (!is_logged_in()) die('Unauthorized');
    require_once __DIR__ . '/models/Bookmark.php';
    $bm = new Bookmark();
    $noticeId = (int)$_POST['notice_id'];
    $userId = $_SESSION['user']['user_id'];
    $act = $_POST['action']; // 'add' or 'remove'

    if ($act === 'add') {
        $bm->add($userId, $noticeId);
    } else {
        $bm->remove($userId, $noticeId);
    }
    header("Location: route.php?action=view-notice&id=$noticeId");
    exit;

    default:
        http_response_code(404);
        echo "Unknown action.";
}