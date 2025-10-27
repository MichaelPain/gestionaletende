<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM clienti WHERE id = ?");
$stmt->execute([$id]);
$cliente = $stmt->fetch();
if (!$cliente) {
    http_response_code(404);
    die('Cliente non trovato.');
}

$error = null;

if (is_post()) {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF non valido.';
    } else {
        $data = [
            'tipo' => trim($_POST['tipo'] ?? ''),
            'nome' => trim($_POST['nome'] ?? ''),
            'partita_iva' => trim($_POST['partita_iva'] ?? ''),
            'codice_fiscale' => trim($_POST['codice_fiscale'] ?? ''),
            'indirizzo' => trim($_POST['indirizzo'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
        ];

        if ($data['nome'] === '') {
            $error = 'Il nome è obbligatorio.';
        } else {
            $stmt = $pdo->prepare("UPDATE clienti SET tipo=?, nome=?, partita_iva=?, codice_fiscale=?, indirizzo=?, telefono=?, email=? WHERE id=?");
            $stmt->execute([
                $data['tipo'], $data['nome'], $data['partita_iva'], $data['codice_fiscale'],
                $data['indirizzo'], $data['telefono'], $data['email'], $id
            ]);

            $u = current_user();
            $log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
            $log->execute([$u['id'], 'MODIFICA_CLIENTE:' . $id]);

            redirect('/pages/clienti/index.php');
        }
    }
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Modifica cliente</h1>
    <?php if ($error): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <div class="form-group"><label>Tipo</label><input name="tipo" value="<?= sanitize($cliente['tipo'] ?? '') ?>"></div>
        <div class="form-group"><label>Nome</label><input name="nome" value="<?= sanitize($cliente['nome'] ?? '') ?>" required></div>
        <div class="form-group"><label>Partita IVA</label><input name="partita_iva" value="<?= sanitize($cliente['partita_iva'] ?? '') ?>"></div>
        <div class="form-group"><label>Codice Fiscale</label><input name="codice_fiscale" value="<?= sanitize($cliente['codice_fiscale'] ?? '') ?>"></div>
        <div class="form-group"><label>Indirizzo</label><input name="indirizzo" value="<?= sanitize($cliente['indirizzo'] ?? '') ?>"></div>
        <div class="form-group"><label>Telefono</label><input name="telefono" value="<?= sanitize($cliente['telefono'] ?? '') ?>"></div>
        <div class="form-group"><label>Email</label><input name="email" type="email" value="<?= sanitize($cliente['email'] ?? '') ?>"></div>
        <button class="btn btn-primary" type="submit">Salva</button>
        <a class="btn" href="/pages/clienti/index.php">Annulla</a>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
