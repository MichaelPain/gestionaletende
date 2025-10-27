<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$term = trim($_GET['q'] ?? '');
if ($term !== '') {
    $stmt = $pdo->prepare("SELECT * FROM clienti WHERE nome LIKE ? OR partita_iva LIKE ? OR codice_fiscale LIKE ? ORDER BY nome ASC");
    $like = '%' . $term . '%';
    $stmt->execute([$like, $like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM clienti ORDER BY nome ASC");
}
$clienti = $stmt->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Clienti</h1>
    <div class="toolbar">
        <form method="get" class="inline">
            <input name="q" placeholder="Cerca..." value="<?= sanitize($term) ?>">
            <button class="btn">Cerca</button>
        </form>
        <a class="btn btn-primary" href="/pages/clienti/nuovo.php">Nuovo cliente</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>P. IVA</th>
                <th>CF</th>
                <th>Telefono</th>
                <th>Email</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clienti as $c): ?>
                <tr>
                    <td><?= sanitize($c['nome'] ?? '') ?></td>
                    <td><?= sanitize($c['tipo'] ?? '') ?></td>
                    <td><?= sanitize($c['partita_iva'] ?? '') ?></td>
                    <td><?= sanitize($c['codice_fiscale'] ?? '') ?></td>
                    <td><?= sanitize($c['telefono'] ?? '') ?></td>
                    <td><?= sanitize($c['email'] ?? '') ?></td>
                    <td>
                        <a class="btn btn-sm" href="/pages/clienti/modifica.php?id=<?= (int)$c['id'] ?>">Modifica</a>
                        <a class="btn btn-sm btn-danger" href="/pages/clienti/elimina.php?id=<?= (int)$c['id'] ?>"
                           onclick="return confirm('Eliminare questo cliente?');">Elimina</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
