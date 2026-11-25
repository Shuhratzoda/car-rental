<?php
$tabs = [
  'pending'   => 'Интизор',
  'approved'  => 'Тасдиқшуд',
  'issued'    => 'Дар иҷора',
  'returned'  => 'Баргашт',
  'rejected'  => 'Радшуда',
  'cancelled' => 'Бекор',
];
?>
<h2 class="reveal">Панели менеҷер</h2>

<div class="tabs reveal">
  <?php foreach ($tabs as $k => $label): ?>
    <a class="btn sm <?= $tab === $k ? '' : 'ghost' ?>" href="/manager?t=<?= $k ?>">
      <?= e($label) ?> (<?= (int)($counts[$k] ?? 0) ?>)
    </a>
  <?php endforeach; ?>
</div>

<div class="list">
<?php foreach ($rows as $b): ?>
  <div class="item reveal">
    <div>
      <b><?= e($b['brand'] . ' ' . $b['model']) ?></b><br>
      <small class="muted"><?= e($b['full_name']) ?> · <?= e($b['phone'] ?? '') ?></small><br>
      <span class="muted"><?= e($b['date_start']) ?> → <?= e($b['date_end']) ?></span>
    </div>
    <span class="st <?= e($b['status']) ?>"><?= e($tabs[$b['status']] ?? $b['status']) ?></span>
    <div>
      <?php if ($b['status'] === 'pending'): ?>
        <form method="post" action="/manager/action" style="display:inline">
          <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
          <button class="btn sm" name="a" value="approve">Тасдиқ</button>
          <button class="btn sm ghost" name="a" value="reject">Рад</button>
        </form>
      <?php elseif ($b['status'] === 'approved'): ?>
        <form method="post" action="/manager/action" class="inline-form">
          <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
          <input type="number" name="odo" placeholder="Одометр" value="<?= (int)$b['odometer'] ?>" required style="width:100px">
          <button class="btn sm" name="a" value="issue">Додан</button>
        </form>
      <?php elseif ($b['status'] === 'issued'): ?>
        <form method="post" action="/manager/action" class="inline-form">
          <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
          <input type="number" name="odo" placeholder="Одометри нав" required style="width:110px">
          <button class="btn sm" name="a" value="receive">Қабул</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
<?php if (!$rows): ?>
  <p class="muted">Сабте нест.</p>
<?php endif; ?>
</div>
