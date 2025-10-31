<?php
session_start();
require_once '../config/config.php';

echo "<h2>Blocco Risorse per Eventi</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evento_id'], $_POST['risorsa_tipo'], $_POST['risorsa_id'])) {
    $evento_id = (int) $_POST['evento_id'];
    $risorsa_tipo = $_POST['risorsa_tipo'];
    $risorsa_id = (int) $_POST['risorsa_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO blocco_risorse_eventi (evento_id, risorsa_tipo, risorsa_id) VALUES (:evento_id, :risorsa_tipo, :risorsa_id)");
        $stmt->bindParam(':evento_id', $evento_id);
        $stmt->bindParam(':risorsa_tipo', $risorsa_tipo);
        $stmt->bindParam(':risorsa_id', $risorsa_id);
        $stmt->execute();
        echo "<p>Risorsa bloccata correttamente.</p>";
    } catch (PDOException $e) {
        error_log("Errore blocco risorsa: " . $e->getMessage());
        echo "<p>Errore nel blocco della risorsa.</p>";
    }
}

try {
    $eventi = $conn->query("SELECT id, localizzazione FROM eventi ORDER BY data_evento DESC");
    $veicoli = $conn->query("SELECT id, modello FROM veicoli");
    $operai = $conn->query("SELECT id, nome FROM operai");
    $attrezzature = $conn->query("SELECT id, nome FROM attrezzature");
} catch (PDOException $e) {
    die("Errore nel recupero dati: " . $e->getMessage());
}
?>

<form method="POST">
    <label for="evento_id">Evento:</label>
    <select name="evento_id" required>
        <?php while ($e = $eventi->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['localizzazione']) ?> (ID <?= $e['id'] ?>)</option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="risorsa_tipo">Tipo Risorsa:</label>
    <select name="risorsa_tipo" required>
        <option value="veicolo">Veicolo</option>
        <option value="operaio">Operaio</option>
        <option value="attrezzatura">Attrezzatura</option>
    </select><br><br>

    <label for="risorsa_id">Risorsa:</label>
    <select name="risorsa_id" required>
        <?php
        foreach ($veicoli as $v) {
            echo "<option value='{$v['id']}'>Veicolo: " . htmlspecialchars($v['modello']) . "</option>";
        }
        foreach ($operai as $o) {
            echo "<option value='{$o['id']}'>Operaio: " . htmlspecialchars($o['nome']) . "</option>";
        }
        foreach ($attrezzature as $a) {
            echo "<option value='{$a['id']}'>Attrezzatura: " . htmlspecialchars($a['nome']) . "</option>";
        }
        ?>
    </select><br><br>

    <button type="submit">Blocca Risorsa</button>
</form>
