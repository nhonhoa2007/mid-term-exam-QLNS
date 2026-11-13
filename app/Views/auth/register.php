<div class="login-layout">
    <section class="login-intro">
        <span class="eyebrow">PERSONNEL</span>
        <h1>Một tài khoản.<br><span>Thêm kết nối.</span></h1>
        <p>Đăng ký bằng email để tham gia cùng các thành viên.</p>
        <a class="back-link" href="<?= e(route_url('home')) ?>"><?= icon('back') ?>Về danh sách người dùng</a>
    </section>
    <section class="form-card login-card">
        <h2>Đăng ký tài khoản</h2>
        <p>Đã có tài khoản? <a class="inline-link" href="<?= e(route_url('login')) ?>">Đăng nhập</a></p>
        <?php if ($errors): ?><div class="alert error" role="alert">Chưa thể đăng ký. Vui lòng kiểm tra thông tin bên dưới.</div><?php endif; ?>
        <form method="post" action="<?= e(route_url('register')) ?>">
            <?php csrf_field(); ?>
            <div class="field">
                <label for="admin_email">Địa chỉ email <span class="required">*</span></label>
                <input id="admin_email" name="admin_email" type="email" placeholder="ban@example.com" value="<?= e($email) ?>" autocomplete="username" maxlength="255" required <?= isset($errors['admin_email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>>
                <?php if (isset($errors['admin_email'])): ?><small class="field-error" id="email-error"><?= e($errors['admin_email']) ?></small><?php endif; ?>
            </div>
            <div class="field">
                <label for="admin_password">Mật khẩu <span class="required">*</span></label>
                <div class="password-input">
                    <input id="admin_password" name="admin_password" type="password" placeholder="Nhập ít nhất 8 ký tự" autocomplete="new-password" minlength="8" required <?= isset($errors['admin_password']) ? 'aria-invalid="true"' : '' ?> aria-describedby="password-help">
                    <button type="button" class="password-toggle" data-password-toggle="admin_password" aria-label="Hiện mật khẩu">Hiện</button>
                </div>
                <small id="password-help" class="<?= isset($errors['admin_password']) ? 'field-error' : '' ?>"><?= e($errors['admin_password'] ?? 'Tối thiểu 8 ký tự, tối đa 72 byte.') ?></small>
            </div>
            <div class="field">
                <label for="password_confirmation">Xác nhận mật khẩu <span class="required">*</span></label>
                <div class="password-input">
                    <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Nhập lại mật khẩu" autocomplete="new-password" minlength="8" required <?= isset($errors['password_confirmation']) ? 'aria-invalid="true" aria-describedby="confirmation-error"' : '' ?>>
                    <button type="button" class="password-toggle" data-password-toggle="password_confirmation" aria-label="Hiện mật khẩu xác nhận">Hiện</button>
                </div>
                <?php if (isset($errors['password_confirmation'])): ?><small class="field-error" id="confirmation-error"><?= e($errors['password_confirmation']) ?></small><?php endif; ?>
            </div>
            <button class="button login-submit" type="submit">Đăng ký tài khoản<?= icon('arrow') ?></button>
        </form>
        <div class="form-footnote"><?= icon('shield') ?>Tài khoản đăng ký có quyền người dùng thông thường.</div>
    </section>
</div>
