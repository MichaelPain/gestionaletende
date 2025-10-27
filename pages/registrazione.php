<?php
session_start();
require_once '../includes/db.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $ruolo = 'operatore'; // ruolo fisso

    // Controlla se l'username esiste già
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utenti WHERE username = ?");
    $stmt->execute([$username]);
    $esiste = $stmt->fetchColumn();

    if ($esiste > 0) {
        echo "<p style='color:red;'>Username già esistente. Scegli un altro nome.</p>";
    } else {
        // Hash della password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Inserisci nuovo utente
        $stmt = $pdo->prepare("INSERT INTO utenti (username, password, ruolo) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed, $ruolo]);

        echo "<p>Utente registrato con successo!</p>";
    }
}
?>

<h2>Registrazione Utente</h2>
<form method="post">
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <button type="submit">Registra</button>
</form>

<?php include '../includes/footer.php'; ?>
