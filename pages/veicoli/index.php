<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$term = trim($_GET['q'] ?? '');
if ($term !== '') {
    $stmt = $pdo->prepare("SELECT * FROM veicoli WHERE targa LIKE ? OR modello LIKE ? ORDER BY targa ASC");
    $like = '%' . $term . '%';
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM veicoli ORDER BY targa ASC");
}
$veicoli = $stmt->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Veicoli</h1>
    <div class="toolbar">
        <form method="get" class="inline">
            <input name="q" placeholder="Cerca..." value="<?= sanitize($term) ?>">
            <button class="btn">Cerca</button>
        </form>
        <a class="btn btn-primary" href="nuovo.php">Nuovo veicolo</a>
    </div>
    <table class="table">
        <thead>
            <tr><th>Targa</th><th>Modello</th><th>Tipo</th><th>Capacità</th><th>Azioni</th></tr>
        </thead>
        <tbody>
        <?php foreach ($veicoli as $v): ?>
            <tr>
                <td><?= sanitize($v['targa']) ?></td>
                <td><?= sanitize($v['modello']) ?></td>
                <td><?= sanitize($v['tipo']) ?></td>
                <td><?= (int)$v['capacità'] ?></td>
                <td>
                    <a href="modifica.php?id=<?= (int)$v['id'] ?>">Modifica</a> |
                    <a href="elimina.php?id=<?= (int)$v['id'] ?>" onclick="return confirm('Eliminare questo veicolo?');">Elimina</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
