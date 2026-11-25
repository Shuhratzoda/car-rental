<section class="hero reveal">
  <div>
    <span class="pill">● Бисёр осон. Бисёр зуд.</span>
    <h1>Мошини орзуи худро<br><span class="grad">дар чанд сония</span> киро кунед</h1>
    <p>Автопарки муосир, нархи шаффоф ва банди онлайн бо тасдиқи фаврӣ.</p>
    <form class="search" method="get">
      <label>Аз<input type="date" name="s" min="<?= date('Y-m-d') ?>" value="<?= e($s) ?>" required></label>
      <label>То<input type="date" name="e" min="<?= date('Y-m-d') ?>" value="<?= e($e) ?>" required></label>
      <button class="btn">Ҷустуҷӯи мошини холӣ</button>
    </form>
  </div>
  <div class="car-fx">
    <svg viewBox="0 0 400 160">
      <defs><linearGradient id="g" x1="0" x2="1"><stop offset="0" stop-color="#7c5cff"/><stop offset="1" stop-color="#00e0c6"/></linearGradient></defs>
      <path d="M30 110 L60 70 Q70 55 95 52 L270 52 Q300 55 320 80 L370 95 Q385 100 385 115 L385 125 L15 125 L15 115 Q15 112 30 110Z" fill="none" stroke="url(#g)" stroke-width="4"/>
      <circle cx="95" cy="125" r="22" fill="#0b0b18" stroke="url(#g)" stroke-width="4"/>
      <circle cx="310" cy="125" r="22" fill="#0b0b18" stroke="url(#g)" stroke-width="4"/>
    </svg>
  </div>
</section>

<div class="stats reveal">
  <div><b data-n="<?= (int)($stats['active'] ?? 0) ?>">0</b><span>мошини фаъол</span></div>
  <div><b data-n="24">0</b><span>соат дастгирӣ</span></div>
  <div><b data-n="<?= (int)($bookingStats['pending'] ?? 0) ?>">0</b><span>банди интизор</span></div>
</div>

<h2 class="reveal"><?= $search ? 'Мошинҳои холӣ: ' . e($s) . ' → ' . e($e) : 'Автопарки мо' ?></h2>

<div class="grid">
<?php foreach ($cars as $c): ?>
  <article class="card tilt reveal">
    <div class="ph">
      <img loading="lazy" src="<?= e($c['image'] ?: '/assets/placeholder.svg') ?>" alt="" onerror="this.src='/assets/placeholder.svg'">
      <span class="tag"><?= e($c['tariff_name']) ?></span>
    </div>
    <div class="body">
      <h3><?= e($c['brand'] . ' ' . $c['model']) ?> <small><?= e((string)$c['year']) ?></small></h3>
      <p class="muted">Меъёр: <?= (int)$c['km_per_day'] ?> км/рӯз · Иловагӣ: <?= money($c['extra_km_price']) ?>/км</p>
      <div class="row">
        <div class="price"><?= money($c['price_day']) ?><small>/рӯз</small></div>
        <div>
          <?php if ($user && $user['role'] === 'client'): ?>
            <a class="btn sm" href="/book/<?= (int)$c['id'] ?><?= $search ? '?s=' . urlencode($s) . '&e=' . urlencode($e) : '' ?>">Банд кардан</a>
          <?php elseif (!$user): ?>
            <a class="btn sm" href="/login">Барои банд ворид шавед</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </article>
<?php endforeach; ?>
<?php if (!$cars): ?>
  <p class="muted">Мошини холӣ барои ин санаҳо нест.</p>
<?php endif; ?>
</div>
