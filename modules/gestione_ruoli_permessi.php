<?php
session_start();
require_once '../config/config.php';

// Verifica accesso admin
$utente_id = $_SESSION['user_id'] ?? null;
if (!$utente_id) {
    echo "Accesso negato.";
    exit;
}

try {
    $stmt = $conn->prepare("SELECT r.nome FROM utenti u JOIN ruoli r ON u.ruolo_id = r.id WHERE u.id = :utente_id");
    $stmt->bindParam(':utente_id', $utente_id);
    $stmt->execute();
    $ruolo = $stmt->fetch(PDO::FETCH_ASSOC)['nome'] ?? '';

    if ($ruolo !== 'admin') {
        echo "Accesso negato.";
        exit;
    }
} catch (PDOException $e) {
    die("Errore verifica ruolo: " . $e->getMessage());
}

// Assegnazione permesso a ruolo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ruolo_id'], $_POST['permesso_id'])) {
    $ruolo_id = (int) $_POST['ruolo_id'];
    $permesso_id = (int) $_POST['permesso_id'];

    try {
        $stmt = $conn->prepare("INSERT IGNORE INTO ruolo_permessi (ruolo_id, permesso_id) VALUES (:ruolo_id, :permesso_id)");
        $stmt->bindParam(':ruolo_id', $ruolo_id);
        $stmt->bindParam(':permesso_id', $permesso_id);
        $stmt->execute();
        echo "<p>Permesso assegnato correttamente.</p>";
    } catch (PDOException $e) {
        error_log("Errore assegnazione permesso: " . $e->getMessage());
        echo "<p>Errore nell'assegnazione del permesso.</p>";
    }
}

// Recupero ruoli e permessi
try {
    $ruoli = $conn->query("SELECT id, nome FROM ruoli");
    $permessi = $conn->query("SELECT id, nome FROM permessi");
    $assegnazioni = $conn->query("
        SELECT r.nome AS ruolo, p.nome AS permesso
        FROM ruolo_permessi rp
        JOIN ruoli r ON rp.ruolo_id = r.id
        JOIN permessi p ON rp.permesso_id = p.id
        ORDER BY r.nome, p.nome
    ");
} catch (PDOException $e) {
    die("Errore nel recupero dati: " . $e->getMessage());
}
?>

<h2>Gestione Ruoli e Permessi</h2>

<h3>Assegna Permesso a Ruolo</h3>
<form method="POST">
    <label for="ruolo_id">Ruolo:</label>
    <select name="ruolo_id" required>
        <?php while ($r = $ruoli->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nome']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="permesso_id">Permesso:</label>
    <select name="permesso_id" required>
        <?php while ($p = $permessi->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit">Assegna</button>
</form>

<h3>Permessi Assegnati</h3>
<table border="1">
    <tr>
        <th>Ruolo</th>
        <th>Permesso</th>
    </tr>
    <?php while ($a = $assegnazioni->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?= htmlspecialchars($a['ruolo']) ?></td>
            <td><?= htmlspecialchars($a['permesso']) ?></td>
        </tr>
    <?php endwhile; ?>
</table>
