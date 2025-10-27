<?php
// includes/auth.php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => isset($_SERVER['HTTPS']),
    ]);
    session_start();
}

function require_login(): void {
    if (empty($_SESSION['user'])) {
        header('Location: /pages/login.php');
        exit;
    }
}

function require_role(string $role): void {
    require_login();
    $u = $_SESSION['user'];
    if (($u['ruolo'] ?? '') !== $role) {
        http_response_code(403);
        die('Accesso negato.');
    }
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}
