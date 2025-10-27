<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM tende WHERE id = ?");
$stmt->execute([$id]);
$tenda = $stmt->fetch();
if (!$tenda) { http_response_code(404); die('Tenda non trovata.'); }

$error = null;

if (is_post()) {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF non valido.';
    } else {
        $nome = trim($_POST['nome'] ?? '');
        $descrizione = trim($_POST['descrizione'] ?? '');
        $quantita = (int)($_POST['quantita_disponibile'] ?? 0);

        if ($nome === '') {
            $error = 'Il nome è obbligatorio.';
        } elseif ($quantita < 0) {
            $error = 'La quantità non può essere negativa.';
        } else {
            $stmt = $pdo->prepare("UPDATE tende SET nome=?, descrizione=?, quantita_disponibile=? WHERE id=?");
            $stmt->execute([$nome, $descrizione, $quantita, $id]);

            $u = current_user();
            $log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
            $log->execute([$u['id'], 'MODIFICA_TENDA:' . $id]);

            redirect('/pages/tende/index.php');
        }
    }
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Modifica tenda</h1>
    <?php if ($error): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <div class="form-group"><label>Nome</label><input name="nome" value="<?= sanitize($tenda['nome'] ?? '') ?>" required></div>
        <div class="form-group"><label>Descrizione</label><input name="descrizione" value="<?= sanitize($tenda['descrizione'] ?? '') ?>"></div>
        <div class="form-group"><label>Quantità disponibile</label><input name="quantita_disponibile" type="number" min="0" value="<?= (int)($tenda['quantita_disponibile'] ?? 0) ?>"></div>
        <button class="btn btn-primary" type="submit">Salva</button>
        <a class="btn" href="/pages/tende/index.php">Annulla</a>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
