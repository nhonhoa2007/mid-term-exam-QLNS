<?php
declare(strict_types=1);
namespace App\Core;

use App\Models\User;

final class Auth
{
    private static bool $loaded = false;
    private static ?array $user = null;

    public static function user(): ?array
    {
        if (!self::$loaded) {
            self::$user = isset($_SESSION['user_id']) ? (new User())->find((int) $_SESSION['user_id']) : null;
            self::$loaded = true;
        }
        return self::$user;
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['admin_id'];
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
        self::$user = $user;
        self::$loaded = true;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        self::$user = null;
        self::$loaded = true;
        $params = session_get_cookie_params();
        setcookie(session_name(), '', ['expires' => time() - 42000, 'path' => $params['path'], 'secure' => $params['secure'], 'httponly' => true, 'samesite' => 'Lax']);
        session_destroy();
    }

    public static function requireAdmin(): array
    {
        $user = self::user();
        if (!$user) { redirect_route('admin/login'); }
        if (!(bool) $user['is_admin']) { abort_page(403, 'Bạn không có quyền quản trị', 'Tài khoản này chỉ có quyền truy cập trang người dùng.'); }
        return $user;
    }
}
