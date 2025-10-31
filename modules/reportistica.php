<?php
session_start();
require_once '../config/config.php';

echo "<h2>Reportistica</h2>";

try {
    // Report ordini per tipo
    $ordini = $conn->query("SELECT tipo, COUNT(*) as totale FROM ordini GROUP BY tipo");
    echo "<h3>Ordini per Tipo</h3><ul>";
    while ($o = $ordini->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($o['tipo']) . ": " . htmlspecialchars($o['totale']) . "</li>";
    }
    echo "</ul>";

    // Report risorse totali
    $risorse = $conn->query("
        SELECT 'veicoli' AS tipo, COUNT(*) AS totale FROM veicoli
        UNION ALL
        SELECT 'operai', COUNT(*) FROM operai
        UNION ALL
        SELECT 'attrezzature', COUNT(*) FROM attrezzature
    ");
    echo "<h3>Totale Risorse</h3><ul>";
    while ($r = $risorse->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($r['tipo']) . ": " . htmlspecialchars($r['totale']) . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    error_log("Errore reportistica: " . $e->getMessage());
    echo "<p>Errore nel caricamento dei report.</p>";
}
?>