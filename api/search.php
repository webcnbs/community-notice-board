<?php
// api/search.php
require_once __DIR__ . '/../models/Notice.php';
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';
$notice = new Notice();
$filters = ['q' => $q, 'active_only' => true];
echo json_encode(['data' => $notice->list($filters, 20, 0)]);