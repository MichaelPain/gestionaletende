<?php
// Configurazione connessione database per gestionale Tende Bedouin

$host = 'localhost';
$dbname = 'gestionale_tende';
$username = 'tuoaccount';
$password = 'tuapassword'; // Sostituire con la password reale

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Errore connessione DB: " . $e->getMessage());
    die("Connessione al database non riuscita.");
}
?>