<?php
session_start();
if (!isset($_SESSION['utente'])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/db.php';
include '../includes/header.php';

$stmt = $pdo->query("SELECT * FROM clienti");
$clienti = $stmt->fetchAll();
?>
<h2>Clienti</h2>
<table border="1">
    <tr><th>Nome</th><th>Tipo</th><th>Email</th><th>Telefono</th></tr>
    <?php foreach ($clienti as $c): ?>
    <tr>
        <td><?= $c['nome'] ?></td>
        <td><?= $c['tipo'] ?></td>
        <td><?= $c['email'] ?></td>
        <td><?= $c['telefono'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>