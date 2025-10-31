<?php
session_start();
require_once '../config/config.php';

// Aggiunta disponibilità
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['operaio_id'], $_POST['data'])) {
    $operaio_id = (int) $_POST['operaio_id'];
    $data = $_POST['data'];

    try {
        $stmt = $conn->prepare("INSERT INTO disponibilita_operai (operaio_id, data) VALUES (:operaio_id, :data)");
        $stmt->bindParam(':operaio_id', $operaio_id);
        $stmt->bindParam(':data', $data);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Errore inserimento disponibilità: " . $e->getMessage());
    }
}

// Visualizzazione disponibilità
try {
    $disponibilita = $conn->query("SELECT d.id, o.nome, d.data FROM disponibilita_operai d JOIN operai o ON d.operaio_id = o.id ORDER BY d.data");
    $operai = $conn->query("SELECT id, nome FROM operai");
} catch (PDOException $e) {
    die("Errore nel recupero dati: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Disponibilità Operai</title>
</head>
<body>
    <h2>Disponibilità Operai</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Operaio</th>
            <th>Data</th>
        </tr>
        <?php while ($d = $disponibilita->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($d['id']) ?></td>
                <td><?= htmlspecialchars($d['nome']) ?></td>
                <td><?= htmlspecialchars($d['data']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Aggiungi Disponibilità</h3>
    <form method="POST">
        <label for="operaio_id">Operaio:</label>
        <select name="operaio_id" required>
            <?php while ($o = $operai->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['nome']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="data">Data:</label>
        <input type="date" name="data" required><br><br>

        <button type="submit">Aggiungi</button>
    </form>
</body>
</html>