<?php
session_start();
require_once '../config/config.php';

try {
    $result = $conn->query("SELECT o.id, c.nome AS cliente_nome, o.tipo, o.stato FROM ordini o JOIN clienti c ON o.cliente_id = c.id");
} catch (PDOException $e) {
    die("Errore nel recupero degli ordini: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Ordini</title>
</head>
<body>
    <h2>Gestione Ordini</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Tipo</th>
            <th>Stato</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['cliente_nome']) ?></td>
                <td><?= htmlspecialchars($row['tipo']) ?></td>
                <td><?= htmlspecialchars($row['stato']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>