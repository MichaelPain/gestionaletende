<?php
session_start();
require_once '../config/config.php';

// Aggiunta manutenzione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aggiungi'])) {
    $attrezzatura_id = (int) $_POST['attrezzatura_id'];
    $data_inizio = $_POST['data_inizio'];
    $note = $_POST['note'];

    try {
        $stmt = $conn->prepare("INSERT INTO manutenzioni (attrezzatura_id, data_inizio, note) VALUES (:attrezzatura_id, :data_inizio, :note)");
        $stmt->bindParam(':attrezzatura_id', $attrezzatura_id);
        $stmt->bindParam(':data_inizio', $data_inizio);
        $stmt->bindParam(':note', $note);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Errore inserimento manutenzione: " . $e->getMessage());
    }
}

// Chiusura manutenzione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['chiudi'])) {
    $id = (int) $_POST['manutenzione_id'];
    $data_fine = $_POST['data_fine'];

    try {
        $stmt = $conn->prepare("UPDATE manutenzioni SET data_fine = :data_fine WHERE id = :id");
        $stmt->bindParam(':data_fine', $data_fine);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log("Errore chiusura manutenzione: " . $e->getMessage());
    }
}

// Visualizzazione
try {
    $manutenzioni = $conn->query("SELECT m.id, a.nome, m.data_inizio, m.data_fine, m.note FROM manutenzioni m JOIN attrezzature a ON m.attrezzatura_id = a.id");
    $attrezzature = $conn->query("SELECT id, nome FROM attrezzature");
} catch (PDOException $e) {
    die("Errore nel recupero dati: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Manutenzioni</title>
</head>
<body>
    <h2>Gestione Manutenzioni</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Attrezzatura</th>
            <th>Inizio</th>
            <th>Fine</th>
            <th>Note</th>
        </tr>
        <?php while ($m = $manutenzioni->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($m['id']) ?></td>
                <td><?= htmlspecialchars($m['nome']) ?></td>
                <td><?= htmlspecialchars($m['data_inizio']) ?></td>
                <td><?= htmlspecialchars($m['data_fine']) ?></td>
                <td><?= htmlspecialchars($m['note']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Aggiungi Manutenzione</h3>
    <form method="POST">
        <input type="hidden" name="aggiungi" value="1">
        <label for="attrezzatura_id">Attrezzatura:</label>
        <select name="attrezzatura_id" required>
            <?php while ($a = $attrezzature->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nome']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="data_inizio">Data Inizio:</label>
        <input type="date" name="data_inizio" required><br><br>

        <label for="note">Note:</label>
        <textarea name="note"></textarea><br><br>

        <button type="submit">Aggiungi</button>
    </form>

    <h3>Chiudi Manutenzione</h3>
    <form method="POST">
        <input type="hidden" name="chiudi" value="1">
        <label for="manutenzione_id">ID Manutenzione:</label>
        <input type="number" name="manutenzione_id" required><br><br>

        <label for="data_fine">Data Fine:</label>
        <input type="date" name="data_fine" required><br><br>

        <button type="submit">Chiudi</button>
    </form>
</body>
</html>