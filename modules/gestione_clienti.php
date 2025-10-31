<?php
session_start();
require_once '../config/config.php';

echo "<h2>Gestione Clienti</h2>";

// Aggiunta cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aggiungi'])) {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    if ($nome && $email && $telefono) {
        try {
            $stmt = $conn->prepare("INSERT INTO clienti (nome, email, telefono) VALUES (:nome, :email, :telefono)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->execute();
            echo "<p>Cliente aggiunto con successo.</p>";
        } catch (PDOException $e) {
            error_log("Errore inserimento cliente: " . $e->getMessage());
            echo "<p>Errore nell'aggiunta del cliente.</p>";
        }
    } else {
        echo "<p>Compila tutti i campi.</p>";
    }
}

// Visualizzazione clienti
try {
    $clienti = $conn->query("SELECT id, nome, email, telefono FROM clienti ORDER BY nome ASC");
    echo "<table border='1'><tr><th>ID</th><th>Nome</th><th>Email</th><th>Telefono</th></tr>";
    while ($c = $clienti->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>" . htmlspecialchars($c['id']) . "</td><td>" . htmlspecialchars($c['nome']) . "</td><td>" . htmlspecialchars($c['email']) . "</td><td>" . htmlspecialchars($c['telefono']) . "</td></tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    error_log("Errore visualizzazione clienti: " . $e->getMessage());
    echo "<p>Errore nel caricamento dei clienti.</p>";
}
?>

<h3>Aggiungi Cliente</h3>
<form method="POST">
    <input type="hidden" name="aggiungi" value="1">
    <label for="nome">Nome:</label><br>
    <input type="text" name="nome" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label for="telefono">Telefono:</label><br>
    <input type="text" name="telefono" required><br><br>

    <button type="submit">Aggiungi</button>
</form>
