<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$error = null;

if (is_post()) {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF non valido.';
    } else {
        $data = [
            'nome' => trim($_POST['nome'] ?? ''),
            'descrizione' => trim($_POST['descrizione'] ?? ''),
            'tipo' => trim($_POST['tipo'] ?? ''),
            'prezzo_acquisto' => (float)($_POST['prezzo_acquisto'] ?? 0),
            'prezzo_noleggio' => (float)($_POST['prezzo_noleggio'] ?? 0),
            'quantita_disponibile' => (int)($_POST['quantita_disponibile'] ?? 0),
        ];
        if ($data['nome'] === '') {
            $error = 'Il nome è obbligatorio.';
        } elseif ($data['quantita_disponibile'] < 0) {
            $error = 'La quantità non può essere negativa.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO attrezzatura (nome, descrizione, tipo, prezzo_acquisto, prezzo_noleggio, quantita_disponibile) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['nome'], $data['descrizione'], $data['tipo'], $data['prezzo_acquisto'], $data['prezzo_noleggio'], $data['quantita_disponibile']]);

            $u = current_user();
            $log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
            $log->execute([$u['id'], 'CREA_ATTREZZATURA:' . $data['nome']]);

            redirect('/pages/attrezzatura/index.php');
        }
    }
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Nuova attrezzatura</h1>
    <?php if ($error): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <div class="form-group"><label>Nome</label><input name="nome" required></div>
        <div class="form-group"><label>Descrizione</label><input name="descrizione"></div>
        <div class="form-group"><label>Tipo</label><input name="tipo"></div>
        <div class="form-group"><label>Prezzo acquisto</label><input name="prezzo_acquisto" type="number" step="0.01" min="0" value="0"></div>
        <div class="form-group"><label>Prezzo noleggio</label><input name="prezzo_noleggio" type="number" step="0.01" min="0" value="0"></div>
        <div class="form-group"><label>Quantità disponibile</label><input name="quantita_disponibile" type="number" min="0" value="0"></div>
        <button class="btn btn-primary" type="submit">Salva</button>
        <a class="btn" href="/pages/attrezzatura/index.php">Annulla</a>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
