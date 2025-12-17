<?php
// includes/csrf.php

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field() {
    echo '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function csrf_verify($token): bool {
    return hash_equals($_SESSION['csrf'] ?? '', $token ?? '');
}