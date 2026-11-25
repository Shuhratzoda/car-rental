# AutoRent — Кори лабораторӣ 13

**PDO + Репозиторийҳо + CRUD + ҷустуҷӯ/филтр/саҳифабандӣ + JOIN + PRG + валидатсия**

Лоиҳаи иҷораи мошин (car_rental), ки мувофиқи талаботи Кори лабораторӣ 13 сохта шудааст (намунаи university_hr ба мавзӯи AutoRent мутобиқ карда шудааст).

## Насб

1. Файлуҳоро ба `C:\xampp\htdocs\autorent` (ё ҷои дигар) гузоред.
2. Дар phpMyAdmin файли `database/schema.sql`-ро Import кунед.
3. `cp config/config.example.php config/config.php` ва агар лозим бошад паролро иваз кунед.
4. DocumentRoot-ро ба папкаи **`public/`** нишон диҳед (ё Apache Alias).
5. Кушоед: `http://localhost/autorent/` (ё мувофиқи танзимот).

### Корбарони демо (рамз: `password`)

| E-mail            | Нақш    |
|-------------------|---------|
| admin@rent.tj     | admin   |
| manager@rent.tj   | manager |
| client@rent.tj    | client  |

## Сохтор

```
config/          — конфигуратсия (example + config.php дар .gitignore)
database/        — schema.sql
public/          — нуқтаи вуруд (index.php) + assets + .htaccess
src/
  Core/          — Database, Router, View, Validator, helpers
  Controllers/   — Home, Auth, Car, Booking, Manager, Report
  Repositories/  — Car, Booking, Tariff, User, Rental
views/           — шаблонҳо
docs/13-checks.md
```

## Имкониятҳои Lab-13

- Пайвастшавии PDO бо `utf8mb4`, истисноҳо, конфиг берун аз Git
- Ҳамаи дархостҳо бо prepared statements
- CRUD-и мошинҳо бо валидатсияи серверӣ ва PRG
- Ҷустуҷӯ, филтр (тариф/статус), саҳифабандӣ
- JOIN (cars + tariffs)
- Ҳисоботи даромад
- Репозиторийҳо ва рӯйхати сафед барои майдонҳо

## Меъёрҳои баҳогузорӣ (мувофиқи PDF)

| Меъёр                                      | Хол |
|--------------------------------------------|-----|
| PDO, конфиг, коркарди хатоҳо               | 1   |
| Prepared statements, ҳимоя аз инъексия     | 2   |
| CRUD + PRG + валидатсия                    | 2   |
| Ҷустуҷӯ, филтр, саҳифабандӣ, JOIN, ҳисобот | 2   |
| docs/13-checks.md, тег lab-13              | 1   |
| Ҳимоя                                      | 2   |
| **Ҳамагӣ**                                 | **10** |
