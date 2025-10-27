<?php
// pages/ordini/elimina.php
require_once __DIR__ . '/../../includes/bootstrap.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// Carica ordine
$stmt = $pdo->prepare("SELECT id, stato FROM ordini WHERE id = ?");
$stmt->execute([$id]);
$ordine = $stmt->fetch();

if (!$ordine) {
    http_response_code(404);
    die('Ordine non trovato.');
}

// Consenti eliminazione solo se stato è bozza o annullato
if (!in_array($ordine['stato'], ['bozza', 'annullato'], true)) {
    http_response_code(400);
    die('L\'ordine può essere eliminato solo se in stato "bozza" o "annullato".');
}

try {
    $pdo->beginTransaction();

    // Elimina righe collegate (tende). Se aggiungerai attrezzatura, elimina anche quelle.
    $delRighe = $pdo->prepare("DELETE FROM ordini_tende WHERE id_ordine = ?");
    $delRighe->execute([$id]);

    // Elimina l'ordine
    $delOrdine = $pdo->prepare("DELETE FROM ordini WHERE id = ?");
    $delOrdine->execute([$id]);

    // Audit log
    $u = current_user();
    $log = $pdo->prepare("INSERT INTO audit_log (utente_id, azione, data_ora) VALUES (?, ?, NOW())");
    $log->execute([$u['id'], 'ELIMINA_ORDINE:' . $id]);

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    error_log('Errore eliminazione ordine: ' . $e->getMessage());
    http_response_code(500);
    die('Errore durante l\'eliminazione dell\'ordine.');
}

// Torna alla lista
header('Location: /pages/ordini/index.php');
exit;
