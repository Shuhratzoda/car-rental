<h2 class="reveal">Мошинҳо <small class="muted">(<?= (int)$total ?> сабт)</small></h2>

<form class="panel reveal fgrid" method="get">
  <input type="search" name="q" placeholder="Ҷустуҷӯ (бренд, модел)" value="<?= e($filters['q']) ?>">
  <select name="tariff_id">
    <option value="">Ҳамаи тарифҳо</option>
    <?php foreach ($tariffs as $t): ?>
      <option value="<?= (int)$t['id'] ?>" <?= ($filters['tariff_id'] == $t['id']) ? 'selected' : '' ?>><?= e($t['name']) ?></option>
    <?php endforeach; ?>
  </select>
  <select name="status">
    <option value="">Ҳамаи статусҳо</option>
    <option value="active" <?= $filters['status']==='active'?'selected':'' ?>>Фаъол</option>
    <option value="inactive" <?= $filters['status']==='inactive'?'selected':'' ?>>Ғайрифаъол</option>
  </select>
  <button class="btn">Филтр</button>
  <?php if ($user && $user['role']==='admin'): ?>
    <a class="btn" href="/cars/create">+ Мошини нав</a>
  <?php endif; ?>
</form>

<div class="list">
<?php foreach ($items as $c): ?>
  <div class="item reveal">
    <div>
      <b><a href="/cars/<?= (int)$c['id'] ?>"><?= e($c['brand'] . ' ' . $c['model']) ?></a></b>
      <br><small class="muted"><?= e($c['tariff_name']) ?> · <?= e((string)$c['year']) ?> · <?= money($c['price_day']) ?>/рӯз</small>
    </div>
    <span class="st <?= e($c['status']) ?>"><?= $c['status']==='active' ? 'Фаъол' : 'Ғайрифаъол' ?></span>
    <div>
      <?php if ($user && $user['role']==='admin'): ?>
        <a class="btn sm ghost" href="/cars/<?= (int)$c['id'] ?>/edit">Таҳрир</a>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
<?php if (!$items): ?>
  <p class="muted">Ҳеҷ мошине ёфт нашуд.</p>
<?php endif; ?>
</div>

<?php if ($pages > 1): ?>
<nav class="pagination reveal">
  <?php for ($p = 1; $p <= $pages; $p++): ?>
    <?php
      $qs = http_build_query(array_filter([
        'q' => $filters['q'] ?: null,
        'tariff_id' => $filters['tariff_id'] ?: null,
        'status' => $filters['status'] ?: null,
        'page' => $p,
      ]));
    ?>
    <a class="btn sm <?= $p === $page ? '' : 'ghost' ?>" href="/cars?<?= $qs ?>"><?= $p ?></a>
  <?php endfor; ?>
</nav>
<?php endif; ?>
