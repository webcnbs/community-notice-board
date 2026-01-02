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
    
    // Get filters from AJAX request
    $filters = [
        'category_id' => $_GET['category_id'] ?? null,
        'priority'    => $_GET['priority'] ?? null,
        'q'           => $_GET['q'] ?? null,
        'active_only' => true // Residents should only see active notices
    ];
    
    $notices = $noticeModel->list($filters, 20, 0);
    
    // Send data back to ajax.js as JSON
    header('Content-Type: application/json');
    echo json_encode($notices);
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
    // Check if user is logged in
    if (empty($_SESSION['user'])) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $noticeId = (int)($input['notice_id'] ?? 0);
    $content = sanitize($input['content'] ?? '');

    if ($noticeId > 0 && !empty($content)) {
        $stmt = Database::getInstance()->pdo()->prepare("
            INSERT INTO comments (notice_id, user_id, content, status) 
            VALUES (?, ?, ?, 'pending')
        ");
        $success = $stmt->execute([$noticeId, $_SESSION['user']['user_id'], $content]);
        
        header('Content-Type: application/json');
        echo json_encode(['ok' => $success]);
    } else {
        echo json_encode(['ok' => false]);
    }
    exit;

    default:
        http_response_code(404);
        echo "Unknown action.";
}