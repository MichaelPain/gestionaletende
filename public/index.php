<?php
session_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestionale Tende Bedouin</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script src="../assets/script.js" defer></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <h1>Benvenuto nel Gestionale Tende Bedouin</h1>
        <p>Utilizza il menu per accedere alle funzionalità di gestione ordini, risorse, eventi e report.</p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Accesso effettuato. Vai alla <a href="../modules/dashboard.php">Dashboard</a>.</p>
        <?php else: ?>
            <p><a href="../auth/login.php">Accedi</a> per iniziare.</p>
        <?php endif; ?>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
