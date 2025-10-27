<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// Controlla se il veicolo esiste
$stmt = $pdo->prepare("SELECT * FROM veicoli WHERE id = ?");
$stmt->execute([$id]);
$veicolo = $stmt->fetch();
if (!$veicolo) {
    http_response_code(404);
    die('Veicolo non trovato.');
}

// TODO: opzionale — controllare se il veicolo è già assegnato in "assegnazioni" prima di eliminarlo

$del = $pdo->prepare("DELETE FROM veicoli WHERE id = ?");
$del->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_VEICOLO:' . $id]);

header('Location: index.php');
exit;
