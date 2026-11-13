<div class="login-layout">
    <section class="login-intro">
        <span class="eyebrow"><?= $adminOnly ? 'PERSONNEL / ADMIN' : 'PERSONNEL' ?></span>
        <h1><?= $adminOnly ? 'Quản lý<br><span>dễ dàng hơn.</span>' : 'Chào mừng<br><span>bạn trở lại.</span>' ?></h1>
        <p><?= $adminOnly ? 'Đăng nhập để thêm, cập nhật và quản lý người dùng.' : 'Đăng nhập bằng email và mật khẩu của bạn.' ?></p>
        <a class="back-link" href="<?= e(route_url('home')) ?>"><?= icon('back') ?>Về danh sách người dùng</a>
    </section>
    <section class="form-card login-card">
        <h2><?= $adminOnly ? 'Đăng nhập admin' : 'Đăng nhập' ?></h2>
        <p><?= $adminOnly ? 'Dành cho tài khoản có quyền quản trị.' : 'Đăng nhập bằng tài khoản của bạn.' ?></p>
        <?php if ($error): ?><div class="alert error" role="alert"><?= e($error) ?></div><?php endif; ?>
        <form method="post" action="<?= e(route_url($adminOnly ? 'admin/login' : 'login')) ?>">
            <?php csrf_field(); ?>
            <div class="field">
                <label for="admin_email">Địa chỉ email</label>
                <input id="admin_email" name="admin_email" type="email" placeholder="<?= e($adminOnly ? admin_email() : 'ban@example.com') ?>" autocomplete="username" maxlength="255" required value="<?= e($email) ?>">
            </div>
            <div class="field">
                <label for="admin_password">Mật khẩu</label>
                <div class="password-input">
                    <input id="admin_password" name="admin_password" type="password" placeholder="Nhập mật khẩu của bạn" autocomplete="current-password" required>
                    <button type="button" class="password-toggle" data-password-toggle="admin_password" aria-label="Hiện mật khẩu">Hiện</button>
                </div>
            </div>
            <button class="button login-submit" type="submit">Đăng nhập<?= icon('arrow') ?></button>
        </form>
        <?php if ($adminOnly): ?>
            <div class="demo-account"><strong>Tài khoản admin demo</strong><span>Email: <code><?= e(admin_email()) ?></code></span><span>Mật khẩu: <code>Admin@12345</code></span><small>Mật khẩu mặc định khi cài mới; dùng mật khẩu hiện tại nếu bạn đã thay đổi.</small></div>
            <p class="auth-switch">Người dùng thông thường? <a class="inline-link" href="<?= e(route_url('login')) ?>">Đăng nhập tại đây</a></p>
        <?php else: ?>
            <p class="auth-switch">Chưa có tài khoản? <a class="inline-link" href="<?= e(route_url('register')) ?>">Đăng ký ngay</a></p>
            <div class="form-footnote"><?= icon('shield') ?><a href="<?= e(route_url('admin/login')) ?>">Đăng nhập bằng tài khoản admin demo</a></div>
        <?php endif; ?>
    </section>
</div>
