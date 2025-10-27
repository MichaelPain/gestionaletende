<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

// Statistiche principali
$ordini_attivi = $pdo->query("SELECT COUNT(*) FROM ordini WHERE stato IN ('bozza','confermato')")->fetchColumn();
$ordini_completati = $pdo->query("SELECT COUNT(*) FROM ordini WHERE stato='completato'")->fetchColumn();
$clienti_tot = $pdo->query("SELECT COUNT(*) FROM clienti")->fetchColumn();
$tende_tot = $pdo->query("SELECT SUM(quantita_disponibile) FROM tende")->fetchColumn();
$attrezzatura_tot = $pdo->query("SELECT SUM(quantita_disponibile) FROM attrezzatura")->fetchColumn();
$veicoli_tot = $pdo->query("SELECT COUNT(*) FROM veicoli")->fetchColumn();
$operai_tot = $pdo->query("SELECT COUNT(*) FROM operai")->fetchColumn();

// Ultimi ordini
$ultimi_ordini = $pdo->query("
    SELECT o.id, o.data_ordine, o.stato, c.nome AS cliente
    FROM ordini o
    JOIN clienti c ON o.id_cliente = c.id
    ORDER BY o.data_ordine DESC
    LIMIT 5
")->fetchAll();
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<main class="container">
    <h1>Dashboard</h1>

    <section class="stats">
        <div class="card">
            <h2>📦 Ordini attivi</h2>
            <p><?= (int)$ordini_attivi ?></p>
        </div>
        <div class="card">
            <h2>✅ Ordini completati</h2>
            <p><?= (int)$ordini_completati ?></p>
        </div>
        <div class="card">
            <h2>👥 Clienti</h2>
            <p><?= (int)$clienti_tot ?></p>
        </div>
        <div class="card">
            <h2>⛺ Tende disponibili</h2>
            <p><?= (int)$tende_tot ?></p>
        </div>
        <div class="card">
            <h2>🛠️ Attrezzatura disponibile</h2>
            <p><?= (int)$attrezzatura_tot ?></p>
        </div>
        <div class="card">
            <h2>🚐 Veicoli</h2>
            <p><?= (int)$veicoli_tot ?></p>
        </div>
        <div class="card">
            <h2>👷 Operai</h2>
            <p><?= (int)$operai_tot ?></p>
        </div>
    </section>

    <section class="latest">
        <h2>Ultimi ordini</h2>
        <table class="table">
            <thead><tr><th>ID</th><th>Cliente</th><th>Data</th><th>Stato</th><th>Azioni</th></tr></thead>
            <tbody>
            <?php foreach ($ultimi_ordini as $o): ?>
                <tr>
                    <td>#<?= (int)$o['id'] ?></td>
                    <td><?= sanitize($o['cliente']) ?></td>
                    <td><?= sanitize($o['data_ordine']) ?></td>
                    <td><?= sanitize($o['stato']) ?></td>
                    <td>
                        <a href="/pages/ordini/dettaglio.php?id=<?= (int)$o['id'] ?>">Dettaglio</a> |
                        <a href="/pages/ordini/modifica.php?id=<?= (int)$o['id'] ?>">Modifica</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="links">
        <h2>Sezioni</h2>
        <ul>
            <li><a href="/pages/clienti/index.php">Gestione Clienti</a></li>
            <li><a href="/pages/tende/index.php">Gestione Tende</a></li>
            <li><a href="/pages/attrezzatura/index.php">Gestione Attrezzatura</a></li>
            <li><a href="/pages/ordini/index.php">Gestione Ordini</a></li>
            <li><a href="/pages/veicoli/index.php">Gestione Veicoli</a></li>
            <li><a href="/pages/operai/index.php">Gestione Operai</a></li>
            <li><a href="/pages/assegnazioni/index.php">Gestione Assegnazioni</a></li>
        </ul>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
