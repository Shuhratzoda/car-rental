<div class="split">
  <div class="card reveal">
    <div class="ph">
      <img src="<?= e($car['image'] ?: '/assets/placeholder.svg') ?>" onerror="this.src='/assets/placeholder.svg'" alt="">
    </div>
    <div class="body">
      <h3><?= e($car['brand'] . ' ' . $car['model']) ?> <small><?= e((string)$car['year']) ?></small></h3>
      <p class="muted">Тариф «<?= e($car['tariff_name']) ?>»: <?= (int)$car['km_per_day'] ?> км/рӯз, иловагӣ <?= money($car['extra_km_price']) ?>/км</p>
    </div>
  </div>

  <form class="panel reveal" method="post" action="/book/<?= (int)$car['id'] ?>" data-validate>
    <h2>Банд кардан</h2>
    <label>Санаи оғоз
      <input type="date" name="date_start" id="s" value="<?= e($s) ?>" min="<?= date('Y-m-d') ?>" required>
      <?php if (!empty($errors['date_start'])): ?><small class="err"><?= e($errors['date_start']) ?></small><?php endif; ?>
    </label>
    <label>Санаи анҷом
      <input type="date" name="date_end" id="e" value="<?= e($e) ?>" min="<?= date('Y-m-d') ?>" required>
      <?php if (!empty($errors['date_end'])): ?><small class="err"><?= e($errors['date_end']) ?></small><?php endif; ?>
    </label>
    <?php $d = max(1, days($s, max($s, $e))); ?>
    <div class="sum" data-price="<?= (float)$car['price_day'] ?>">
      <span>Рӯзҳо: <b id="dd"><?= $d ?></b></span>
      <span>Арзиши тахминӣ: <b id="tt"><?= money($d * $car['price_day']) ?></b></span>
    </div>
    <p class="muted">Меъёри масофа: <?= (int)$car['km_per_day'] ?> км/рӯз. Масофаи зиёд ҳангоми баргардонидан ҳисоб мешавад.</p>
    <button class="btn">Тасдиқи банд</button>
    <a class="btn ghost" href="/">Бозгашт</a>
  </form>
</div>
