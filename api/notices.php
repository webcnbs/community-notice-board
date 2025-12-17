<?php
require_once __DIR__ . '/../models/Notice.php';
header('Content-Type: application/json');

$notice = new Notice();
$filters = [
  'category_id' => $_GET['category_id'] ?? null,
  'priority'    => $_GET['priority'] ?? null,
  'q'           => $_GET['q'] ?? null,
  'active_only' => false,
];
$limit  = (int)($_GET['limit'] ?? 10);
$page   = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$data  = $notice->list($filters, $limit, $offset);
$total = $notice->count($filters);
$pages = ceil($total / $limit);

echo json_encode([
  'data'  => $data,
  'total' => $total,
  'page'  => $page,
  'pages' => $pages
]);