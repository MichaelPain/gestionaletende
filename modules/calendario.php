<?php
session_start();
require_once '../config/config.php';

echo "<h2>Calendario Eventi</h2>";

try {
    $oggi = date('Y-m-d');
    $eventi = $conn->query("SELECT data_evento, localizzazione, ordine_id FROM eventi WHERE data_evento >= '$oggi' ORDER BY data_evento ASC");

    echo "<table border='1'><tr><th>Data</th><th>Localizzazione</th><th>ID Ordine</th></tr>";
    while ($e = $eventi->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>" . htmlspecialchars($e['data_evento']) . "</td><td>" . htmlspecialchars($e['localizzazione']) . "</td><td>" . htmlspecialchars($e['ordine_id']) . "</td></tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    error_log("Errore calendario: " . $e->getMessage());
    echo "<p>Errore nel caricamento del calendario.</p>";
}
?>
