<?php
session_start();
require_once '../config/config.php';

echo "<h2>Elenco Ordini</h2>";

try {
    $ordini = $conn->query("SELECT o.id, c.nome AS cliente_nome, o.tipo, o.stato FROM ordini o JOIN clienti c ON o.cliente_id = c.id ORDER BY o.id DESC");

    echo "<table border='1'><tr><th>ID</th><th>Cliente</th><th>Tipo</th><th>Stato</th></tr>";
    while ($o = $ordini->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>" . htmlspecialchars($o['id']) . "</td><td>" . htmlspecialchars($o['cliente_nome']) . "</td><td>" . htmlspecialchars($o['tipo']) . "</td><td>" . htmlspecialchars($o['stato']) . "</td></tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    error_log("Errore ordini: " . $e->getMessage());
    echo "<p>Errore nel caricamento degli ordini.</p>";
}
?>
