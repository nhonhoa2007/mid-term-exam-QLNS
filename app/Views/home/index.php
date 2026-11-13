<section class="public-hero">
    <div class="hero-copy">
        <span class="eyebrow">PERSONNEL</span>
        <h1>Danh sách người dùng<span class="title-dot">.</span></h1>
        <p>Thông tin và ảnh đại diện của các thành viên trong hệ thống.</p>
    </div>
    <div class="member-summary"><strong><?= $total ?></strong><span>thành viên</span></div>
</section>
<?php require __DIR__ . '/../partials/users-table.php'; ?>
<div class="public-note">
    <?= icon('shield') ?><p>Thông tin đăng nhập được bảo vệ.</p>
    <a href="<?= e(route_url('admin/login')) ?>">Quản lý người dùng <?= icon('arrow') ?></a>
</div>
