<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM operai WHERE id = ?");
$stmt->execute([$id]);
$operaio = $stmt->fetch();
if (!$operaio) {
    http_response_code(404);
    die('Operaio non trovato.');
}

// TODO: opzionale — controllare se l'operaio è già assegnato in "assegnazioni" prima di eliminarlo

$del = $pdo->prepare("DELETE FROM operai WHERE id = ?");
$del->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_OPERAIO:' . $id]);

header('Location: index.php');
exit;
