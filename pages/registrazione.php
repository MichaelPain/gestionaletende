<?php
require_once '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $ruolo = $_POST['ruolo'] ?? 'operatore';

    // Hash della password
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO utenti (username, password, ruolo) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashed, $ruolo]);

    echo "<p>Utente registrato con successo!</p>";
}
?>

<?php include '../includes/header.php'; ?>
<h2>Registrazione Utente</h2>
<form method="post">
    <label>Username: <input type="text" name="username" required></label><br>
    <label>Password: <input type="password" name="password" required></label><br>
    <label>Ruolo:
        <select name="ruolo">
            <option value="operatore">Operatore</option>
        </select>
    </label><br>
    <button type="submit">Registra</button>
</form>
<?php include '../includes/footer.php'; ?>