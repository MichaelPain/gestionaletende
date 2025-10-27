<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM ordini WHERE id = ?");
$stmt->execute([$id]);
$ordine = $stmt->fetch();
if (!$ordine) { http_response_code(404); die('Ordine non trovato.'); }

$error = null;

// Aggiornamento ordine
if (is_post() && isset($_POST['update_order'])) {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF non valido.';
    } else {
        $stato = trim($_POST['stato'] ?? $ordine['stato']);
        $note = trim($_POST['note'] ?? '');
        $upd = $pdo->prepare("UPDATE ordini SET stato=?, note=? WHERE id=?");
        $upd->execute([$stato, $note, $id]);

        $u = current_user();
        $log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
        $log->execute([$u['id'], 'MODIFICA_ORDINE:' . $id]);

        redirect("modifica.php?id=$id");
    }
}

// Aggiunta riga tenda
if (is_post() && isset($_POST['add_tenda'])) {
    if (csrf_check($_POST['csrf'] ?? '')) {
        $id_tenda = (int)($_POST['id_tenda'] ?? 0);
        $quantita = (int)($_POST['quantita'] ?? 0);
        if ($id_tenda > 0 && $quantita > 0) {
            $ins = $pdo->prepare("INSERT INTO ordini_tende (id_ordine, id_tenda, quantita) VALUES (?, ?, ?)");
            $ins->execute([$id, $id_tenda, $quantita]);
        }
        redirect("modifica.php?id=$id");
    }
}

// Carica righe ordine
$stmt = $pdo->prepare("
    SELECT ot.id, t.nome, ot.quantita
    FROM ordini_tende ot
    JOIN tende t ON ot.id_tenda = t.id
    WHERE ot.id_ordine = ?
");
$stmt->execute([$id]);
$righe = $stmt->fetchAll();

$tende = $pdo->query("SELECT id, nome FROM tende ORDER BY nome")->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Modifica ordine #<?= (int)$ordine['id'] ?></h1>
    <?php if ($error): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <input type="hidden" name="update_order" value="1">
        <div class="form-group">
            <label>Stato</label>
            <select name="stato">
                <?php foreach (['bozza','confermato','completato','annullato'] as $s): ?>
                    <option value="<?= $s ?>" <?= $ordine['stato']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Note</label>
            <textarea name="note"><?= sanitize($ordine['note'] ?? '') ?></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Aggiorna ordine</button>
    </form>

    <h2>Righe ordine (tende)</h2>
    <table class="table">
        <thead><tr><th>Tenda</th><th>Quantità</th></tr></thead>
        <tbody>
        <?php foreach ($righe as $r): ?>
            <tr>
                <td><?= sanitize($r['nome']) ?></td>
                <td><?= (int)$r['quantita'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Aggiungi tenda</h3>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <input type="hidden" name="add_tenda" value="1">
        <select name="id_tenda">
            <?php foreach ($tende as $t): ?>
                <option value="<?= (int)$t['id'] ?>"><?= sanitize($t['nome']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="quantita" min="1" value="1">
        <button class="btn btn-secondary" type="submit">Aggiungi</button>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
