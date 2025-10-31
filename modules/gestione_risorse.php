<?php
session_start();
require_once '../config/config.php';

echo "<h2>Gestione Risorse</h2>";

try {
    // Veicoli
    $veicoli = $conn->query("SELECT modello, targa FROM veicoli");
    echo "<h3>Veicoli</h3><ul>";
    while ($v = $veicoli->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($v['modello']) . " - " . htmlspecialchars($v['targa']) . "</li>";
    }
    echo "</ul>";

    // Operai
    $operai = $conn->query("SELECT nome, specializzazione FROM operai");
    echo "<h3>Operai</h3><ul>";
    while ($o = $operai->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($o['nome']) . " - " . htmlspecialchars($o['specializzazione']) . "</li>";
    }
    echo "</ul>";

    // Attrezzature
    $attrezzature = $conn->query("SELECT nome, stato FROM attrezzature");
    echo "<h3>Attrezzature</h3><ul>";
    while ($a = $attrezzature->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($a['nome']) . " - " . htmlspecialchars($a['stato']) . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    error_log("Errore nella gestione risorse: " . $e->getMessage());
    echo "<p>Errore nel caricamento delle risorse.</p>";
}
?>