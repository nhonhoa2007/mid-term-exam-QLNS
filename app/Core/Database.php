<?php
declare(strict_types=1);
namespace App\Core;

use PDO;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(bool $withDatabase = true): PDO
    {
        if ($withDatabase && self::$connection) { return self::$connection; }
        $config = config();
        $dsn = $config['db_socket'] !== ''
            ? 'mysql:unix_socket=' . $config['db_socket']
            : 'mysql:host=' . $config['db_host'] . ';port=' . $config['db_port'];
        $dsn .= ($withDatabase ? ';dbname=' . $config['db_name'] : '') . ';charset=utf8mb4';
        $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        if ($withDatabase) { self::$connection = $pdo; }
        return $pdo;
    }
}
