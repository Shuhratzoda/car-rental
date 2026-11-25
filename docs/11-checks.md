# Санҷишҳои Кори лабораторӣ 11 — AutoRent

## 1. Беҳбудии прогрессивӣ + ES-модул
- [x] Саҳифа бе JS ҳам кор мекунад (шаклҳо GET/POST-и оддӣ)
- [x] `<script type="module" src="/assets/js/app.js">`
- [x] Модул пас аз таҳлили HTML иҷро мешавад

## 2. Валидатсия (Constraint Validation API)
- [x] `data-validate` + `novalidate`
- [x] Паёмҳои тоҷикӣ (`setCustomValidity`)
- [x] Санҷиши санаҳо, email, minlength, required
- [x] Хатоҳо ҳангоми `input`/`blur` нишон дода мешаванд

## 3. Ҷустуҷӯи фаврӣ
- [x] `debounce(300ms)`
- [x] `fetch` + `async/await` + `AbortController`
- [x] API: `/api/cars/index.php?q=...`
- [x] Намоиш бо `textContent` (ҳимоя аз XSS)

## 4. Модали тасдиқи нест кардан
- [x] `data-confirm` + event delegation
- [x] Модал пеш аз DELETE
- [x] Нест кардан танҳо бо POST

## 5. XSS
- [x] Сабти тестӣ бо `<img onerror=...>` — ҳамчун матн нишон дода мешавад
- [x] `textContent` / `createElement`, на `innerHTML` бо додаҳои сервер

## Чӣ тавр санҷидан
1. `/cars` — ҷустуҷӯи фаврӣ нависед
2. `/cars/create` — шаклро нодуруст пур кунед (валидатсия)
3. Тугмаи «Нест» — модал мебарояд
4. DevTools → Network: дархостҳои fetch бо Abort
