<?php
/**
 * =============================================================================
 * HALAMAN LOGIN — simulasi_login
 * =============================================================================
 * Alur pembelajaran:
 * 1) GET: tampilkan form (username + password).
 * 2) POST: baca input, cari baris di tabel users dengan prepared statement.
 * 3) Bandingkan password form dengan kolom password di database.
 *    Di latihan ini keduanya teks polos (bukan bcrypt). Di produksi gunakan
 *    password_verify($input, $hash_dari_db).
 * 4) Jika cocok: masuk_sesi(...) lalu redirect ke dashboard (hindari resubmit POST).
 * 5) Jika tidak: tampilkan pesan error di form (tanpa menyebut "username salah"
 *    atau "password salah" terpisah — mengurangi bocoran untuk penyerang).
 *
 * hash_equals() dipakai membandingkan string sensitif (timing lebih aman dari !=).
 *
 * Jika user sudah login lalu membuka login.php lagi: redirect ke dashboard
 * supaya tidak mengisi form tanpa perlu.
 * =============================================================================
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/fungsi.php';
require_once __DIR__ . '/includes/auth.php';

pastikan_sesi();

// Sudah login → tidak perlu form; langsung ke dashboard
if (sudah_login()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Data form dikirim lewat POST (tidak muncul di URL, lebih pantas untuk password)
    $username = trim((string) ($_POST['username'] ?? ''));
    $passwordInput = (string) ($_POST['password'] ?? '');

    if ($username === '' || $passwordInput === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        // Satu placeholder ? — nilai diikat lewat execute([...]) → aman dari SQL injection
        $stmt = $pdo->prepare('SELECT id, username, password, nama FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        // Latihan: string sama persis. Produksi: kolom password_hash + password_verify()
        if ($row && hash_equals((string) $row['password'], $passwordInput)) {
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
    <!-- viewport: tampilan responsif di perangkat mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Simulasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<!-- min-height 100vh + flex: kartu login terasa "di tengah" layar vertikal -->
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-3">Simulasi login</h1>
                    <p class="small text-muted">Password di database <strong>tidak di-hash</strong> (untuk pembelajaran). Lihat isi tabel <code>users</code> di phpMyAdmin.</p>
                    <?php if ($error !== '') { ?>
                        <div class="alert alert-danger py-2 small"><?= h($error) ?></div>
                    <?php } ?>
                    <!-- method="post": kirim data lewat body request, bukan query string -->
                    <form method="post" action="login.php" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <!-- value= POST lama: jika gagal login, username tetap terisi (UX) -->
                            <input class="form-control" type="text" name="username" id="username" required value="<?= h((string) ($_POST['username'] ?? '')) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <!-- type="password": karakter disembunyikan di layar (tetap teks polos di DB latihan) -->
                            <input class="form-control" type="password" name="password" id="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </form>
                    <hr>
                    <p class="small mb-0 text-secondary">Demo: <code>admin</code> / <code>admin123</code> · <code>mahasiswa</code> / <code>rahasia</code></p>
                    <p class="small mt-2 mb-0"><a href="../simulasi_login_enkripsi/">Versi login dengan password ter-hash (bcrypt) → folder <code>simulasi_login_enkripsi</code></a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
