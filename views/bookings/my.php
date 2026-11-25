<?php
$L = [
  'pending'   => 'Интизор',
  'approved'  => 'Тасдиқшуд',
  'rejected'  => 'Рад шуд',
  'issued'    => 'Дар иҷора',
  'returned'  => 'Баргардонида шуд',
  'cancelled' => 'Бекор',
];
?>
<h2 class="reveal">Бандҳои ман</h2>
<div class="list">
<?php foreach ($rows as $b): ?>
  <div class="item reveal">
    <div>
      <b><?= e($b['brand'] . ' ' . $b['model']) ?></b><br>
      <span class="muted"><?= e($b['date_start']) ?> → <?= e($b['date_end']) ?></span>
    </div>
    <span class="st <?= e($b['status']) ?>"><?= e($L[$b['status']] ?? $b['status']) ?></span>
    <div>
      <?php if ($b['total'] !== null): ?>
        <b><?= money($b['total']) ?></b><br><small class="muted"><?= (int)$b['km_driven'] ?> км</small>
      <?php else: ?>
        <?= money($b['total_est']) ?> <small class="muted">(тахминӣ)</small>
      <?php endif; ?>
    </div>
    <?php if (in_array($b['status'], ['pending', 'approved'], true)): ?>
      <form method="post" action="/my/cancel">
        <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
        <button class="btn sm ghost" type="submit">Бекор</button>
      </form>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
<?php if (!$rows): ?>
  <p class="muted">Ҳоло бандӣ нест.</p>
<?php endif; ?>
</div>
