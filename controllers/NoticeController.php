<?php
// controllers/NoticeController.php
require_once __DIR__ . '/../models/Notice.php';
require_once __DIR__ . '/../models/AuditLog.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

class NoticeController {
    public function manage() {
        require_role(['manager','admin']);
        $noticeModel = new Notice();
        $auditLog = new AuditLog(); //  Initialize logger

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_verify($_POST['csrf'] ?? '')) die('Invalid CSRF');
            $action = $_POST['action'] ?? '';

            if ($action === 'create') {
                $data = [
                    ':title' => sanitize($_POST['title']),
                    ':content' => $_POST['content'],
                    ':category_id' => (int)$_POST['category_id'],
                    ':priority' => sanitize($_POST['priority']),
                    ':user_id' => $_SESSION['user']['user_id'],
                    ':expiry_date' => $_POST['expiry_date'] ?: null,
                ];
                $noticeModel->create($data);
                //  Record Notice Creation
                $auditLog->record($_SESSION['user']['user_id'], 'Create Notice', "Title: " . $_POST['title']);

                //  Redirect with success flag
                header('Location: admin/manage-notices.php?created=1');
                exit;
            } elseif ($action === 'update') {
                $id = (int)$_POST['notice_id'];
                $data = [
                    ':title' => sanitize($_POST['title']),
                    ':content' => $_POST['content'],
                    ':category_id' => (int)$_POST['category_id'],
                    ':priority' => sanitize($_POST['priority']),
                    ':expiry_date' => $_POST['expiry_date'] ?: null,
                ];
                $noticeModel->update($id, $data);

                // Record Notice Update
                $auditLog->record($_SESSION['user']['user_id'], 'Update Notice', "ID: $id - Title: " . $_POST['title']);

                //  Redirect with update flag
                header('Location: admin/manage-notices.php?updated=1');

                exit;

                } elseif ($action === 'delete') {
                // 1. Define the ID first
                $id = (int)$_POST['notice_id']; 
    
                // 2. Perform the deletion
                $noticeModel->delete($id);
    
                // 3. Record the log (Now $id is defined!)
                $auditLog->record($_SESSION['user']['user_id'], 'Delete Notice', "Deleted Notice ID: $id");

                header('Location: admin/manage-notices.php?deleted=1');
                exit;
}
        }

        // Default: show manage-notices page
        $filters = [];
        $notices = $noticeModel->list($filters, 50, 0);
        include __DIR__ . '/../admin/manage-notices.php';
    }

    public function view() {
        $id = (int)($_GET['id'] ?? 0);
        $noticeModel = new Notice();
        $notice = $noticeModel->find($id);
        if ($notice) $noticeModel->incrementViews($id);
        include __DIR__ . '/../view-notice.php';
    }
}