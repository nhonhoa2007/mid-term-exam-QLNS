<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

final class HomeController extends Controller
{
    public function index(): void
    {
        $model = new User();
        $search = mb_substr(trim(input($_GET, 'q')), 0, 255);
        $data = $model->paginate($search, (int) input($_GET, 'page', '1'));
        $total = (int) $model->stats()['total'];
        $this->render('home/index', compact('search', 'data', 'total') + ['title' => 'Danh sách người dùng']);
    }
}
