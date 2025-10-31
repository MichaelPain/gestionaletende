<?php
session_start();
require_once '../config/config.php';

echo "<h2>Log Tracciamento</h2>";

try {
    $log = $conn->query("SELECT l.id, u.nome AS utente, l.azione, l.timestamp FROM log_tracciamento l JOIN utenti u ON l.utente_id = u.id ORDER BY l.timestamp DESC LIMIT 100");
    echo "<table border='1'>
        <tr><th>ID</th><th>Utente</th><th>Azione</th><th>Timestamp</th></tr>";
    while ($r = $log->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
            <td>" . htmlspecialchars($r['id']) . "</td>
            <td>" . htmlspecialchars($r['utente']) . "</td>
            <td>" . htmlspecialchars($r['azione']) . "</td>
            <td>" . htmlspecialchars($r['timestamp']) . "</td>
        </tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    error_log("Errore log tracciamento: " . $e->getMessage());
    echo "<p>Errore nel caricamento dei log.</p>";
}
?>
