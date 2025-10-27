<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$term = trim($_GET['q'] ?? '');
if ($term !== '') {
    $stmt = $pdo->prepare("SELECT * FROM operai WHERE nome LIKE ? OR cognome LIKE ? ORDER BY cognome ASC");
    $like = '%' . $term . '%';
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM operai ORDER BY cognome ASC");
}
$operai = $stmt->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Operai</h1>
    <div class="toolbar">
        <form method="get" class="inline">
            <input name="q" placeholder="Cerca..." value="<?= sanitize($term) ?>">
            <button class="btn">Cerca</button>
        </form>
        <a class="btn btn-primary" href="nuovo.php">Nuovo operaio</a>
    </div>
    <table class="table">
        <thead>
            <tr><th>Nome</th><th>Cognome</th><th>Telefono</th><th>Email</th><th>Azioni</th></tr>
        </thead>
        <tbody>
        <?php foreach ($operai as $o): ?>
            <tr>
                <td><?= sanitize($o['nome']) ?></td>
                <td><?= sanitize($o['cognome']) ?></td>
                <td><?= sanitize($o['telefono']) ?></td>
                <td><?= sanitize($o['email']) ?></td>
                <td>
                    <a href="modifica.php?id=<?= (int)$o['id'] ?>">Modifica</a> |
                    <a href="elimina.php?id=<?= (int)$o['id'] ?>" onclick="return confirm('Eliminare questo operaio?');">Elimina</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
