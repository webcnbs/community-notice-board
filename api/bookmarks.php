<?php
// api/bookmarks.php

//loads configuration that contains constants, reusable functions and model class
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../models/Bookmark.php';

session_name(SESSION_NAME); //uses a custom session name 
session_start(); //starts the user’s session to access

$bookmark = new Bookmark(); //creates a bookmark object to interact with the bookmarks table

//checks if the user is currently logged in or not  
if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'auth']);
    exit;
}

$noticeId = (int)($_POST['notice_id'] ?? 0); //retrieves a notice ID when being bookmarked 
$action   = $_POST['action'] ?? ''; //fetches the user’s action which is either (add or remove) the bookmark

//if the action is (add) a bookmark then it will go through this control statement
if ($action === 'add') {
    $bookmark->add($_SESSION['user']['user_id'], $noticeId);
    header('Location: ../user/bookmarks.php?added=1'); //redirects the user to the bookmarks page
    exit;

    // if the action is (remove) a bookmark then it will go through this control statement
} elseif ($action === 'remove') {
    $bookmark->remove($_SESSION['user']['user_id'], $noticeId);
    header('Location: ../user/bookmarks.php?removed=1'); //redirects the user to the bookmarks page
    exit;
}

http_response_code(400); //sends a HTTP 400 bad request if the action is INVALID
echo json_encode(['error' => 'invalid']); //Shows or outputs an error message if there are any INVALID requests