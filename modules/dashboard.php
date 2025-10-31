<?php
session_start();
require_once '../config/config.php';

try {
    $ordini_attivi = $conn->query("SELECT COUNT(*) as totale FROM ordini WHERE stato = 'approvato'");
    $ordini_row = $ordini_attivi->fetch(PDO::FETCH_ASSOC);

    $veicoli_disp = $conn->query("SELECT COUNT(*) as totale FROM veicoli");
    $veicoli_row = $veicoli_disp->fetch(PDO::FETCH_ASSOC);

    $operai_disp = $conn->query("SELECT COUNT(*) as totale FROM operai");
    $operai_row = $operai_disp->fetch(PDO::FETCH_ASSOC);

    $attrezz_disp = $conn->query("SELECT COUNT(*) as totale FROM attrezzature WHERE stato = 'disponibile'");
    $attrezz_row = $attrezz_disp->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore nel recupero dati dashboard: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Gestionale Tende Bedouin</title>
</head>
<body>
    <h2>Dashboard</h2>

    <h3>Ordini Approvati Attivi</h3>
    <p>Totale: <?= htmlspecialchars($ordini_row['totale']) ?></p>

    <h3>Risorse Disponibili</h3>
    <ul>
        <li>Veicoli: <?= htmlspecialchars($veicoli_row['totale']) ?></li>
        <li>Operai: <?= htmlspecialchars($operai_row['totale']) ?></li>
        <li>Attrezzature disponibili: <?= htmlspecialchars($attrezz_row['totale']) ?></li>
    </ul>
</body>
</html>