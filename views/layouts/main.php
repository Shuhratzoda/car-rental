<!DOCTYPE html>
<html lang="tg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($title ?? 'AutoRent') ?> — AutoRent</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="bg"><i></i><i></i><i></i></div>
<nav class="nav" id="nav">
    <a class="logo" href="/">Auto<b>Rent</b></a>
    <div class="links">
        <a href="/">Автопарк</a>
        <?php if ($user): ?>
            <?php if ($user['role'] === 'client'): ?>
                <a href="/my">Бандҳои ман</a>
            <?php endif; ?>
            <?php if (in_array($user['role'], ['manager', 'admin'], true)): ?>
                <a href="/manager">Менеҷер</a>
                <a href="/cars">Мошинҳо</a>
                <a href="/reports/income">Ҳисобот</a>
            <?php endif; ?>
            <?php if ($user['role'] === 'admin'): ?>
                <a href="/cars/create">+ Мошин</a>
            <?php endif; ?>
            <span class="user"><?= e($user['full_name']) ?></span>
            <a href="/logout">Баромад</a>
        <?php else: ?>
            <a href="/login">Вуруд</a>
            <a href="/register">Бақайдгирӣ</a>
        <?php endif; ?>
    </div>
</nav>

<main class="container">
    <?php if (!empty($flash)): ?>
        <div class="flash reveal"><?= e($flash) ?></div>
    <?php endif; ?>
    <?= $content ?>
</main>

<!-- Модали тасдиқи нест кардан (Lab 11) -->
<div id="confirm-modal" class="modal-overlay" role="dialog" aria-modal="true">
  <div class="modal-box">
    <h3 data-modal-title>Тасдиқ</h3>
    <p data-modal-body>Шумо мутмаин ҳастед?</p>
    <div class="row">
      <button type="button" class="btn ghost" data-modal-cancel>Бекор</button>
      <button type="button" class="btn" data-modal-ok>Тасдиқ</button>
    </div>
  </div>
</div>

<!-- ES-модул: пас аз таҳлили HTML иҷро мешавад (мисли defer) -->
<script type="module" src="/assets/js/app.js"></script>
</body>
</html>
