<?php
declare(strict_types=1);

function validate_user(array $input, bool $editing): array
{
    $errors = [];
    $email = trim((string) ($input['admin_email'] ?? ''));
    $password = (string) ($input['admin_password'] ?? '');
    if (strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['admin_email'] = 'Vui lòng nhập email hợp lệ, tối đa 255 ký tự.';
    }
    if ((!$editing || $password !== '') && (mb_strlen($password) < 8 || strlen($password) > 72 || str_contains($password, "\0"))) {
        $errors['admin_password'] = 'Mật khẩu cần ít nhất 8 ký tự, tối đa 72 byte và không chứa byte NUL.';
    }
    return $errors;
}

function validate_image(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) { return null; }
    if ($file['error'] !== UPLOAD_ERR_OK) { return 'Không tải được ảnh. Vui lòng chọn ảnh tối đa 2 MB.'; }
    if (($file['size'] ?? 0) > 2 * 1024 * 1024 || ($file['size'] ?? 0) < 1) { return 'Ảnh phải có dung lượng từ 1 byte đến 2 MB.'; }
    $path = $file['tmp_name'] ?? '';
    if (!is_file($path)) { return 'Tệp ảnh không hợp lệ.'; }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($path);
    $info = @getimagesize($path);
    if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true) || !$info || $info[0] > 4096 || $info[1] > 4096) {
        return 'Chỉ nhận ảnh JPG, PNG, WebP, kích thước tối đa 4096 × 4096 px.';
    }
    return null;
}

function store_image(array $file): string
{
    $error = validate_image($file);
    if ($error !== null) { throw new InvalidArgumentException($error); }
    $image = @imagecreatefromstring(file_get_contents($file['tmp_name']));
    if (!$image) { throw new InvalidArgumentException('Không đọc được nội dung ảnh.'); }
    $name = bin2hex(random_bytes(16)) . '.png';
    imagesavealpha($image, true);
    if (!imagepng($image, dirname(__DIR__, 2) . '/public/uploads/' . $name)) { throw new RuntimeException('Không thể lưu ảnh. Kiểm tra quyền thư mục uploads.'); }
    return 'uploads/' . $name;
}

function delete_image(string $path): void
{
    if (preg_match('~^uploads/[a-f0-9]{32}\.png$~D', $path)) {
        $full = dirname(__DIR__, 2) . '/public/' . $path;
        if (is_file($full)) { unlink($full); }
    }
}
