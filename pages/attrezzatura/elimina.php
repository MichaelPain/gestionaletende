<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// Controlla se l'attrezzatura è usata in assegnazioni
$check = $pdo->prepare("SELECT COUNT(*) FROM assegnazioni WHERE id_attrezzatura = ?");
$check->execute([$id]);
if ($check->fetchColumn() > 0) {
    die("Impossibile eliminare: l'attrezzatura è collegata ad assegnazioni.");
}

$stmt = $pdo->prepare("DELETE FROM attrezzatura WHERE id = ?");
$stmt->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_ATTREZZATURA:' . $id]);

header('Location: index.php');
exit;
