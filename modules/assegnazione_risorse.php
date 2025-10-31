<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evento_id'], $_POST['risorsa_tipo'], $_POST['risorsa_id'])) {
    $evento_id = (int) $_POST['evento_id'];
    $risorsa_tipo = $_POST['risorsa_tipo'];
    $risorsa_id = (int) $_POST['risorsa_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO assegnazioni_risorse (evento_id, risorsa_tipo, risorsa_id) VALUES (:evento_id, :risorsa_tipo, :risorsa_id)");
        $stmt->bindParam(':evento_id', $evento_id);
        $stmt->bindParam(':risorsa_tipo', $risorsa_tipo);
        $stmt->bindParam(':risorsa_id', $risorsa_id);
        $stmt->execute();
        echo "<p>Risorsa assegnata con successo.</p>";
    } catch (PDOException $e) {
        error_log("Errore assegnazione risorsa: " . $e->getMessage());
        echo "<p>Errore durante l'assegnazione della risorsa.</p>";
    }
}

try {
    $eventi = $conn->query("SELECT id, localizzazione, data_evento FROM eventi ORDER BY data_evento ASC");
    $veicoli = $conn->query("SELECT id, modello FROM veicoli");
    $operai = $conn->query("SELECT id, nome FROM operai");
    $attrezzature = $conn->query("SELECT id, nome FROM attrezzature");
} catch (PDOException $e) {
    die("Errore nel recupero dati: " . $e->getMessage());
}
?>

<h2>Assegnazione Risorse agli Eventi</h2>
<form method="POST">
    <label for="evento_id">Evento:</label>
    <select name="evento_id" required>
        <?php while ($e = $eventi->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['localizzazione']) ?> - <?= htmlspecialchars($e['data_evento']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="risorsa_tipo">Tipo Risorsa:</label>
    <select name="risorsa_tipo" required>
        <option value="veicolo">Veicolo</option>
        <option value="operaio">Operaio</option>
        <option value="attrezzatura">Attrezzatura</option>
    </select><br><br>

    <label for="risorsa_id">ID Risorsa:</label>
    <input type="number" name="risorsa_id" required><br><br>

    <button type="submit">Assegna Risorsa</button>
</form>
