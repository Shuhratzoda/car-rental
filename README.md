# AutoRent — Кори лабораторӣ 11 + 13

**Lab 11:** JavaScript (валидатсия, ҷустуҷӯи фаврӣ, модал)  
**Lab 13:** PDO, Репозиторийҳо, CRUD, саҳифабандӣ

## Насб

1. `database/schema.sql`-ро дар phpMyAdmin Import кунед
2. DocumentRoot = папкаи **`public/`**
3. `config/config.php` — агар лозим, пароли MySQL
4. `http://localhost/...`

### Корбарони демо (рамз: `password`)
- admin@rent.tj — admin
- manager@rent.tj — manager  
- client@rent.tj — client

## Lab 11 (Frontend JS)

| Имконият | Ҷой |
|----------|-----|
| ES-модул | `public/assets/js/app.js` |
| Валидатсия | шаклҳои `data-validate` |
| Ҷустуҷӯи фаврӣ | `/cars` + `/api/cars/index.php` |
| Debounce + AbortController | `app.js` |
| textContent (XSS) | `renderResults()` |
| Модали нест кардан | `data-confirm` + `#confirm-modal` |

## Lab 13 (Backend)

- PDO + Database class
- CarRepository, BookingRepository, ...
- CRUD бо PRG ва Validator
- Ҷустуҷӯ, филтр, саҳифабандӣ, JOIN, ҳисобот

## Сохтор

```
public/
  assets/js/app.js     ← Lab 11
  api/cars/index.php   ← API-и муваққатӣ
  index.php
src/ Controllers, Repositories, Core
views/
docs/11-checks.md  docs/13-checks.md
```
