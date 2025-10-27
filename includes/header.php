<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config.php';
$user = current_user();
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title><?= sanitize(APP_NAME) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>
<header class="topbar">
    <div class="brand"><a href="/index.php"><?= sanitize(APP_NAME) ?></a></div>
    <nav class="nav">
        <a href="/pages/clienti/index.php">Clienti</a>
        <a href="/pages/tende/index.php">Tende</a>
        <a href="/pages/attrezzatura/index.php">Attrezzatura</a>
        <a href="/pages/ordini/index.php">Ordini</a>
        <?php if ($user): ?>
            <span class="user">Ciao, <?= sanitize($user['username']) ?> (<?= sanitize($user['ruolo']) ?>)</span>
            <a href="/pages/logout.php">Logout</a>
        <?php else: ?>
            <a href="/pages/login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>
