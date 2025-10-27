<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// Controlla se la tenda è usata in ordini o assegnazioni
$check1 = $pdo->prepare("SELECT COUNT(*) FROM ordini_tende WHERE id_tenda = ?");
$check1->execute([$id]);
$check2 = $pdo->prepare("SELECT COUNT(*) FROM assegnazioni WHERE id_tenda = ?");
$check2->execute([$id]);

if ($check1->fetchColumn() > 0 || $check2->fetchColumn() > 0) {
    die("Impossibile eliminare: la tenda è collegata a ordini o assegnazioni.");
}

$stmt = $pdo->prepare("DELETE FROM tende WHERE id = ?");
$stmt->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_TENDA:' . $id]);

header('Location: index.php');
exit;
