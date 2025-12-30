<?php
// controllers/AdminController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/AuditLog.php';
require_once __DIR__ . '/../includes/functions.php';

class AdminController {
    public function dashboard() {
       // session_name(SESSION_NAME); session_start();
        require_role(['admin']);
        $userModel = new User();
        $categoryModel = new Category();
        $logModel = new AuditLog();

        $categories = $categoryModel->all();
        $logs = $logModel->list(20);

        include __DIR__ . '/../admin/dashboard.php';
    }

    public function manageUsers() {
        //session_name(SESSION_NAME); session_start();
        require_role(['admin']);
        $userModel = new User();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int)$_POST['user_id'];

       //$pdo = Database::getInstance()->pdo();

        if ($action === 'approve') { // Approve/disable fix
        $userModel->updateStatus($id, 'active');
        } elseif ($action === 'disable') {
        $userModel->updateStatus($id, 'disabled');
        }
        }
        // fetch all users for display
        $pdo = Database::getInstance()->pdo();
        $users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
        include __DIR__ . '/../admin/manage-users.php';
    }
}