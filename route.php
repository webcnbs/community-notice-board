<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
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

        case 'index2':
       include __DIR__ . '/index2.php'; //index2 manager page fix
       break;
        
    default:
        http_response_code(404);
        echo "Unknown action.";
}