<?php
session_start();
if (isset($_SESSION['utente'])) {
    header("Location: pages/dashboard.php");
} else {
    header("Location: pages/login.php");
}
exit;
?>