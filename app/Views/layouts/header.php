<!doctype html>
<html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#2e6b3f"><title><?= e($title) ?> · Personnel</title><link rel="stylesheet" href="<?= e(url('/assets/style.css')) ?>"><link rel="stylesheet" href="<?= e(url('/assets/auth.css')) ?>"><script src="<?= e(url('/assets/app.js')) ?>" defer></script></head>
<body class="<?= $admin ? 'admin-layout' : 'public-layout' ?>">
<?php if ($admin): ?>
<aside class="sidebar">
    <a class="brand" href="<?= e(route_url('admin/users')) ?>"><span class="brand-mark"><?= icon('users') ?></span>personnel<span class="brand-dot">.</span></a>
    <span class="nav-caption">QUẢN LÝ</span>
    <nav aria-label="Menu quản trị">
        <a class="nav-item active" href="<?= e(route_url('admin/users')) ?>"><?= icon('users') ?>Người dùng</a>
        <a class="nav-item" href="<?= e(route_url('home')) ?>"><?= icon('grid') ?>Trang hiển thị<?= icon('arrow') ?></a>
    </nav>
    <div class="sidebar-profile">
        <span class="mini-avatar"><?= e(strtoupper(substr($currentUser['admin_email'], 0, 1))) ?></span>
        <div><strong>Quản trị viên</strong><small><?= e($currentUser['admin_email']) ?></small></div>
    </div>
</aside>
<?php endif; ?>
<div class="page-shell">
<header class="topbar"><div class="topbar-inner">
<?php if (!$admin): ?>
  <a class="brand" href="<?= e(route_url('home')) ?>"><span class="brand-mark"><?= icon('users') ?></span>personnel<span class="brand-dot">.</span></a>
  <nav class="public-nav" aria-label="Điều hướng"><a class="selected" href="<?= e(route_url('home')) ?>">Danh sách người dùng</a></nav>
<?php else: ?>
  <span class="breadcrumb">Không gian làm việc <span>/</span> <strong><?= e($title) ?></strong></span>
<?php endif; ?>
<div class="header-actions">
<?php if ($currentUser): ?>
  <?php if (!$admin): ?>
    <span class="signed-in-user" title="<?= e($currentUser['admin_email']) ?>"><?= e($currentUser['admin_email']) ?></span>
    <?php if ($currentUser['is_admin']): ?><a class="button button-outline button-small" href="<?= e(route_url('admin/users')) ?>"><?= icon('shield') ?>Quản trị</a><?php endif; ?>
  <?php endif; ?>
  <form action="<?= e(route_url('logout')) ?>" method="post"><?php csrf_field(); ?><button class="text-button" type="submit"><?= icon('logout') ?><span>Đăng xuất</span></button></form>
<?php else: ?>
  <a class="auth-link" href="<?= e(route_url('login')) ?>">Đăng nhập</a>
  <a class="button button-small" href="<?= e(route_url('register')) ?>">Đăng ký</a>
  <a class="admin-login-link" href="<?= e(route_url('admin/login')) ?>" aria-label="Đăng nhập admin" title="Đăng nhập admin"><?= icon('shield') ?></a>
<?php endif; ?>
</div></div></header>
<main class="content <?= $admin ? 'admin-content' : '' ?>">
<?php if ($flashMessage): ?><div class="alert success" role="status"><?= icon('check') ?><?= e($flashMessage) ?></div><?php endif; ?>
