<!--Ian Wong -->
<?php
// api/stats.php

//loads the database class
require_once __DIR__ . '/../includes/database.php';
header('Content-Type: application/json'); //setting the response type in JSON

$pdo = Database::getInstance()->pdo(); //access the shared database instance

//an associative array to hold 4 stats or statistics
$data = [
    'total_users'   => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'total_notices' => $pdo->query("SELECT COUNT(*) FROM notices")->fetchColumn(),
    'total_comments'=> $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn(),
    'active_notices'=> $pdo->query("SELECT COUNT(*) FROM notices WHERE expiry_date IS NULL OR expiry_date >= CURDATE()")->fetchColumn(),
];

echo json_encode($data); //returns a JSON response