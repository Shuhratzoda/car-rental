<h2 class="reveal">Ҳисоботи даромад</h2>

<form class="panel reveal fgrid" method="get">
  <label>Аз <input type="date" name="from" value="<?= e($from) ?>"></label>
  <label>То <input type="date" name="to" value="<?= e($to) ?>"></label>
  <button class="btn">Намоиш</button>
</form>

<div class="stats reveal">
  <div><b><?= money($tot) ?></b><span>Даромади умумӣ</span></div>
</div>

<div class="list">
<?php foreach ($rep as $r): ?>
  <div class="item reveal">
    <div>
      <b><?= e($r['brand'] . ' ' . $r['model']) ?></b><br>
      <small class="muted"><?= (int)$r['n'] ?> иҷора · иловагӣ <?= money($r['ex']) ?></small>
    </div>
    <div class="bar"><i style="width:<?= round($r['sum_total'] / $mx * 100) ?>%"></i></div>
    <b><?= money($r['sum_total']) ?></b>
  </div>
<?php endforeach; ?>
<?php if (!$rep): ?>
  <p class="muted">Маълумот нест.</p>
<?php endif; ?>
</div>
