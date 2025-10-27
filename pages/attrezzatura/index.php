<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$term = trim($_GET['q'] ?? '');
if ($term !== '') {
    $stmt = $pdo->prepare("SELECT * FROM attrezzatura WHERE nome LIKE ? OR descrizione LIKE ? ORDER BY nome ASC");
    $like = '%' . $term . '%';
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM attrezzatura ORDER BY nome ASC");
}
$items = $stmt->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Attrezzatura</h1>
    <div class="toolbar">
        <form method="get" class="inline">
            <input name="q" placeholder="Cerca..." value="<?= sanitize($term) ?>">
            <button class="btn">Cerca</button>
        </form>
        <a class="btn btn-primary" href="/pages/attrezzatura/nuovo.php">Nuovo articolo</a>
    </div>
    <table class="table">
        <thead><tr><th>Nome</th><th>Descrizione</th><th>Disponibile</th><th>Azioni</th></tr></thead>
        <tbody>
        <?php foreach ($items as $i): ?>
            <tr>
                <td><?= sanitize($i['nome'] ?? '') ?></td>
                <td><?= sanitize($i['descrizione'] ?? '') ?></td>
                <td><?= (int)($i['quantita_disponibile'] ?? 0) ?></td>
                <td>
                    <a class="btn btn-sm" href="/pages/attrezzatura/modifica.php?id=<?= (int)$i['id'] ?>">Modifica</a>
                    <a class="btn btn-sm btn-danger" href="/pages/attrezzatura/elimina.php?id=<?= (int)$i['id'] ?>" onclick="return confirm('Eliminare questo articolo?');">Elimina</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
