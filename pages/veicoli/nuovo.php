<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$error = null;

if (is_post()) {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF non valido.';
    } else {
        $targa = trim($_POST['targa'] ?? '');
        $modello = trim($_POST['modello'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $capacita = (int)($_POST['capacita'] ?? 0);

        if ($targa === '' || $modello === '') {
            $error = 'Targa e modello sono obbligatori.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO veicoli (targa, modello, tipo, capacità) VALUES (?, ?, ?, ?)");
            $stmt->execute([$targa, $modello, $tipo, $capacita]);

            $u = current_user();
            $log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
            $log->execute([$u['id'], 'CREA_VEICOLO:' . $targa]);

            redirect('index.php');
        }
    }
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Nuovo veicolo</h1>
    <?php if ($error): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <div class="form-group"><label>Targa</label><input name="targa" required></div>
        <div class="form-group"><label>Modello</label><input name="modello" required></div>
<div class="form-group"><label>Tipo</label>
    <select name="tipo" required>
        <option value="Furgone">Furgone</option>
        <option value="Altro">Altro</option>
    </select>
</div>
        <div class="form-group"><label>Capacità</label><input name="capacita" type="number" min="0" value="0"></div>
        <button class="btn btn-primary" type="submit">Salva</button>
        <a class="btn" href="index.php">Annulla</a>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
