<?php
session_start();
if (!isset($_SESSION['utente'])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/db.php';
include '../includes/header.php';

$tende = $pdo->query("SELECT * FROM tende")->fetchAll();
$attrezzature = $pdo->query("SELECT * FROM attrezzature")->fetchAll();
?>
<h2>Inventario Tende</h2>
<table border="1">
    <tr><th>Nome</th><th>Tipo</th><th>Disponibili</th></tr>
    <?php foreach ($tende as $t): ?>
    <tr>
        <td><?= $t['nome'] ?></td>
        <td><?= $t['tipo'] ?></td>
        <td><?= $t['quantita_disponibile'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Inventario Attrezzature</h2>
<table border="1">
    <tr><th>Nome</th><th>Disponibili</th></tr>
    <?php foreach ($attrezzature as $a): ?>
    <tr>
        <td><?= $a['nome'] ?></td>
        <td><?= $a['quantita_disponibile'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>