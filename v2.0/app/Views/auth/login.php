<?php

use App\Core\Session;

$title = 'Login - Zeta';
$theme = 'dark';
$bodyClass = 'auth-page';
$error = Session::consumeFlash('error');
$success = Session::consumeFlash('success');

require BASE_PATH . '/app/Views/layouts/public-header.php';
?>

<main class="auth-shell">
    <section class="auth-card">
        <h1>Login</h1>
        <p>Masuk setelah akun disetujui oleh superadmin.</p>

        <?php if ($error): ?>
            <p class="flash flash-error" role="alert"><?= e($error) ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p class="flash flash-success" role="status"><?= e($success) ?></p>
        <?php endif; ?>

        <form method="post" action="<?= e(url('/login')) ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" id="email" name="email" type="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" required>
            </div>

            <button class="btn btn-primary" type="submit">Login</button>
        </form>

        <p class="form-note"><a href="<?= e(url('/register')) ?>">Daftar akun user</a></p>
    </section>
</main>

<?php require BASE_PATH . '/app/Views/layouts/public-footer.php'; ?>
