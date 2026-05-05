<?php
/**
 * =============================================================================
 * LOGOUT — simulasi_login
 * =============================================================================
 * Tujuan:
 * - Mengakhiri sesi PHP: $_SESSION dikosongkan, cookie sesi dihapus, session_destroy().
 *
 * Setelah itu user dianggap "belum login". Akses ke dashboard.php akan memicu
 * redirect ke login.php (lewat fungsi wajib_login).
 *
 * Tidak perlu form HTML: cukup tautan GET ke logout.php (untuk latihan). Di
 * aplikasi sensitif kadang dipakai POST + token CSRF untuk mencegah logout paksa.
 *
 * Redirect ke login.php setelah sesi dihapus.
 * =============================================================================
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

keluar_sesi();
header('Location: login.php');
exit;
