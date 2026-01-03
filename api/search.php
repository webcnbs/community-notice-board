<!--Ian Wong -->
<?php
// api/search.php

//includes notice model which allows this API to access notice database logic 
require_once __DIR__ . '/../models/Notice.php';
header('Content-Type: application/json'); //setting the response type to JSON 

$q = $_GET['q'] ?? ''; //reads the search keyword 
$notice = new Notice(); //creating new instance of the Notice class
$filters = ['q' => $q, 'active_only' => true]; //filters the notices by keyword and active status
echo json_encode(['data' => $notice->list($filters, 20, 0)]); //returning search results in JSON format