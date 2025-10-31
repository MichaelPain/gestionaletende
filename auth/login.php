<?php
session_start();
require_once '../config/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $conn->prepare('SELECT id, password_hash FROM utenti WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: ../modules/dashboard.php');
                exit;
            } else {
                $error = 'Credenziali non valide.';
            }
        } catch (PDOException $e) {
            error_log("Errore login: " . $e->getMessage());
            $error = 'Errore di connessione al database.';
        }
    } else {
        $error = 'Inserisci username e password.';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - Gestionale Tende Bedouin</title>
    ../assets/style.css
</head>
<body>
    <h2>Accesso al Gestionale</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="username">Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Accedi</button>
    </form>
</body>
</html>