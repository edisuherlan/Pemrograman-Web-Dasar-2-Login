<?php
/**
 * =============================================================================
 * KONFIGURASI DATABASE — simulasi_login_enkripsi
 * =============================================================================
 * Database `simulasi_login_enkripsi` berisi tabel users dengan kolom password_hash.
 * Impor: database/setup.sql (folder ini).
 *
 * Terpisah dari `simulasi_login` agar mahasiswa bisa membandingkan dua pendekatan
 * penyimpanan sandi tanpa mencampur data.
 * =============================================================================
 */

declare(strict_types=1);

$host = '127.0.0.1';
$namaDatabase = 'simulasi_login_enkripsi';
$user = 'root';
$password = '';
$charset = 'utf8mb4';
$dsn = "mysql:host={$host};dbname={$namaDatabase};charset={$charset}";
$pilihan = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $pilihan);
} catch (PDOException $e) {
    http_response_code(500);
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>DB error</title></head><body class="p-4">';
    echo '<p><strong>Koneksi gagal.</strong> Impor <code>simulasi_login_enkripsi/database/setup.sql</code> di MySQL.</p>';
    echo '<p><small>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</small></p></body></html>';
    exit;
}
