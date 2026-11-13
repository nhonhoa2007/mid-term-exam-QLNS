<div class="page-heading">
    <div>
        <span class="eyebrow">QUẢN LÝ</span>
        <h1>Người dùng<span class="title-dot">.</span></h1>
        <p>Thêm và cập nhật tài khoản trong hệ thống.</p>
    </div>
    <a class="button" href="<?= e(route_url('admin/user')) ?>"><?= icon('plus') ?>Thêm người dùng</a>
</div>
<div class="stats-grid">
    <article class="stat-card">
        <div><span>Tổng người dùng</span><strong><?= (int) $stats['total'] ?></strong></div>
        <span class="stat-icon tone-green"><?= icon('users') ?></span>
    </article>
    <article class="stat-card">
        <div><span>Đã có ảnh đại diện</span><strong><?= (int) $stats['with_images'] ?><em>/ <?= (int) $stats['total'] ?></em></strong></div>
        <span class="stat-icon tone-sunset"><?= icon('grid') ?></span>
    </article>
</div>
<?php require __DIR__ . '/../partials/users-table.php'; ?>
<p class="admin-tip"><?= icon('shield') ?>Mật khẩu không hiển thị trong danh sách.</p>
