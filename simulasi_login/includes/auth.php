<?php
/**
 * =============================================================================
 * AUTENTIKASI BERBASIS SESI — simulasi_login (PASSWORD TEKS POLOS DI DB)
 * =============================================================================
 * Folder ini khusus latihan menyimpan sandi seperti teks biasa di kolom users.password.
 * Untuk versi bcrypt / password_verify(), buka folder terpisah:
 *   ../simulasi_login_enkripsi/
 *
 * Nama kunci sesi: simulasi_* — berbeda dari folder simulasi_login_enkripsi agar
 * kedua aplikasi bisa dibuka bergantian tanpa tabrakan data sesi (walau nama cookie PHP tetap sama).
 * =============================================================================
 */

declare(strict_types=1);

function pastikan_sesi(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function sudah_login(): bool
{
    pastikan_sesi();
    return isset($_SESSION['simulasi_user_id']) && (int) $_SESSION['simulasi_user_id'] > 0;
}

/**
 * @return array{id:int, username:string, nama:string}|null
 */
function data_user_sesi(): ?array
{
    if (!sudah_login()) {
        return null;
    }
    return [
        'id'       => (int) $_SESSION['simulasi_user_id'],
        'username' => (string) ($_SESSION['simulasi_username'] ?? ''),
        'nama'     => (string) ($_SESSION['simulasi_nama'] ?? ''),
    ];
}

function masuk_sesi(int $id, string $username, string $nama): void
{
    pastikan_sesi();
    session_regenerate_id(true);
    $_SESSION['simulasi_user_id'] = $id;
    $_SESSION['simulasi_username'] = $username;
    $_SESSION['simulasi_nama'] = $nama;
}

function keluar_sesi(): void
{
    pastikan_sesi();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], (bool) $p['secure'], (bool) $p['httponly']);
    }
    session_destroy();
}

function wajib_login(): void
{
    if (!sudah_login()) {
        header('Location: login.php');
        exit;
    }
}
