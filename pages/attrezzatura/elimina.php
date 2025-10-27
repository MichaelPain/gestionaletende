<?php
// pages/attrezzatura/elimina.php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// TODO: opzionale — verificare se l'articolo è referenziato in ordini/assegnazioni prima di eliminare
$del = $pdo->prepare("DELETE FROM attrezzatura WHERE id = ?");
$del->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_ATTREZZATURA:' . $id]);

header('Location: /pages/attrezzatura/index.php');
exit;
