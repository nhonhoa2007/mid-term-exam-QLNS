<?php
declare(strict_types=1);
// Endpoint cũ dùng cùng router MVC; không nhận route tùy ý từ request.
$_GET['route'] = 'admin/users';
require dirname(__DIR__) . '/index.php';
