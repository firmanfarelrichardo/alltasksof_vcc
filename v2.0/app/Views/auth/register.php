<?php

use App\Core\Session;

$title = 'Register - Zeta';
$theme = 'dark';
$bodyClass = 'auth-page';
$error = Session::consumeFlash('error');

require BASE_PATH . '/app/Views/layouts/public-header.php';
?>

<main class="auth-shell">
    <section class="auth-card">
        <h1>Register User</h1>
        <p>Akun user baru akan berstatus pending sampai disetujui superadmin.</p>

        <?php if ($error): ?>
            <p class="flash flash-error" role="alert"><?= e($error) ?></p>
        <?php endif; ?>

        <form method="post" action="<?= e(url('/register')) ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name">Nama</label>
                <input class="form-control" id="name" name="name" type="text" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" id="email" name="email" type="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" minlength="8" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" minlength="8" required>
            </div>

            <button class="btn btn-primary" type="submit">Daftar</button>
        </form>

        <p class="form-note"><a href="<?= e(url('/login')) ?>">Sudah punya akun? Login</a></p>
    </section>
</main>

<?php require BASE_PATH . '/app/Views/layouts/public-footer.php'; ?>
