<!--Ian Wong -->
<?php

//loads the Notice model which interacts with the database
require_once __DIR__ . '/../models/Notice.php'; 
header('Content-Type: application/json'); //setting the response type to JSON

$notice = new Notice(); //creating notice object

//filters GET parameters
$filters = [
  'category_id' => $_GET['category_id'] ?? null,
  'priority'    => $_GET['priority'] ?? null,
  'q'           => $_GET['q'] ?? null,
  'active_only' => false,
];

//Pagination logic
$limit  = (int)($_GET['limit'] ?? 10);
$page   = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$data  = $notice->list($filters, $limit, $offset); //fetches notice data
$total = $notice->count($filters); //counts the total number of notices that matches with the search
$pages = ceil($total / $limit); //calculates the total pages that are existing

//outputs the JSON response
echo json_encode([
  'data'  => $data,
  'total' => $total,
  'page'  => $page,
  'pages' => $pages
]);