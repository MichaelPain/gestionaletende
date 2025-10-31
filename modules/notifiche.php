<?php
session_start();
require_once '../config/config.php';

echo "<h2>Notifiche</h2>";

try {
    // Ordini approvati
    echo "<h3>Ordini Approvati</h3><ul>";
    $ordini = $conn->query("SELECT o.id, c.nome AS cliente_nome FROM ordini o JOIN clienti c ON o.cliente_id = c.id WHERE o.stato = 'approvato'");
    while ($o = $ordini->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>Ordine #" . htmlspecialchars($o['id']) . " per cliente " . htmlspecialchars($o['cliente_nome']) . " è stato approvato.</li>";
    }
    echo "</ul>";

    // Eventi imminenti
    echo "<h3>Eventi Imminenti</h3><ul>";
    $oggi = date('Y-m-d');
    $eventi = $conn->query("SELECT data_evento, localizzazione FROM eventi WHERE data_evento >= '$oggi' ORDER BY data_evento ASC LIMIT 5");
    while ($e = $eventi->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>Evento il " . htmlspecialchars($e['data_evento']) . " a " . htmlspecialchars($e['localizzazione']) . "</li>";
    }
    echo "</ul>";

    // Manutenzioni in corso
    echo "<h3>Manutenzioni in corso</h3><ul>";
    $manutenzioni = $conn->query("SELECT a.nome, m.data_inizio FROM manutenzioni m JOIN attrezzature a ON m.attrezzatura_id = a.id WHERE m.data_fine IS NULL OR m.data_fine >= '$oggi'");
    while ($m = $manutenzioni->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>Attrezzatura " . htmlspecialchars($m['nome']) . " in manutenzione dal " . htmlspecialchars($m['data_inizio']) . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    error_log("Errore notifiche: " . $e->getMessage());
    echo "<p>Errore nel caricamento delle notifiche.</p>";
}
?>