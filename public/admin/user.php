<?php
declare(strict_types=1);
// Endpoint cũ dùng cùng router MVC; không nhận route tùy ý từ request.
$_GET['route'] = 'admin/user';
require dirname(__DIR__) . '/index.php';
