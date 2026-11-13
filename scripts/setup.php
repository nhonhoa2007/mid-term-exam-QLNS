<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../app/autoload.php';
use App\Core\Database;
$name = config()['db_name'];
if (!preg_match('/^[a-zA-Z0-9_]+$/D', $name)) { exit("Tên database không hợp lệ.\n"); }
try {
    $pdo = Database::connection(false);
    $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . $name . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $sql = file_get_contents(__DIR__ . '/../database/schema.sql');
    $table = substr($sql, strpos($sql, 'CREATE TABLE'));
    Database::connection()->exec($table);
    $stmt = Database::connection()->prepare('INSERT INTO Users (admin_email, admin_password, images) SELECT ?, ?, ? WHERE NOT EXISTS (SELECT 1 FROM Users WHERE admin_email = ?)');
    $emails = [admin_email(), 'linh.nguyen@example.com', 'minh.tran@example.com', 'anh.le@example.com', 'phuong.vo@example.com', 'khoa.pham@example.com'];
    $password = getenv('SEED_PASSWORD') ?: 'Admin@12345';
    if (strlen($password) < 8 || strlen($password) > 72) { throw new RuntimeException('SEED_PASSWORD phải dài từ 8 đến 72 byte.'); }
    foreach ($emails as $i => $email) {
        $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT), 'assets/avatar-' . ($i % 3 + 1) . '.svg', $email]);
    }
    echo "Đã tạo/kiểm tra database $name và thêm dữ liệu mẫu còn thiếu. Không ghi đè tài khoản đã có.\n";
    echo 'Admin demo: ' . admin_email() . ". Mật khẩu khi cài mới: SEED_PASSWORD hoặc mặc định trong README.\n";
} catch (Throwable $e) {
    fwrite(STDERR, "Cài đặt thất bại: " . $e->getMessage() . "\n");
    exit(1);
}
