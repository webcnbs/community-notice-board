<?php
// logout.php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/controllers/AuthController.php';

(new AuthController())->logout();