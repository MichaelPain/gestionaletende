<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM veicoli WHERE id = ?");
$stmt->execute([$id]);
$veicolo = $stmt->fetch();
if (!$veicolo) { http_response_code(404); die('Veicolo non trovato.'); }

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
            $upd = $pdo->prepare("UPDATE veicoli SET targa=?, modello=?, tipo=?, capacità=? WHERE id=?");
            $upd->execute([$targa, $modello, $tipo, $capacita, $id]);

            $u = current_user();
            $log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
            $log->execute([$u['id'], 'MODIFICA_VEICOLO:' . $id]);

            redirect('index.php');
        }
    }
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Modifica veicolo</h1>
    <?php if ($error): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <div class="form-group"><label>Targa</label><input name="targa" value="<?= sanitize($veicolo['targa']) ?>" required></div>
        <div class="form-group"><label>Modello</label><input name="modello" value="<?= sanitize($veicolo['modello']) ?>" required></div>
<div class="form-group"><label>Tipo</label>
    <select name="tipo" required>
        <option value="Furgone" <?= $veicolo['tipo']=='Furgone'?'selected':'' ?>>Furgone</option>
        <option value="Altro" <?= $veicolo['tipo']=='Altro'?'selected':'' ?>>Altro</option>
    </select>
</div>
        <div class="form-group"><label>Capacità</label><input name="capacita" type="number" min="0" value="<?= (int)$veicolo['capacità'] ?>"></div>
        <button class="btn btn-primary" type="submit">Salva</button>
        <a class="btn" href="index.php">Annulla</a>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
