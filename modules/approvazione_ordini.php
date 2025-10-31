<?php
session_start();
require_once '../config/config.php';

// Gestione approvazione o rifiuto ordine
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ordine_id'], $_POST['azione'])) {
    $ordine_id = (int) $_POST['ordine_id'];
    $azione = $_POST['azione'];

    if (in_array($azione, ['approvato', 'rifiutato'])) {
        try {
            $stmt = $conn->prepare("UPDATE ordini SET stato = :azione WHERE id = :ordine_id AND tipo = 'noleggio'");
            $stmt->bindParam(':azione', $azione);
            $stmt->bindParam(':ordine_id', $ordine_id);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Errore aggiornamento ordine: " . $e->getMessage());
        }
    }
    header('Location: approvazione_ordini.php');
    exit;
}

// Recupero ordini in attesa
try {
    $result = $conn->query("SELECT o.id, c.nome AS cliente_nome FROM ordini o JOIN clienti c ON o.cliente_id = c.id WHERE o.tipo = 'noleggio' AND o.stato = 'in_attesa'");
} catch (PDOException $e) {
    die("Errore nel recupero degli ordini: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Approvazione Ordini di Noleggio</title>
</head>
<body>
    <h2>Approvazione Ordini di Noleggio</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Azioni</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['cliente_nome']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="ordine_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="azione" value="approvato">Approva</button>
                        <button type="submit" name="azione" value="rifiutato">Rifiuta</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>