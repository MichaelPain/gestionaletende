<?php
session_start();
if (!isset($_SESSION['utente'])) {
    header("Location: login.php");
    exit;
}
require_once '../includes/db.php';
include '../includes/header.php';

// Inserimento nuovo ordine
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nuovo'])) {
    $id_cliente = $_POST['id_cliente'];
    $data_ordine = $_POST['data_ordine'];
    $tipo = $_POST['tipo'];
    $stato = $_POST['stato'];
    $note = $_POST['note'];

    $stmt = $pdo->prepare("INSERT INTO ordini (id_cliente, data_ordine, tipo, stato, note) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_cliente, $data_ordine, $tipo, $stato, $note]);
    echo "<p>Ordine inserito con successo!</p>";
}

// Modifica ordine esistente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifica'])) {
    $id = $_POST['id'];
    $stato = $_POST['stato'];
    $note = $_POST['note'];

    $stmt = $pdo->prepare("UPDATE ordini SET stato = ?, note = ? WHERE id = ?");
    $stmt->execute([$stato, $note, $id]);
    echo "<p>Ordine aggiornato!</p>";
}

// Visualizzazione ordini
$stmt = $pdo->query("SELECT o.id, c.nome AS cliente, o.data_ordine, o.tipo, o.stato, o.note FROM ordini o JOIN clienti c ON o.id_cliente = c.id");
$ordini = $stmt->fetchAll();
?>

<h2>Ordini</h2>
<table border="1">
    <tr><th>ID</th><th>Cliente</th><th>Data</th><th>Tipo</th><th>Stato</th><th>Note</th><th>Modifica</th></tr>
    <?php foreach ($ordini as $ordine): ?>
    <tr>
        <form method="post">
            <td><?= $ordine['id'] ?></td>
            <td><?= $ordine['cliente'] ?></td>
            <td><?= $ordine['data_ordine'] ?></td>
            <td><?= $ordine['tipo'] ?></td>
            <td>
                <select name="stato">
                    <option value="In preparazione" <?= $ordine['stato']=='In preparazione'?'selected':'' ?>>In preparazione</option>
                    <option value="In corso" <?= $ordine['stato']=='In corso'?'selected':'' ?>>In corso</option>
                    <option value="Completato" <?= $ordine['stato']=='Completato'?'selected':'' ?>>Completato</option>
                    <option value="Annullato" <?= $ordine['stato']=='Annullato'?'selected':'' ?>>Annullato</option>
                </select>
            </td>
            <td><input type="text" name="note" value="<?= htmlspecialchars($ordine['note']) ?>"></td>
            <td>
                <input type="hidden" name="id" value="<?= $ordine['id'] ?>">
                <button type="submit" name="modifica">Salva</button>
            </td>
        </form>
    </tr>
    <?php endforeach; ?>
</table>

<h3>Nuovo Ordine</h3>
<form method="post">
    Cliente:
    <select name="id_cliente">
        <?php
        $clienti = $pdo->query("SELECT id, nome FROM clienti")->fetchAll();
        foreach ($clienti as $c) {
            echo "<option value='{$c['id']}'>{$c['nome']}</option>";
        }
        ?>
    </select><br>
    Data: <input type="date" name="data_ordine"><br>
    Tipo:
    <select name="tipo">
        <option value="Vendita">Vendita</option>
        <option value="Noleggio">Noleggio</option>
    </select><br>
    Stato:
    <select name="stato">
        <option value="In preparazione">In preparazione</option>
        <option value="In corso">In corso</option>
        <option value="Completato">Completato</option>
        <option value="Annullato">Annullato</option>
    </select><br>
    Note: <input type="text" name="note"><br>
    <button type="submit" name="nuovo">Inserisci Ordine</button>
</form>

<?php include '../includes/footer.php'; ?>