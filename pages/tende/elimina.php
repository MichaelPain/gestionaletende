<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// Controlla se la tenda è usata in ordini attivi
$check = $pdo->prepare("
    SELECT COUNT(*) 
    FROM ordini_tende ot
    JOIN ordini o ON ot.id_ordine = o.id
    WHERE ot.id_tenda = ? 
      AND o.stato IN ('bozza','confermato')
");
$check->execute([$id]);
if ($check->fetchColumn() > 0) {
    die("Impossibile eliminare: la tenda è collegata a ordini attivi.");
}

// Controlla se la tenda è usata in assegnazioni collegate a ordini attivi
$check2 = $pdo->prepare("
    SELECT COUNT(*) 
    FROM assegnazioni a
    JOIN ordini o ON a.id_ordine = o.id
    WHERE a.id_tenda = ? 
      AND o.stato IN ('bozza','confermato')
");
$check2->execute([$id]);
if ($check2->fetchColumn() > 0) {
    die("Impossibile eliminare: la tenda è collegata a assegnazioni di ordini attivi.");
}

$stmt = $pdo->prepare("DELETE FROM tende WHERE id = ?");
$stmt->execute([$id]);

$u = current_user();
$log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
$log->execute([$u['id'], 'ELIMINA_TENDA:' . $id]);

header('Location: index.php');
exit;
