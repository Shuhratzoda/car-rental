# Санҷишҳои Кори лабораторӣ 13 — AutoRent (car_rental)

## 1. Пайвастшавии PDO ва конфигуратсия
- [x] `config/config.example.php` дар Git, `config/config.php` дар `.gitignore`
- [x] `App\Core\Database` — DSN бо `charset=utf8mb4`, `ERRMODE_EXCEPTION`, `FETCH_ASSOC`, `EMULATE_PREPARES=false`
- [x] Коркарди истисноҳо → саҳифаи 500, матни SQL танҳо дар `debug=true`

## 2. Prepared statements
- [x] Ҳамаи дархостҳо бо додаҳои корбар — `prepare()` + `execute()` / `bindValue()`
- [x] `LIMIT`/`OFFSET` бо `PDO::PARAM_INT`
- [x] `addcslashes` барои LIKE
- [x] Номҳои сутунҳо танҳо аз рӯйхати сафед (`CarRepository::FIELDS`)

## 3. CRUD + PRG + валидатсия
- [x] CRUD-и мошинҳо: `/cars`, `/cars/create`, `/cars/{id}`, `/cars/{id}/edit`, `/cars/{id}/delete` (POST)
- [x] Пас аз POST-и бомуваффақият — `redirect()` (PRG)
- [x] Ҳангоми хатои валидатсия — 422 ва шакл бо қиматҳои воридшуда
- [x] `App\Core\Validator` (required, maxLength, email, date, in, numeric, min)

## 4. Ҷустуҷӯ, филтр, саҳифабандӣ, JOIN, ҳисобот
- [x] Ҷустуҷӯ аз рӯи бренд/модел (`CONCAT_WS` + LIKE)
- [x] Филтр аз рӯи тариф ва статус
- [x] Саҳифабандӣ (10 сабт, LIMIT/OFFSET)
- [x] JOIN cars + tariffs
- [x] Ҳисоботи даромад `/reports/income` (агрегатсия бо SUM/COUNT)

## 5. Дигар
- [x] Репозиторийҳо: Car, Booking, Tariff, User, Rental
- [x] Масирҳо бо Router
- [x] Теги `lab-13` (пас аз push)

## Чӣ тавр санҷидан
1. `mysql -u root < database/schema.sql`
2. `cp config/config.example.php config/config.php` (агар лозим)
3. DocumentRoot = `public/`
4. Ворид шавед: admin@rent.tj / password
5. CRUD-и мошинҳо, ҷустуҷӯ, филтр, саҳифабандӣ, бандкунӣ, менеҷер, ҳисобот
