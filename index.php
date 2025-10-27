<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<main class="container">
    <h1>Dashboard</h1>
    <p>Benvenuto nel Gestionale Tende.</p>
    <ul>
        <li><a href="/pages/clienti/index.php">Gestione Clienti</a></li>
        <li><a href="/pages/tende/index.php">Gestione Tende</a></li>
        <li><a href="/pages/attrezzatura/index.php">Gestione Attrezzatura</a></li>
        <li><a href="/pages/ordini/index.php">Gestione Ordini</a></li>
    </ul>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
