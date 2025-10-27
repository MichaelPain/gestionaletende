<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// Controlla se l'operaio è usato in assegnazioni di ordini attivi
$check = $pdo->prepare("
    SELECT COUNT(*) 
    FROM assegnazioni a
    JOIN ordini o ON a.id_ordine = o.id
    WHERE a.id_operaio = ? 
      AND o.stato IN ('bozza','confermato')
");
$check->execute([$id]);
if ($check->fetchColumn() > 0) {
    die("Impossibile eliminare: l'operaio è collegato ad assegnazioni di ordini attivi.");
}

$stmt = $pdo->prepare("DELETE FROM operai WHERE id = ?");
$stmt->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_OPERAIO:' . $id]);

header('Location: index.php');
exit;
