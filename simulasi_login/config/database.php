<?php
/**
 * =============================================================================
 * KONFIGURASI DATABASE — folder simulasi_login
 * =============================================================================
 * Tujuan file ini:
 * - Membuat variabel $pdo (PDO) agar halaman lain bisa menjalankan query SQL.
 *
 * Kenapa database terpisah?
 * - Proyek utama memakai database `perkuliahan`. Folder ini memakai `simulasi_login`.
 * - Versi sandi ter-hash (bcrypt) memakai database lain: `simulasi_login_enkripsi`
 *   di folder ../simulasi_login_enkripsi/
 *
 * PDO & prepared statement:
 * - Di login.php, query memakai placeholder `?` lalu execute([...]) — pola yang
 *   sama aman dari SQL injection seperti di modul CRUD besar.
 *
 * Jika koneksi gagal:
 * - Biasanya database belum dibuat. Impor file database/setup.sql di phpMyAdmin
 *   atau MySQL CLI, lalu cek nama DB dan user/password di bawah ini.
 * =============================================================================
 */

declare(strict_types=1);

// Host MySQL di Laragon / XAMPP biasanya 127.0.0.1 (setara localhost)
$host = '127.0.0.1';

// Nama database harus sama dengan yang dibuat di setup.sql
$namaDatabase = 'simulasi_login';

// Kredensial MySQL — sesuaikan jika instalasi Anda berbeda
$user = 'root';
$password = '';

// utf8mb4 mendukung karakter Indonesia dan emoji dengan benar
$charset = 'utf8mb4';

// DSN = rangkuman cara PHP menghubungi MySQL
$dsn = "mysql:host={$host};dbname={$namaDatabase};charset={$charset}";

// Opsi PDO: lempar exception saat error; baris hasil query sebagai array asosiatif
$pilihan = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Variabel $pdo dipakai di login.php (dan bisa dipakai halaman lain)
    $pdo = new PDO($dsn, $user, $password, $pilihan);
} catch (PDOException $e) {
    // HTTP 500 memberi isyarat "kesalahan server" (bukan salah user di form)
    http_response_code(500);
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>DB error</title></head><body class="p-4">';
    echo '<p><strong>Koneksi gagal.</strong> Impor dulu <code>simulasi_login/database/setup.sql</code> di MySQL.</p>';
    // htmlspecialchars mirip fungsi h() — di sini tanpa include fungsi.php agar error tetap ringkas
    echo '<p><small>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</small></p></body></html>';
    exit;
}
