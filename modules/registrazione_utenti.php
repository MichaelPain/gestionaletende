<?php
session_start();
require_once '../config/config.php';

// Verifica ruolo utente
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

// Gestione invio form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $ruolo_id = (int) ($_POST['ruolo_id'] ?? 0);

    if ($nome && $email && $password && $ruolo_id) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare("INSERT INTO utenti (nome, email, password_hash, ruolo_id) VALUES (:nome, :email, :password_hash, :ruolo_id)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password_hash', $password_hash);
            $stmt->bindParam(':ruolo_id', $ruolo_id);
            $stmt->execute();
            echo "<p>Utente registrato con successo.</p>";
        } catch (PDOException $e) {
            error_log("Errore registrazione utente: " . $e->getMessage());
            echo "<p>Errore nella registrazione.</p>";
        }
    } else {
        echo "<p>Compila tutti i campi.</p>";
    }
}

// Form HTML
try {
    $ruoli = $conn->query("SELECT id, nome FROM ruoli");
} catch (PDOException $e) {
    die("Errore nel caricamento ruoli: " . $e->getMessage());
}
?>

<h2>Registrazione Utente</h2>
<form method="POST">
    <label for="nome">Nome:</label><br>
    <input type="text" name="nome" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label for="ruolo_id">Ruolo:</label><br>
    <select name="ruolo_id" required>
        <?php while ($r = $ruoli->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nome']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <button type="submit">Registra</button>
</form>