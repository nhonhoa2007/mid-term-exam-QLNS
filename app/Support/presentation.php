<?php
declare(strict_types=1);

function icon(string $name, string $class = ''): string {
    $paths = [
        'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m20 0v-2a4 4 0 0 0-3-3.87M15 3.13a4 4 0 0 1 0 7.75"/><circle cx="9" cy="7" r="4"/>',
        'grid' => '<rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/>',
        'arrow' => '<path d="M7 17 17 7M7 7h10v10"/>', 'plus' => '<path d="M12 5v14M5 12h14"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m16 16 4 4"/>',
        'edit' => '<path d="m16 3 5 5-12 12-6 1 1-6ZM13 6l5 5"/>',
        'trash' => '<path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7M14 10v7"/>',
        'shield' => '<path d="m12 3 8 4v5c0 5-8 9-8 9s-8-4-8-9V7Z"/><path d="m8 12 3 3 5-5"/>',
        'logout' => '<path d="M9 21H4V3h5m5 5 5 4-5 4M9 12h12"/>',
        'back' => '<path d="m12 5-7 7 7 7M5 12h15"/>',
        'upload' => '<path d="M12 16V3m-5 5 5-5 5 5M4 15v6h16v-6"/>',
        'check' => '<path d="m5 12 4 4L19 6"/>',
    ];
    return '<svg class="icon ' . e($class) . '" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? $paths['users']) . '</svg>';
}

function avatar(array $user, string $class = ''): void {
    $path = $user['images'] ?? '';
    $allowed = preg_match('~^(uploads/[a-f0-9]{32}\.png|assets/avatar-[123]\.svg)$~D', $path);
    if ($allowed) { ?><img class="avatar <?= e($class) ?>" src="<?= e(url('/' . $path)) ?>" alt="Ảnh đại diện của <?= e($user['admin_email']) ?>" width="44" height="44" loading="lazy"><?php }
    else { ?><span class="avatar avatar-initial <?= e($class) ?>" aria-hidden="true"><?= e(strtoupper(substr($user['admin_email'] ?? 'U', 0, 1))) ?></span><?php }
}

