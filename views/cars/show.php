<div class="split">
  <div class="card reveal">
    <div class="ph">
      <img src="<?= e($car['image'] ?: '/assets/placeholder.svg') ?>" onerror="this.src='/assets/placeholder.svg'" alt="">
    </div>
    <div class="body">
      <h2><?= e($car['brand'] . ' ' . $car['model']) ?> <small><?= e((string)$car['year']) ?></small></h2>
      <p class="muted">Тариф: <?= e($car['tariff_name']) ?> · <?= (int)$car['km_per_day'] ?> км/рӯз · иловагӣ <?= money($car['extra_km_price']) ?>/км</p>
      <p>Нарх: <b><?= money($car['price_day']) ?></b> / рӯз</p>
      <p>Одометр: <?= number_format((int)$car['odometer']) ?> км</p>
      <p>Статус: <span class="st <?= e($car['status']) ?>"><?= $car['status'] === 'active' ? 'Фаъол' : 'Ғайрифаъол' ?></span></p>
    </div>
  </div>

  <div class="panel reveal">
    <?php if ($user && $user['role'] === 'admin'): ?>
      <a class="btn" href="/cars/<?= (int)$car['id'] ?>/edit">Таҳрир</a>
      <form method="post" action="/cars/<?= (int)$car['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Мошинро нест мекунед?')">
        <button class="btn ghost" type="submit">Нест кардан</button>
      </form>
    <?php endif; ?>
    <a class="btn ghost" href="/cars">Ба рӯйхат</a>
  </div>
</div>
