<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// Controlla se l'attrezzatura è usata in assegnazioni di ordini attivi
$check = $pdo->prepare("
    SELECT COUNT(*) 
    FROM assegnazioni a
    JOIN ordini o ON a.id_ordine = o.id
    WHERE a.id_attrezzatura = ? 
      AND o.stato IN ('bozza','confermato')
");
$check->execute([$id]);
if ($check->fetchColumn() > 0) {
    die("Impossibile eliminare: l'attrezzatura è collegata ad assegnazioni di ordini attivi.");
}

$stmt = $pdo->prepare("DELETE FROM attrezzatura WHERE id = ?");
$stmt->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_ATTREZZATURA:' . $id]);

header('Location: index.php');
exit;
