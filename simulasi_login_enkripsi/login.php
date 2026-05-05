<?php
/**
 * =============================================================================
 * LOGIN — sandi tersimpan sebagai PASSWORD_HASH (bcrypt)
 * =============================================================================
 * Alur:
 * 1) SELECT baris user berdasarkan username (prepared statement).
 * 2) password_verify(input_form, password_hash_dari_db) — true jika sandi cocok.
 *
 * Registrasi / ubah password di aplikasi nyata:
 *   $hash = password_hash($plain, PASSWORD_DEFAULT);
 *   simpan $hash ke kolom password_hash — jangan simpan plain text.
 * =============================================================================
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/fungsi.php';
require_once __DIR__ . '/includes/auth.php';

pastikan_sesi();

if (sudah_login()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $passwordInput = (string) ($_POST['password'] ?? '');

    if ($username === '' || $passwordInput === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT id, username, password_hash, nama FROM users WHERE username = ? LIMIT 1'
        );
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        if ($row !== false && password_verify($passwordInput, (string) $row['password_hash'])) {
            masuk_sesi((int) $row['id'], (string) $row['username'], (string) $row['nama']);
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login (hash) — simulasi_login_enkripsi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-success border-opacity-50">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Login — password ter-hash</h1>
                    <p class="small text-muted mb-2">
                        Folder <strong>simulasi_login_enkripsi</strong>. Kolom di MySQL: <code>password_hash</code> (bcrypt).
                        Lihat phpMyAdmin: Anda tidak melihat sandi asli, hanya string hash.
                    </p>
                    <?php if ($error !== '') { ?>
                        <div class="alert alert-danger py-2 small"><?= h($error) ?></div>
                    <?php } ?>
                    <form method="post" action="login.php" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input class="form-control" type="text" name="username" id="username" required value="<?= h((string) ($_POST['username'] ?? '')) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input class="form-control" type="password" name="password" id="password" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Masuk</button>
                    </form>
                    <hr>
                    <p class="small mb-0 text-secondary">Demo (ketik di form): <code>admin</code> / <code>admin123</code> · <code>mahasiswa</code> / <code>rahasia</code></p>
                    <p class="small mt-2 mb-0"><a href="../simulasi_login/">← Versi sandi teks polos di folder <code>simulasi_login</code></a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
