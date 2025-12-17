<?php
// controllers/ApiController.php
require_once __DIR__ . '/../models/Notice.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../includes/functions.php';

class ApiController {
    public function notices() {
        header('Content-Type: application/json');
        $notice = new Notice();
        $filters = [
            'category_id' => $_GET['category_id'] ?? null,
            'priority'    => $_GET['priority'] ?? null,
            'q'           => $_GET['q'] ?? null,
            'active_only' => true,
        ];
        $limit  = (int)($_GET['limit'] ?? 10);
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        echo json_encode(['data' => $notice->list($filters, $limit, $offset)]);
    }

    public function comments() {
        header('Content-Type: application/json');
        $comment = new Comment();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $noticeId = (int)($_GET['notice_id'] ?? 0);
            echo json_encode(['data' => $comment->listApproved($noticeId)]);
        }
    }
}