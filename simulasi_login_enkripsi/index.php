<?php
/**
 * ENTRY — simulasi_login_enkripsi: sudah login → dashboard, belum → login.php
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

pastikan_sesi();

if (sudah_login()) {
    header('Location: dashboard.php');
    exit;
}
header('Location: login.php');
exit;
