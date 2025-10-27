<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// Verifica se il cliente ha ordini collegati
$check = $pdo->prepare("SELECT COUNT(*) FROM ordini WHERE id_cliente = ?");
$check->execute([$id]);
if ($check->fetchColumn() > 0) {
    die("Impossibile eliminare: il cliente ha ordini associati.");
}

$stmt = $pdo->prepare("DELETE FROM clienti WHERE id = ?");
$stmt->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_CLIENTE:' . $id]);

header('Location: index.php');
exit;
