<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$term = trim($_GET['q'] ?? '');
if ($term !== '') {
    $stmt = $pdo->prepare("SELECT * FROM tende WHERE nome LIKE ? OR descrizione LIKE ? ORDER BY nome ASC");
    $like = '%' . $term . '%';
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM tende ORDER BY nome ASC");
}
$tende = $stmt->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Tende</h1>
    <div class="toolbar">
        <form method="get" class="inline">
            <input name="q" placeholder="Cerca..." value="<?= sanitize($term) ?>">
            <button class="btn">Cerca</button>
        </form>
        <a class="btn btn-primary" href="/pages/tende/nuovo.php">Nuova tenda</a>
    </div>
    <table class="table">
        <thead><tr><th>Nome</th><th>Descrizione</th><th>Disponibile</th><th>Azioni</th></tr></thead>
        <tbody>
        <?php foreach ($tende as $t): ?>
            <tr>
                <td><?= sanitize($t['nome'] ?? '') ?></td>
                <td><?= sanitize($t['descrizione'] ?? '') ?></td>
                <td><?= (int)($t['quantita_disponibile'] ?? 0) ?></td>
                <td>
                    <a class="btn btn-sm" href="/pages/tende/modifica.php?id=<?= (int)$t['id'] ?>">Modifica</a>
                    <a class="btn btn-sm btn-danger" href="/pages/tende/elimina.php?id=<?= (int)$t['id'] ?>" onclick="return confirm('Eliminare questa tenda?');">Elimina</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
