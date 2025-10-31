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

// Aggiunta nuovo utente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aggiungi'])) {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $ruolo_id = (int) ($_POST['ruolo_id'] ?? 0);

    if ($nome && $email && $username && $password && $ruolo_id) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare("INSERT INTO utenti (nome, email, username, password_hash, ruolo_id) VALUES (:nome, :email, :username, :password_hash, :ruolo_id)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password_hash', $password_hash);
            $stmt->bindParam(':ruolo_id', $ruolo_id);
            $stmt->execute();
            echo "<p>Utente aggiunto con successo.</p>";
        } catch (PDOException $e) {
            error_log("Errore inserimento utente: " . $e->getMessage());
            echo "<p>Errore durante l'inserimento dell'utente.</p>";
        }
    } else {
        echo "<p>Compila tutti i campi.</p>";
    }
}

// Recupero utenti e ruoli
try {
    $utenti = $conn->query("SELECT u.id, u.nome, u.email, u.username, r.nome AS ruolo FROM utenti u JOIN ruoli r ON u.ruolo_id = r.id");
    $ruoli = $conn->query("SELECT id, nome FROM ruoli");
} catch (PDOException $e) {
    die("Errore nel recupero dati utenti: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Utenti</title>
</head>
<body>
    <h2>Gestione Utenti</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Username</th>
            <th>Ruolo</th>
        </tr>
        <?php while ($u = $utenti->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['nome']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['ruolo']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h3>Aggiungi Nuovo Utente</h3>
    <form method="POST">
        <input type="hidden" name="aggiungi" value="1">
        <label for="nome">Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="username">Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label for="ruolo_id">Ruolo:</label><br>
        <select name="ruolo_id" required>
            <?php while ($r = $ruoli->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['nome']) ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <button type="submit">Aggiungi Utente</button>
    </form>
</body>
</html>
