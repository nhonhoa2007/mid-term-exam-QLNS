<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;
use PDOException;

final class AuthController extends Controller
{
    public function register(): void
    {
        if (Auth::user()) { redirect_route(Auth::user()['is_admin'] ? 'admin/users' : 'home'); }
        $email = trim(input($_POST, 'admin_email'));
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $password = input($_POST, 'admin_password');
            $errors = validate_user(['admin_email' => $email, 'admin_password' => $password], false);
            if ($password !== input($_POST, 'password_confirmation')) { $errors['password_confirmation'] = 'Mật khẩu xác nhận chưa khớp.'; }
            if (is_admin_email($email)) { $errors['admin_email'] = 'Email này dành cho tài khoản quản trị demo.'; }
            if (!$errors) {
                try {
                    (new User())->create($email, password_hash($password, PASSWORD_DEFAULT));
                    flash('Đăng ký thành công. Bạn có thể đăng nhập bằng tài khoản vừa tạo.');
                    redirect_route('login');
                } catch (PDOException $error) {
                    if (($error->errorInfo[1] ?? 0) !== 1062) { throw $error; }
                    $errors['admin_email'] = 'Email này đã được sử dụng. Vui lòng chọn email khác.';
                }
            }
        }
        $this->render('auth/register', compact('email', 'errors') + ['title' => 'Đăng ký tài khoản'], false, $errors ? 422 : 200);
    }

    public function login(): void { $this->signIn(false); }
    public function adminLogin(): void { $this->signIn(true); }

    private function signIn(bool $adminOnly): void
    {
        $current = Auth::user();
        if ($current) {
            if ($adminOnly) { Auth::requireAdmin(); }
            redirect_route($current['is_admin'] ? 'admin/users' : 'home');
        }
        $email = trim(input($_POST, 'admin_email'));
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $user = (new User())->findByEmail($email);
            if ($user && password_verify(input($_POST, 'admin_password'), $user['admin_password']) && (!$adminOnly || $user['is_admin'])) {
                Auth::login($user);
                flash('Đăng nhập thành công. Chào mừng bạn trở lại!');
                redirect_route($user['is_admin'] ? 'admin/users' : 'home');
            }
            $error = $adminOnly ? 'Email, mật khẩu hoặc quyền quản trị không hợp lệ.' : 'Email hoặc mật khẩu không chính xác.';
        }
        $this->render('auth/login', compact('email', 'error', 'adminOnly') + ['title' => $adminOnly ? 'Đăng nhập quản trị' : 'Đăng nhập'], false, $error ? 422 : 200);
    }

    public function logout(): void
    {
        verify_csrf();
        Auth::logout();
        redirect_route('login');
    }
}
