<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';


$stmt = $pdo->prepare("SELECT * FROM utenti WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['utente'] = $user;
    header("Location: dashboard.php");
    exit;
} else {
    $errore = "Credenziali non valide";
}
}

?>
<?php include '../includes/header.php'; ?>
<h2>Login</h2>
<form method="post">
    <label>Username: <input type="text" name="username"></label><br>
    <label>Password: <input type="password" name="password"></label><br>
    <button type="submit">Accedi</button>
</form>
<?php if (isset($errore)) echo "<p style='color:red;'>$errore</p>"; ?>
<?php include '../includes/footer.php'; ?>