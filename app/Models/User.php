<?php
declare(strict_types=1);
namespace App\Models;

use App\Core\Database;

final class User
{
    private function withRole(array $user): array
    {
        $user['is_admin'] = is_admin_email($user['admin_email']);
        return $user;
    }

    public function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM Users WHERE admin_id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ? $this->withRole($user) : null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM Users WHERE admin_email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ? $this->withRole($user) : null;
    }

    public function paginate(string $search, int $page): array
    {
        $where = $search === '' ? '' : ' WHERE u.admin_email LIKE ?';
        $params = $search === '' ? [] : ['%' . $search . '%'];
        $stmt = Database::connection()->prepare('SELECT COUNT(*) FROM Users u' . $where);
        $stmt->execute($params);
        $count = (int) $stmt->fetchColumn();
        $pages = max(1, (int) ceil($count / 8));
        $page = max(1, min($page, $pages));
        $offset = ($page - 1) * 8;
        $stmt = Database::connection()->prepare('SELECT u.admin_id, u.admin_email, u.images FROM Users u' . $where . ' ORDER BY u.admin_id DESC LIMIT 8 OFFSET ' . $offset);
        $stmt->execute($params);
        return ['rows' => array_map($this->withRole(...), $stmt->fetchAll()), 'count' => $count, 'page' => $page, 'pages' => $pages];
    }

    public function stats(): array
    {
        return Database::connection()->query("SELECT COUNT(*) AS total, COALESCE(SUM(images <> ''), 0) AS with_images FROM Users")->fetch();
    }

    public function create(string $email, string $passwordHash, string $image = ''): int
    {
        $stmt = Database::connection()->prepare('INSERT INTO Users (admin_email, admin_password, images) VALUES (?, ?, ?)');
        $stmt->execute([$email, $passwordHash, $image]);
        return (int) Database::connection()->lastInsertId();
    }

    public function update(int $id, string $email, string $passwordHash, string $image): void
    {
        $stmt = Database::connection()->prepare('UPDATE Users SET admin_email = ?, admin_password = ?, images = ? WHERE admin_id = ?');
        $stmt->execute([$email, $passwordHash, $image, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM Users WHERE admin_id = ?');
        $stmt->execute([$id]);
    }
}
