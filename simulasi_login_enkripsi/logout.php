<?php
/**
 * Logout — hapus sesi (kunci simulasi_enc_*), kembali ke login.php
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

keluar_sesi();
header('Location: login.php');
exit;
