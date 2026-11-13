<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;
use InvalidArgumentException;
use PDOException;

final class AdminUserController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();
        $model = new User();
        $search = mb_substr(trim(input($_GET, 'q')), 0, 255);
        $data = $model->paginate($search, (int) input($_GET, 'page', '1'));
        $stats = $model->stats();
        $this->render('admin/index', compact('search', 'data', 'stats') + ['title' => 'Người dùng'], true);
    }

    public function form(): void
    {
        Auth::requireAdmin();
        $model = new User();
        $editing = isset($_GET['id']);
        $id = filter_var(input($_GET, 'id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $user = $editing && $id ? $model->find($id) : null;
        if ($editing && !$user) { abort_page(404, 'Không tìm thấy người dùng', 'Tài khoản có thể đã được xóa.'); }
        $email = $user['admin_email'] ?? '';
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $email = trim(input($_POST, 'admin_email'));
            $password = input($_POST, 'admin_password');
            $errors = validate_user(['admin_email' => $email, 'admin_password' => $password], $editing);
            if ($user && $user['is_admin'] && !is_admin_email($email)) {
                $errors['admin_email'] = 'Email admin demo được giữ cố định theo cấu hình. Bạn vẫn có thể đổi mật khẩu và ảnh.';
            } elseif ((!$user || !$user['is_admin']) && is_admin_email($email)) {
                $errors['admin_email'] = 'Email này dành riêng cho tài khoản admin demo.';
            }
            $file = $_FILES['images'] ?? ['error' => UPLOAD_ERR_NO_FILE];
            if (is_array($file['error'] ?? null)) { $errors['images'] = 'Vui lòng chọn một ảnh.'; }
            elseif ($error = validate_image($file)) { $errors['images'] = $error; }
            $newImage = '';
            if (!$errors) {
                try {
                    if ($file['error'] !== UPLOAD_ERR_NO_FILE) { $newImage = store_image($file); }
                    $image = $newImage ?: ($user['images'] ?? '');
                    $hash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : $user['admin_password'];
                    if ($editing) { $model->update($id, $email, $hash, $image); }
                    else { $model->create($email, $hash, $image); }
                    if ($newImage !== '' && $user) { delete_image($user['images']); }
                    flash($editing ? 'Đã cập nhật thông tin người dùng.' : 'Đã thêm người dùng mới.');
                    redirect_route('admin/users');
                } catch (PDOException $error) {
                    if ($newImage !== '') { delete_image($newImage); }
                    if (($error->errorInfo[1] ?? 0) !== 1062) { throw $error; }
                    $errors['admin_email'] = 'Email này đã được sử dụng. Vui lòng chọn email khác.';
                } catch (InvalidArgumentException $error) { $errors['images'] = $error->getMessage(); }
            }
        }
        $this->render('admin/form', compact('editing', 'id', 'user', 'email', 'errors') + ['title' => $editing ? 'Sửa người dùng' : 'Thêm người dùng'], true, $errors ? 422 : 200);
    }

    public function delete(): void
    {
        $me = Auth::requireAdmin();
        verify_csrf();
        $model = new User();
        $id = filter_var(input($_POST, 'id'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $user = $id ? $model->find($id) : null;
        if (!$user) { abort_page(404, 'Không tìm thấy người dùng', 'Tài khoản có thể đã được xóa.'); }
        if ($id === (int) $me['admin_id']) { abort_page(422, 'Không thể xóa tài khoản đang dùng', 'Bạn không thể tự xóa tài khoản đang đăng nhập.'); }
        $model->delete($id);
        delete_image($user['images']);
        flash('Đã xóa người dùng ' . $user['admin_email'] . '.');
        redirect_route('admin/users');
    }
}
