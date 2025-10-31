<?php
session_start();
require_once '../config/config.php';

try {
    $result = $conn->query("SELECT * FROM eventi ORDER BY data_evento ASC");
} catch (PDOException $e) {
    die("Errore nel recupero eventi: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Calendario Eventi</title>
</head>
<body>
    <h2>Calendario Eventi</h2>
    <table border="1">
        <tr>
            <th>Data</th>
            <th>Localizzazione</th>
            <th>ID Ordine</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['data_evento']) ?></td>
                <td><?= htmlspecialchars($row['localizzazione']) ?></td>
                <td><?= htmlspecialchars($row['ordine_id']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>