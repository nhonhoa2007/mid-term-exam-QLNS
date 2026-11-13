<?php
declare(strict_types=1);

function config(): array
{
    static $config;
    if ($config !== null) { return $config; }
    $local = is_file(__DIR__ . '/../config.local.php') ? require __DIR__ . '/../config.local.php' : [];
    $defaults = ['db_host' => '127.0.0.1', 'db_port' => '3306', 'db_socket' => '', 'db_name' => 'QLNhanSu', 'db_user' => 'root', 'db_pass' => '', 'demo_admin_email' => 'admin@example.com'];
    $config = array_replace($defaults, $local);
    foreach ($defaults as $key => $value) {
        $env = getenv(strtoupper($key));
        if ($env !== false) { $config[$key] = $env; }
    }
    return $config;
}

function admin_email(): string { return trim(config()['demo_admin_email']); }
function is_admin_email(string $email): bool { return strcasecmp(trim($email), admin_email()) === 0; }
