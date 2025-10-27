<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$error = null;

if (is_post()) {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF non valido.';
    } else {
        $id_cliente = (int)($_POST['id_cliente'] ?? 0);
        $tipo = trim($_POST['tipo'] ?? 'noleggio');
        $note = trim($_POST['note'] ?? '');

        $stmt = $pdo->prepare("INSERT INTO ordini (id_cliente, data_ordine, tipo, stato, note) VALUES (?, NOW(), ?, 'bozza', ?)");
        $stmt->execute([$id_cliente, $tipo, $note]);

        $id_ordine = $pdo->lastInsertId();

        $u = current_user();
        $log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
        $log->execute([$u['id'], 'CREA_ORDINE:' . $id_ordine]);

        redirect("modifica.php?id=$id_ordine");
    }
}

$clienti = $pdo->query("SELECT id, nome FROM clienti ORDER BY nome")->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Nuovo ordine</h1>
    <?php if ($error): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <div class="form-group">
            <label>Cliente</label>
            <select name="id_cliente" required>
                <option value="">-- Seleziona --</option>
                <?php foreach ($clienti as $c): ?>
                    <option value="<?= (int)$c['id'] ?>"><?= sanitize($c['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Tipo</label>
            <select name="tipo">
                <option value="noleggio">Noleggio</option>
                <option value="vendita">Vendita</option>
            </select>
        </div>
        <div class="form-group">
            <label>Note</label>
            <textarea name="note"></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Crea ordine</button>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
