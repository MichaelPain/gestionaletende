<?php
session_start();
require_once '../config/config.php';

echo "<h2>Gestione Articoli Ordine</h2>";

// Inserimento nuovo articolo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ordine_id'], $_POST['descrizione'], $_POST['quantita'])) {
    $ordine_id = (int) $_POST['ordine_id'];
    $descrizione = trim($_POST['descrizione']);
    $quantita = (int) $_POST['quantita'];

    if ($ordine_id > 0 && $descrizione && $quantita > 0) {
        try {
            $stmt = $conn->prepare("INSERT INTO ordine_item (ordine_id, descrizione, quantita) VALUES (:ordine_id, :descrizione, :quantita)");
            $stmt->bindParam(':ordine_id', $ordine_id);
            $stmt->bindParam(':descrizione', $descrizione);
            $stmt->bindParam(':quantita', $quantita);
            $stmt->execute();
            echo "<p>Articolo aggiunto con successo.</p>";
        } catch (PDOException $e) {
            error_log("Errore inserimento articolo: " . $e->getMessage());
            echo "<p>Errore nell'aggiunta dell'articolo.</p>";
        }
    } else {
        echo "<p>Compila tutti i campi correttamente.</p>";
    }
}

// Visualizzazione articoli
try {
    $articoli = $conn->query("SELECT oi.id, oi.ordine_id, oi.descrizione, oi.quantita FROM ordine_item oi ORDER BY oi.ordine_id");
    echo "<table border='1'><tr><th>ID</th><th>Ordine</th><th>Descrizione</th><th>Quantità</th></tr>";
    while ($a = $articoli->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>" . htmlspecialchars($a['id']) . "</td>
                <td>" . htmlspecialchars($a['ordine_id']) . "</td>
                <td>" . htmlspecialchars($a['descrizione']) . "</td>
                <td>" . htmlspecialchars($a['quantita']) . "</td>
              </tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    error_log("Errore visualizzazione articoli: " . $e->getMessage());
    echo "<p>Errore nel caricamento degli articoli.</p>";
}

// Recupero ordini per selezione
try {
    $ordini = $conn->query("SELECT id FROM ordini ORDER BY id DESC");
} catch (PDOException $e) {
    die("Errore nel recupero ordini: " . $e->getMessage());
}
?>

<h3>Aggiungi Articolo a Ordine</h3>
<form method="POST">
    <label for="ordine_id">Ordine:</label>
    <select name="ordine_id" required>
        <?php while ($o = $ordini->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?= $o['id'] ?>">Ordine #<?= $o['id'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="descrizione">Descrizione:</label><br>
    <input type="text" name="descrizione" required><br><br>

    <label for="quantita">Quantità:</label><br>
    <input type="number" name="quantita" min="1" required><br><br>

    <button type="submit">Aggiungi Articolo</button>
</form>
