<div class="panel reveal" style="max-width:420px;margin:2rem auto">
  <h2>Вуруд</h2>
  <form method="post" action="/login" data-validate>
    <label>E-mail
      <input type="email" name="email" required value="<?= e($old['email'] ?? '') ?>">
    </label>
    <label>Рамз
      <input type="password" name="password" required minlength="6">
    </label>
    <button class="btn" type="submit">Ворид шудан</button>
  </form>
  <p class="muted">Ҳанӯз ҳисоб надоред? <a href="/register">Бақайдгирӣ</a></p>
  <p class="muted" style="font-size:.85rem">Демо: admin@rent.tj / manager@rent.tj / client@rent.tj — рамз: <b>password</b></p>
</div>
