<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT a.id, o.stato FROM assegnazioni a JOIN ordini o ON a.id_ordine=o.id WHERE a.id=?");
$stmt->execute([$id]);
$ass = $stmt->fetch();
if (!$ass) { http_response_code(404); die('Assegnazione non trovata.'); }

// Consenti eliminazione solo se l'ordine non è completato
if (in_array($ass['stato'], ['completato'], true)) {
    die("Impossibile eliminare: l'assegnazione appartiene a un ordine completato.");
}

$del = $pdo->prepare("DELETE FROM assegnazioni WHERE id = ?");
$del->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_ASSEGNAZIONE:' . $id]);

header('Location: index.php');
exit;
