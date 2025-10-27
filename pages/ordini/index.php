<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$stmt = $pdo->query("
    SELECT o.id, o.data_ordine, o.tipo, o.stato, c.nome AS cliente
    FROM ordini o
    JOIN clienti c ON o.id_cliente = c.id
    ORDER BY o.data_ordine DESC
");
$ordini = $stmt->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Ordini</h1>
    <a class="btn btn-primary" href="nuovo.php">Nuovo ordine</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th><th>Cliente</th><th>Data</th><th>Tipo</th><th>Stato</th><th>Azioni</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($ordini as $o): ?>
            <tr>
                <td><?= (int)$o['id'] ?></td>
                <td><?= sanitize($o['cliente']) ?></td>
                <td><?= sanitize($o['data_ordine']) ?></td>
                <td><?= sanitize($o['tipo']) ?></td>
                <td><?= sanitize($o['stato']) ?></td>
                <td>
                    <a href="modifica.php?id=<?= (int)$o['id'] ?>">Modifica</a> |
                    <a href="elimina.php?id=<?= (int)$o['id'] ?>" onclick="return confirm('Eliminare ordine?');">Elimina</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
