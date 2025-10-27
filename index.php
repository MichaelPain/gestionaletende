<?php
require_once __DIR__ . '/includes/bootstrap.php';
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
        <li><a href="/pages/veicoli/index.php">Gestione Veicoli</a></li>
        <li><a href="/pages/operai/index.php">Gestione Operai</a></li>
        <li><a href="/pages/assegnazioni/index.php">Gestione Assegnazioni</a></li>
    </ul>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
