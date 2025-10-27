<?php
session_start();
if (!isset($_SESSION['utente'])) {
    header("Location: login.php");
    exit;
}
include '../includes/header.php';
?>
<h2>Dashboard</h2>
<p>Benvenuto, <?php echo $_SESSION['utente']['username']; ?>!</p>
<ul>
    <li><a href="ordini.php">Gestione Ordini</a></li>
    <li><a href="inventario.php">Gestione Inventario</a></li>
    <li><a href="clienti.php">Gestione Clienti</a></li>
    <li><a href="admin.php">Admin</a></li>
</ul>
<?php include '../includes/footer.php'; ?>