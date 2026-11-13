<?php
declare(strict_types=1);
namespace App\Core;

class Controller
{
    protected function render(string $view, array $viewData, bool $admin = false, int $status = 200): void
    {
        http_response_code($status);
        $currentUser = array_key_exists('currentUser', $viewData) ? $viewData['currentUser'] : Auth::user();
        $flashMessage = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        extract($viewData, EXTR_SKIP);
        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/' . $view . '.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

    public function error(int $status, string $title, string $description): void
    {
        $this->render('errors/http', compact('title', 'description') + ['currentUser' => null], false, $status);
    }
}
