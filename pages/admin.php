<?php
session_start();
require_once '../includes/db.php';
include '../includes/header.php';

if (!isset($_SESSION['utente']) || $_SESSION['utente']['ruolo'] !== 'admin') {
    echo "<p>Accesso negato. Solo gli admin possono accedere a questa pagina.</p>";
    include '../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifica'])) {
    $id = $_POST['id'];
    $nuova_password = $_POST['nuova_password'];
    $hashed = password_hash($nuova_password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE utenti SET password = ? WHERE id = ?");
    $stmt->execute([$hashed, $id]);
    echo "<p>Password aggiornata per l'utente ID $id</p>";
}

$utenti = $pdo->query("SELECT id, username, ruolo FROM utenti")->fetchAll();
?>

<h2>Gestione Utenti</h2>
<table border="1">
    <tr><th>ID</th><th>Username</th><th>Ruolo</th><th>Nuova Password</th><th>Azioni</th></tr>
    <?php foreach ($utenti as $u): ?>
    <tr>
        <form method="post">
            <td><?= $u['id'] ?></td>
            <td><?= $u['username'] ?></td>
            <td><?= $u['ruolo'] ?></td>
            <td><input type="text" name="nuova_password" required></td>
            <td>
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button type="submit" name="modifica">Aggiorna</button>
            </td>
        </form>
    </tr>
    <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>