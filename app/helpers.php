<?php
declare(strict_types=1);

function e(mixed $value): string { return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function input(array $data, string $key, string $default = ''): string { return isset($data[$key]) && is_string($data[$key]) ? $data[$key] : $default; }
function url(string $path): string
{
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if (str_ends_with($dir, '/admin')) { $dir = substr($dir, 0, -6); }
    return rtrim($dir, '/') . '/' . ltrim($path, '/');
}
function route_url(string $route, array $params = []): string
{
    return url('/index.php') . '?' . http_build_query(['route' => $route] + $params, '', '&', PHP_QUERY_RFC3986);
}
function redirect_route(string $route, array $params = []): never { header('Location: ' . route_url($route, $params), true, 303); exit; }
function csrf_token(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function csrf_field(): void { echo '<input type="hidden" name="csrf" value="' . csrf_token() . '">'; }
function verify_csrf(): void
{
    if (!hash_equals(csrf_token(), input($_POST, 'csrf'))) { abort_page(403, 'Phiên biểu mẫu không hợp lệ', 'Vui lòng tải lại trang và thử lần nữa.'); }
}
function flash(string $message): void { $_SESSION['flash'] = $message; }
function abort_page(int $status, string $title, string $description): never
{
    (new App\Core\Controller())->error($status, $title, $description);
    exit;
}
