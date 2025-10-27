<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// Optional: check foreign keys (orders referencing this client) before delete.
$stmt = $pdo->prepare("DELETE FROM clienti WHERE id = ?");
$stmt->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_CLIENTE:' . $id]);

header('Location: /pages/clienti/index.php');
exit;
