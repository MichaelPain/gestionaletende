<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$error = null;

if (is_post()) {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF non valido.';
    } else {
        $nome = trim($_POST['nome'] ?? '');
        $cognome = trim($_POST['cognome'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nome === '' || $cognome === '') {
            $error = 'Nome e cognome sono obbligatori.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO operai (nome, cognome, telefono, email) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $cognome, $telefono, $email]);

            $u = current_user();
            $log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
            $log->execute([$u['id'], 'CREA_OPERAIO:' . $nome . ' ' . $cognome]);

            redirect('index.php');
        }
    }
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Nuovo operaio</h1>
    <?php if ($error): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <div class="form-group"><label>Nome</label><input name="nome" required></div>
        <div class="form-group"><label>Cognome</label><input name="cognome" required></div>
        <div class="form-group"><label>Telefono</label><input name="telefono"></div>
        <div class="form-group"><label>Email</label><input name="email" type="email"></div>
        <button class="btn btn-primary" type="submit">Salva</button>
        <a class="btn" href="index.php">Annulla</a>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
