<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// Carica ordine
$stmt = $pdo->prepare("
    SELECT o.*, c.nome AS cliente
    FROM ordini o
    JOIN clienti c ON o.id_cliente = c.id
    WHERE o.id = ?
");
$stmt->execute([$id]);
$ordine = $stmt->fetch();
if (!$ordine) { http_response_code(404); die('Ordine non trovato.'); }

// Carica righe tende
$stmt = $pdo->prepare("
    SELECT t.nome, ot.quantita
    FROM ordini_tende ot
    JOIN tende t ON ot.id_tenda = t.id
    WHERE ot.id_ordine = ?
");
$stmt->execute([$id]);
$tende = $stmt->fetchAll();

// Carica assegnazioni
$stmt = $pdo->prepare("
    SELECT a.id,
           v.targa,
           op.nome AS operaio_nome, op.cognome AS operaio_cognome,
           t.nome AS tenda, a.quantita_tenda,
           att.nome AS attrezzatura, a.quantita_attrezzatura
    FROM assegnazioni a
    LEFT JOIN veicoli v ON a.id_veicolo = v.id
    LEFT JOIN operai op ON a.id_operaio = op.id
    LEFT JOIN tende t ON a.id_tenda = t.id
    LEFT JOIN attrezzatura att ON a.id_attrezzatura = att.id
    WHERE a.id_ordine = ?
");
$stmt->execute([$id]);
$assegnazioni = $stmt->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Dettaglio ordine #<?= (int)$ordine['id'] ?></h1>
    <p><strong>Cliente:</strong> <?= sanitize($ordine['cliente']) ?></p>
    <p><strong>Data:</strong> <?= sanitize($ordine['data_ordine']) ?></p>
    <p><strong>Tipo:</strong> <?= sanitize($ordine['tipo']) ?></p>
    <p><strong>Stato:</strong> <?= sanitize($ordine['stato']) ?></p>
    <p><strong>Note:</strong> <?= nl2br(sanitize($ordine['note'] ?? '')) ?></p>

    <h2>Righe ordine (tende)</h2>
    <?php if ($tende): ?>
        <table class="table">
            <thead><tr><th>Tenda</th><th>Quantità</th></tr></thead>
            <tbody>
            <?php foreach ($tende as $r): ?>
                <tr>
                    <td><?= sanitize($r['nome']) ?></td>
                    <td><?= (int)$r['quantita'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nessuna tenda associata.</p>
    <?php endif; ?>

    <h2>Assegnazioni</h2>
    <?php if ($assegnazioni): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Veicolo</th>
                    <th>Operaio</th>
                    <th>Tenda</th>
                    <th>Quantità tenda</th>
                    <th>Attrezzatura</th>
                    <th>Quantità attrezzatura</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($assegnazioni as $a): ?>
                <tr>
                    <td><?= sanitize($a['targa'] ?? '-') ?></td>
                    <td><?= sanitize(trim(($a['op operaio_cognome'] ?? '') . ' ' . ($a['op operaio_nome'] ?? ''))) ?></td>
                    <td><?= sanitize($a['tenda'] ?? '-') ?></td>
                    <td><?= (int)($a['quantita_tenda'] ?? 0) ?></td>
                    <td><?= sanitize($a['attrezzatura'] ?? '-') ?></td>
                    <td><?= (int)($a['quantita_attrezzatura'] ?? 0) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nessuna assegnazione presente.</p>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

