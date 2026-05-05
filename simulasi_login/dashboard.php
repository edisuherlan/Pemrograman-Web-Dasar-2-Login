<?php
/**
 * =============================================================================
 * DASHBOARD — simulasi_login (password teks polos di database)
 * =============================================================================
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/fungsi.php';
require_once __DIR__ . '/includes/auth.php';

wajib_login();

$u = data_user_sesi();
if ($u === null) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — Simulasi (non-enkripsi)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1 fs-5">Simulasi login</span>
        <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
    </div>
</nav>
<main class="container">
    <div class="alert alert-success" role="status">
        <strong>Selamat!</strong> Anda sudah berhasil login dan berada di halaman dashboard.
    </div>
    <h1 class="h3">Dashboard</h1>
    <p class="lead">Halo, <strong><?= h($u['nama']) ?></strong>.</p>
    <p>Anda masuk sebagai <code><?= h($u['username']) ?></code> (id: <?= (int) $u['id'] ?>).</p>
    <div class="alert alert-warning small">
        Sandi disimpan <strong>teks polos</strong> di tabel <code>users</code> — buka phpMyAdmin untuk melihatnya.
        Untuk latihan <strong>password_hash</strong> / <strong>password_verify</strong>, gunakan folder terpisah:
        <a href="../simulasi_login_enkripsi/">simulasi_login_enkripsi</a>.
    </div>
    <p><a href="login.php">← Form login</a></p>
</main>
</body>
</html>
