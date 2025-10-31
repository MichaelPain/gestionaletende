<?php
session_start();
require_once '../config/config.php';

echo "<h2>Associazione Veicoli e Operai</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['veicolo_id'], $_POST['operaio_id'])) {
    $veicolo_id = (int) $_POST['veicolo_id'];
    $operaio_id = (int) $_POST['operaio_id'];

    try {
        $stmt = $conn->prepare("INSERT INTO veicolo_operai (veicolo_id, operaio_id) VALUES (:veicolo_id, :operaio_id)");
        $stmt->bindParam(':veicolo_id', $veicolo_id);
        $stmt->bindParam(':operaio_id', $operaio_id);
        $stmt->execute();
        echo "<p>Associazione registrata con successo.</p>";
    } catch (PDOException $e) {
        error_log("Errore associazione veicolo-operaio: " . $e->getMessage());
        echo "<p>Errore nella registrazione dell'associazione.</p>";
    }
}

try {
    $veicoli = $conn->query("SELECT id, modello FROM veicoli");
    $operai = $conn->query("SELECT id, nome FROM operai");
    $associazioni = $conn->query("SELECT vo.id, v.modello, o.nome FROM veicolo_operai vo JOIN veicoli v ON vo.veicolo_id = v.id JOIN operai o ON vo.operaio_id = o.id");
} catch (PDOException $e) {
    die("Errore nel recupero dati: " . $e->getMessage());
}
?>

<form method="POST">
    <label for="veicolo_id">Veicolo:</label>
    <select name="veicolo_id" required>
        <?php while ($v = $veicoli->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['modello']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="operaio_id">Operaio:</label>
    <select name="operaio_id" required>
        <?php while ($o = $operai->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?= $o['id'] ?>"><?= htmlspecialchars($o['nome']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit">Associa</button>
</form>

<h3>Associazioni Correnti</h3>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Veicolo</th>
        <th>Operaio</th>
    </tr>
    <?php while ($a = $associazioni->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= htmlspecialchars($a['id']) ?></td>
            <td><?= htmlspecialchars($a['modello']) ?></td>
            <td><?= htmlspecialchars($a['nome']) ?></td>
        </tr>
    <?php endwhile; ?>
</table>
