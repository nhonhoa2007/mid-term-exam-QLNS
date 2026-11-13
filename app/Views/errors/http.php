<section class="empty-state">
    <span class="eyebrow">LỖI <?= http_response_code() ?></span>
    <h1><?= e($title) ?></h1>
    <p><?= e($description) ?></p>
    <a class="button" href="<?= e(route_url('home')) ?>">Về danh sách</a>
</section>
