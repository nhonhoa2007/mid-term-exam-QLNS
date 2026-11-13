<?php
declare(strict_types=1);
namespace App\Core;

use App\Controllers\AdminUserController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;

final class Router
{
    private const ROUTES = [
        'home' => [['GET'], HomeController::class, 'index'],
        'register' => [['GET', 'POST'], AuthController::class, 'register'],
        'login' => [['GET', 'POST'], AuthController::class, 'login'],
        'logout' => [['POST'], AuthController::class, 'logout'],
        'admin/login' => [['GET', 'POST'], AuthController::class, 'adminLogin'],
        'admin/users' => [['GET'], AdminUserController::class, 'index'],
        'admin/user' => [['GET', 'POST'], AdminUserController::class, 'form'],
        'admin/delete' => [['POST'], AdminUserController::class, 'delete'],
    ];

    public function dispatch(): void
    {
        $route = input($_GET, 'route', 'home');
        $definition = self::ROUTES[$route] ?? null;
        if (!$definition) { abort_page(404, 'Không tìm thấy trang', 'Đường dẫn bạn yêu cầu không tồn tại.'); }
        [$methods, $class, $action] = $definition;
        if (!in_array($_SERVER['REQUEST_METHOD'], $methods, true)) {
            header('Allow: ' . implode(', ', $methods));
            abort_page(405, 'Thao tác không hợp lệ', 'Chức năng này không nhận phương thức yêu cầu vừa gửi.');
        }
        (new $class())->$action();
    }
}
