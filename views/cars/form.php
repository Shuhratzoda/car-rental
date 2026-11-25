<h2 class="reveal"><?= e($title) ?></h2>

<form class="panel reveal" method="post" action="<?= $id ? '/cars/' . $id . '/edit' : '/cars/create' ?>" data-validate>
  <?php
    $fields = [
      'brand' => ['label' => 'Бренд', 'type' => 'text', 'required' => true, 'maxlength' => 50],
      'model' => ['label' => 'Модел', 'type' => 'text', 'required' => true, 'maxlength' => 50],
      'year'  => ['label' => 'Сол', 'type' => 'number', 'required' => true, 'min' => 1990, 'max' => date('Y')+1],
      'price_day' => ['label' => 'Нарх / рӯз ($)', 'type' => 'number', 'step' => '0.01', 'required' => true, 'min' => 1],
      'odometer' => ['label' => 'Одометр (км)', 'type' => 'number', 'min' => 0],
      'image' => ['label' => 'URL-и расм', 'type' => 'url'],
    ];
  ?>
  <?php foreach ($fields as $name => $f): ?>
    <label>
      <?= e($f['label']) ?>
      <input
        type="<?= e($f['type']) ?>"
        name="<?= e($name) ?>"
        value="<?= e((string)($data[$name] ?? '')) ?>"
        <?= !empty($f['required']) ? 'required' : '' ?>
        <?= isset($f['step']) ? 'step="' . e($f['step']) . '"' : '' ?>
        <?= isset($f['min']) ? 'min="' . e((string)$f['min']) . '"' : '' ?>
        <?= isset($f['max']) ? 'max="' . e((string)$f['max']) . '"' : '' ?>
        <?= isset($f['maxlength']) ? 'maxlength="' . (int)$f['maxlength'] . '"' : '' ?>
      >
      <?php if (!empty($errors[$name])): ?>
        <small class="err"><?= e($errors[$name]) ?></small>
      <?php endif; ?>
    </label>
  <?php endforeach; ?>

  <label>
    Тариф
    <select name="tariff_id" required>
      <?php foreach ($tariffs as $t): ?>
        <option value="<?= (int)$t['id'] ?>" <?= ((int)($data['tariff_id'] ?? 0) === (int)$t['id']) ? 'selected' : '' ?>>
          <?= e($t['name']) ?> (<?= (int)$t['km_per_day'] ?> км/рӯз)
        </option>
      <?php endforeach; ?>
    </select>
    <?php if (!empty($errors['tariff_id'])): ?><small class="err"><?= e($errors['tariff_id']) ?></small><?php endif; ?>
  </label>

  <label>
    Статус
    <select name="status" required>
      <option value="active" <?= ($data['status'] ?? '') === 'active' ? 'selected' : '' ?>>Фаъол</option>
      <option value="inactive" <?= ($data['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Ғайрифаъол</option>
    </select>
  </label>

  <div class="row">
    <button class="btn" type="submit"><?= $id ? 'Сабт кардан' : 'Илова кардан' ?></button>
    <a class="btn ghost" href="/cars">Бозгашт</a>
  </div>
</form>
