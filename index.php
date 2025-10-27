<?php
require_once __DIR__ . '/includes/bootstrap.php';
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<main class="container">
    <h1>Dashboard</h1>
    <p>Benvenuto nel Gestionale Tende.</p>
    <ul>
        <li><a href="/gestionaletende/pages/clienti/index.php">Gestione Clienti</a></li>
        <li><a href="/gestionaletende/pages/tende/index.php">Gestione Tende</a></li>
        <li><a href="/gestionaletende/pages/attrezzatura/index.php">Gestione Attrezzatura</a></li>
        <li><a href="/gestionaletende/pages/ordini/index.php">Gestione Ordini</a></li>
    </ul>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
