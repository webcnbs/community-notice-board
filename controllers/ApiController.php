<?php
// controllers/ApiController.php
require_once __DIR__ . '/../models/Notice.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../includes/functions.php';

class ApiController {

    // Endpoint to fetch notices in a format the website can read dynamically
    public function notices() {

        // Set header so the browser knows to expect a JSON data object
        header('Content-Type: application/json');
        $notice = new Notice();

        // Collect search and filter inputs from the URL (GET parameters)
        $filters = [
            'category_id' => $_GET['category_id'] ?? null,
            'priority'    => $_GET['priority'] ?? null,
            'q'           => $_GET['q'] ?? null,
            'active_only' => true,
        ];

        // Setup pagination logic (how many items to show per page)
        $limit  = (int)($_GET['limit'] ?? 10);
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;

        // Convert the PHP array into a JSON string and send it to the browser
        echo json_encode(['data' => $notice->list($filters, $limit, $offset)]);
    }

    // Endpoint to fetch approved comments for a specific notice
    public function comments() {
        header('Content-Type: application/json');
        $comment = new Comment();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $noticeId = (int)($_GET['notice_id'] ?? 0);

            // Fetch only the comments that have been cleared by a manager
            echo json_encode(['data' => $comment->listApproved($noticeId)]);
        }
    }
}