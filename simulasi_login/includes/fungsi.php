<?php
/**
 * =============================================================================
 * FUNGSI BANTUAN — simulasi_login
 * =============================================================================
 * File ini berisi helper kecil yang bisa dipakai di banyak halaman.
 *
 * h($teks):
 * - Mengekspor teks ke HTML dengan aman. Tanpa ini, jika suatu saat $teks
 *   berisi karakter < atau &, browser bisa salah mengartikan sebagai tag HTML
 *   (risiko XSS). Di dashboard kita pakai untuk nama/username dari sesi.
 * =============================================================================
 */

declare(strict_types=1);

/**
 * Escape teks untuk ditampilkan di HTML (mencegah XSS).
 *
 * @param string|null $teks Teks dari database atau input (boleh null)
 * @return string         Teks aman untuk dimasukkan ke dalam elemen HTML
 */
function h(?string $teks): string
{
    // ENT_QUOTES = escape tanda kutip tunggal dan ganda; UTF-8 = encoding halaman
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}
