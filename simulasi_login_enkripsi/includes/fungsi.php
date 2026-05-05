<?php
/**
 * Helper escape HTML — simulasi_login_enkripsi
 */
declare(strict_types=1);

function h(?string $teks): string
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}
