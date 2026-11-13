<?php
declare(strict_types=1);

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/Support/validation.php';
require_once __DIR__ . '/Support/presentation.php';

ini_set('session.use_strict_mode', '1');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'path' => '/']);
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header("Content-Security-Policy: default-src 'self'; img-src 'self' blob:; style-src 'self'; script-src 'self'; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; form-action 'self'");
header('Referrer-Policy: same-origin');
header('Cache-Control: no-store');

set_exception_handler(function (Throwable $error): void {
    error_log((string) $error);
    abort_page(500, 'Chưa thể tải dữ liệu', 'Kiểm tra cấu hình MySQL và chạy scripts/setup.php theo README. Chi tiết lỗi được ghi trong log PHP.');
});
