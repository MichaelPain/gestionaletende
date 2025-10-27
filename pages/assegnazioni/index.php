<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$stmt = $pdo->query("
    SELECT a.id, o.id AS ordine_id, o.stato, c.nome AS cliente,
           v.targa, op.nome AS operaio_nome, op.cognome AS operaio_cognome,
           t.nome AS tenda, a.quantita_tenda,
           att.nome AS attrezzatura, a.quantita_attrezzatura
    FROM assegnazioni a
    JOIN ordini o ON a.id_ordine = o.id
    JOIN clienti c ON o.id_cliente = c.id
    LEFT JOIN veicoli v ON a.id_veicolo = v.id
    LEFT JOIN operai op ON a.id_operaio = op.id
    LEFT JOIN tende t ON a.id_tenda = t.id
    LEFT JOIN attrezzatura att ON a.id_attrezzatura = att.id
    ORDER BY a.id DESC
");
$rows = $stmt->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Assegnazioni</h1>
    <a class="btn btn-primary" href="nuovo.php">Nuova assegnazione</a>
    <table class="table">
        <thead>
            <tr>
                <th>Ordine</th><th>Cliente</th><th>Stato ordine</th>
                <th>Veicolo</th><th>Operaio</th>
                <th>Tenda</th><th>Q.tà</th>
                <th>Attrezzatura</th><th>Q.tà</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td>#<?= (int)$r['ordine_id'] ?></td>
                <td><?= sanitize($r['cliente']) ?></td>
                <td><?= sanitize($r['stato']) ?></td>
                <td><?= sanitize($r['targa'] ?? '-') ?></td>
                <td><?= sanitize(($r['operaio_nome'] ?? '') . ' ' . ($r['operaio_cognome'] ?? '')) ?></td>
                <td><?= sanitize($r['tenda'] ?? '-') ?></td>
                <td><?= (int)($r['quantita_tenda'] ?? 0) ?></td>
                <td><?= sanitize($r['attrezzatura'] ?? '-') ?></td>
                <td><?= (int)($r['quantita_attrezzatura'] ?? 0) ?></td>
                <td>
                    <a href="modifica.php?id=<?= (int)$r['id'] ?>">Modifica</a> |
                    <a href="elimina.php?id=<?= (int)$r['id'] ?>" onclick="return confirm('Eliminare questa assegnazione?');">Elimina</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
