<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$error = null;

if (is_post()) {
    if (!csrf_check($_POST['csrf'] ?? '')) {
        $error = 'Token CSRF non valido.';
    } else {
        $id_ordine = (int)($_POST['id_ordine'] ?? 0);

        if ($id_ordine <= 0) {
            $error = "Devi selezionare un ordine.";
        } else {
            // Veicoli multipli
            if (!empty($_POST['id_veicolo'])) {
                foreach ($_POST['id_veicolo'] as $id_veicolo) {
                    $stmt = $pdo->prepare("INSERT INTO assegnazioni (id_ordine, id_veicolo) VALUES (?, ?)");
                    $stmt->execute([$id_ordine, (int)$id_veicolo]);
                }
            }

            // Operai multipli
            if (!empty($_POST['id_operaio'])) {
                foreach ($_POST['id_operaio'] as $id_operaio) {
                    $stmt = $pdo->prepare("INSERT INTO assegnazioni (id_ordine, id_operaio) VALUES (?, ?)");
                    $stmt->execute([$id_ordine, (int)$id_operaio]);
                }
            }

            // Tende multiple
            if (!empty($_POST['id_tenda'])) {
                foreach ($_POST['id_tenda'] as $id_tenda) {
                    $stmt = $pdo->prepare("INSERT INTO assegnazioni (id_ordine, id_tenda, quantita_tenda) VALUES (?, ?, ?)");
                    $stmt->execute([$id_ordine, (int)$id_tenda, (int)($_POST['quantita_tenda'] ?? 0)]);
                }
            }

            // Attrezzatura multipla
            if (!empty($_POST['id_attrezzatura'])) {
                foreach ($_POST['id_attrezzatura'] as $id_att) {
                    $stmt = $pdo->prepare("INSERT INTO assegnazioni (id_ordine, id_attrezzatura, quantita_attrezzatura) VALUES (?, ?, ?)");
                    $stmt->execute([$id_ordine, (int)$id_att, (int)($_POST['quantita_attrezzatura'] ?? 0)]);
                }
            }

            // Audit log
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

        <div class="form-group"><label>Veicoli</label>
            <select name="id_veicolo[]" multiple>
                <?php foreach ($veicoli as $v): ?>
                    <option value="<?= (int)$v['id'] ?>"><?= sanitize($v['targa']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group"><label>Operai</label>
            <select name="id_operaio[]" multiple>
                <?php foreach ($operai as $op): ?>
                    <option value="<?= (int)$op['id'] ?>"><?= sanitize($op['cognome'].' '.$op['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group"><label>Tende</label>
            <select name="id_tenda[]" multiple>
                <?php foreach ($tende as $t): ?>
                    <option value="<?= (int)$t['id'] ?>"><?= sanitize($t['nome']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="quantita_tenda" min="0" value="0">
        </div>

        <div class="form-group"><label>Attrezzatura</label>
            <select name="id_attrezzatura[]" multiple>
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

