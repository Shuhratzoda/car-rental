<div class="panel reveal" style="max-width:420px;margin:2rem auto">
  <h2>Бақайдгирӣ</h2>
  <form method="post" action="/register" data-validate>
    <label>Ному насаб
      <input type="text" name="full_name" required maxlength="120" value="<?= e($old['full_name'] ?? '') ?>">
      <?php if (!empty($errors['full_name'])): ?><small class="err"><?= e($errors['full_name']) ?></small><?php endif; ?>
    </label>
    <label>E-mail
      <input type="email" name="email" required value="<?= e($old['email'] ?? '') ?>">
      <?php if (!empty($errors['email'])): ?><small class="err"><?= e($errors['email']) ?></small><?php endif; ?>
    </label>
    <label>Телефон
      <input type="text" name="phone" value="<?= e($old['phone'] ?? '') ?>">
    </label>
    <label>Рамз
      <input type="password" name="password" required minlength="6">
      <?php if (!empty($errors['password'])): ?><small class="err"><?= e($errors['password']) ?></small><?php endif; ?>
    </label>
    <button class="btn" type="submit">Бақайдгирӣ</button>
  </form>
  <p class="muted">Аллакай ҳисоб доред? <a href="/login">Вуруд</a></p>
</div>
