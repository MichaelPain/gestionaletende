<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if (current_user()) {
    redirect('/index.php');
}

$error = null;

if (is_post()) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $token = $_POST['csrf'] ?? '';

    if (!csrf_check($token)) {
        $error = 'Token CSRF non valido.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, password, ruolo FROM utenti WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'ruolo' => $user['ruolo'],
            ];
            redirect('/gestionaletende/index.php');
        } else {
            $error = 'Credenziali non valide.';
        }
    }
}
?>
<?php include __DIR__ . '/../includes/header.php'; ?>
<main class="container">
    <h1>Accesso</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= sanitize($error) ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
        <div class="form-group">
            <label>Username</label>
            <input name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>
        <button class="btn btn-primary" type="submit">Entra</button>
    </form>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
