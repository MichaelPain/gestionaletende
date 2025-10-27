<?php
require_once __DIR__ . '/../includes/auth.php';
$_SESSION = [];
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}
header('Location: /pages/login.php');
exit;
