<?php
session_start();
require_once '../config/config.php';

$backup_dir = '../backup/';
$filename = 'backup_' . date('Ymd_His') . '.sql';
$filepath = $backup_dir . $filename;

$host = 'localhost';
$dbname = 'gestionale_tende';
$username = 'tuoaccount';
$password = 'tuapassword';

$command = "mysqldump -h $host -u $username -p$password $dbname > $filepath";

$output = null;
$return_var = null;
exec($command, $output, $return_var);

if ($return_var === 0) {
    echo "<p>Backup completato con successo: $filename</p>";
} else {
    echo "<p>Errore durante il backup. Codice: $return_var</p>";
}
?>
