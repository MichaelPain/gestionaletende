<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$error = null;

if (is_post()) {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF non valido.';
    } else {
        $id_ordine = (int)($_POST['id_ordine'] ?? 0);
        $id_veicolo = (int)($_POST['id_veicolo'] ?? 0) ?: null;
        $id_operaio = (int)($_POST['id_operaio'] ?? 0) ?: null;
        $id_tenda = (int)($_POST['id_tenda'] ?? 0) ?: null;
        $q_tenda = (int)($_POST['quantita_tenda'] ?? 0);
        $id_attrezzatura = (int)($_POST['id_attrezzatura'] ?? 0) ?: null;
        $q_att = (int)($_POST['quantita_attrezzatura'] ?? 0);

        if ($id_ordine <= 0) {
            $error = "Devi selezionare un ordine.";
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO assegnazioni (id_ordine, id_veicolo, id_operaio, id_tenda, quantita_tenda, id_attrezzatura, quantita_attrezzatura)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$id_ordine, $id_veicolo, $id_operaio, $id_tenda, $q_tenda, $id_attrezzatura, $q_att]);

            $u = current_user();
            $log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
            $log->execute([$u['id'], 'CREA_ASSEGNAZIONE: ordine ' . $id_ordine]);

            redirect('index.php');
        }
    }
}

$ordini = $pdo->query("SELECT id FROM ordini WHERE stato IN ('bozza','confermato') ORDER BY id DESC")->fetchAll();
$veicoli = $pdo->query("SELECT id, targa FROM veicoli ORDER BY targa")->fetchAll();
$operai = $pdo->query("SELECT id, nome, cognome FROM operai ORDER BY cognome")->fetchAll();
$tende = $pdo->query("SELECT id, nome FROM tende ORDER BY nome")->fetchAll();
$attrezzatura = $pdo->query("SELECT id, nome FROM attrezzatura ORDER BY nome")->fetchAll();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<main class="container">
    <h1>Nuova assegnazione</h1>
    <?php if ($error): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <div class="form-group"><label>Ordine</label>
            <select name="id_ordine" required>
                <option value="">-- Seleziona --</option>
                <?php foreach ($ordini as $o): ?>
                    <option value="<?= (int)$o['id'] ?>">Ordine #<?= (int)$o['id'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Veicolo</label>
            <select name="id_veicolo"><option value="">-- Nessuno --</option>
                <?php foreach ($veicoli as $v): ?>
                    <option value="<?= (int)$v['id'] ?>"><?= sanitize($v['targa']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Operaio</label>
            <select name="id_operaio"><option value="">-- Nessuno --</option>
                <?php foreach ($operai as $op): ?>
                    <option value="<?= (int)$op['id'] ?>"><?= sanitize($op['cognome'].' '.$op['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label>Tenda</label>
            <select name="id_tenda"><option value="">-- Nessuna --</option>
                <?php foreach ($tende as $t): ?>
                    <option value="<?= (int)$t['id'] ?>"><?= sanitize($t['nome']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="quantita_tenda" min="0" value="0">
        </div>
        <div class="form-group"><label>Attrezzatura</label>
            <select name="id_attrezzatura"><option value="">-- Nessuna --</option>
                <?php foreach ($attrezzatura as $a): ?>
                    <option value="<?= (int)$a['id'] ?>"><?= sanitize($a['nome']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="quantita_attrezzatura" min="0" value="0">
        </div>
        <button class="btn btn-primary" type="submit">Salva</button>
        <a class="btn" href="index.php">Annulla</a>
    </form>
</main>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
