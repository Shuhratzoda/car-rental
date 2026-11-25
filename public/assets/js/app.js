/**
 * AutoRent — Lab 11: JavaScript (валидатсия, ҷустуҷӯи фаврӣ, модал)
 * ES-модул: type="module"
 */

// ---------- утилитаҳо ----------
export function debounce(fn, delay = 300) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
}

function money(n) {
  return Number(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' $';
}

// ---------- 1. Валидатсияи шакл (Constraint Validation API) ----------
const MESSAGES = {
  valueMissing: 'Ин майдон ҳатмист',
  typeMismatch: 'Формати нодуруст',
  tooShort: 'Хеле кӯтоҳ',
  tooLong: 'Хеле дароз',
  patternMismatch: 'Формат мувофиқат намекунад',
  rangeUnderflow: 'Қимат хеле хурд',
  rangeOverflow: 'Қимат хеле калон',
  stepMismatch: 'Қимати нодуруст',
  badInput: 'Воридоти нодуруст',
};

function getMessage(field) {
  const v = field.validity;
  if (v.valueMissing) return MESSAGES.valueMissing;
  if (v.typeMismatch) {
    if (field.type === 'email') return 'E-mail нодуруст аст';
    if (field.type === 'url') return 'URL нодуруст аст';
    return MESSAGES.typeMismatch;
  }
  if (v.tooShort) return `На камтар аз ${field.minLength} аломат`;
  if (v.tooLong) return `На зиёда аз ${field.maxLength} аломат`;
  if (v.rangeUnderflow) return `На камтар аз ${field.min}`;
  if (v.rangeOverflow) return `На зиёда аз ${field.max}`;
  if (v.patternMismatch) return field.title || MESSAGES.patternMismatch;
  if (v.customError) return field.validationMessage;
  return 'Хатои воридот';
}

function showError(field, message) {
  field.setCustomValidity(message || '');
  let err = field.parentElement?.querySelector('.js-error');
  if (!err) {
    err = document.createElement('small');
    err.className = 'err js-error';
    field.parentElement?.appendChild(err);
  }
  err.textContent = message || '';
  field.classList.toggle('is-invalid', !!message);
}

function clearError(field) {
  field.setCustomValidity('');
  const err = field.parentElement?.querySelector('.js-error');
  if (err) err.textContent = '';
  field.classList.remove('is-invalid');
}

function validateField(field) {
  // Қоидаҳои худӣ
  if (field.name === 'date_end' || field.name === 'e') {
    const form = field.form;
    const start = form?.querySelector('[name="date_start"], [name="s"]');
    if (start && field.value && start.value && field.value < start.value) {
      field.setCustomValidity('Санаи анҷом аз оғоз хурдтар буда наметавонад');
    } else {
      field.setCustomValidity('');
    }
  }
  if (field.name === 'password' && field.value && field.value.length < 6) {
    field.setCustomValidity('Рамз на камтар аз 6 аломат');
  }
  if (field.name === 'year') {
    const y = parseInt(field.value, 10);
    if (y && (y < 1990 || y > new Date().getFullYear() + 1)) {
      field.setCustomValidity('Сол нодуруст аст');
    } else {
      field.setCustomValidity('');
    }
  }

  if (!field.validity.valid) {
    showError(field, getMessage(field));
    return false;
  }
  clearError(field);
  return true;
}

function initFormValidation() {
  document.querySelectorAll('form[data-validate]').forEach((form) => {
    form.setAttribute('novalidate', '');

    form.querySelectorAll('input, select, textarea').forEach((field) => {
      field.addEventListener('input', () => validateField(field));
      field.addEventListener('blur', () => validateField(field));
      field.addEventListener('change', () => validateField(field));
    });

    form.addEventListener('submit', (e) => {
      let ok = true;
      form.querySelectorAll('input, select, textarea').forEach((field) => {
        if (!validateField(field)) ok = false;
      });
      if (!ok) {
        e.preventDefault();
        const first = form.querySelector('.is-invalid');
        first?.focus();
      }
    });
  });
}

// ---------- 2. Ҷустуҷӯи фаврӣ (debounce + fetch + AbortController) ----------
function initLiveSearch() {
  const input = document.querySelector('[data-live-search]');
  const results = document.querySelector('[data-live-results]');
  if (!input || !results) return;

  let controller = null;

  const doSearch = async (q) => {
    if (controller) controller.abort();
    controller = new AbortController();

    results.innerHTML = '';
    const loading = document.createElement('p');
    loading.className = 'muted';
    loading.textContent = 'Ҷустуҷӯ...';
    results.appendChild(loading);

    try {
      const url = `/api/cars/index.php?q=${encodeURIComponent(q)}`;
      const res = await fetch(url, { signal: controller.signal });
      if (!res.ok) throw new Error('Хатои шабака');
      const data = await res.json();
      renderResults(results, data.items || []);
    } catch (err) {
      if (err.name === 'AbortError') return;
      results.innerHTML = '';
      const p = document.createElement('p');
      p.className = 'muted';
      p.textContent = 'Хато ҳангоми ҷустуҷӯ';
      results.appendChild(p);
    }
  };

  const debounced = debounce((e) => {
    const q = e.target.value.trim();
    if (q.length < 1) {
      results.innerHTML = '';
      return;
    }
    doSearch(q);
  }, 300);

  input.addEventListener('input', debounced);
}

/** Намоиши бехатар бо textContent (ҳимоя аз XSS) */
function renderResults(container, items) {
  container.replaceChildren();

  if (!items.length) {
    const p = document.createElement('p');
    p.className = 'muted';
    p.textContent = 'Ҳеҷ чиз ёфт нашуд';
    container.appendChild(p);
    return;
  }

  const list = document.createElement('div');
  list.className = 'list';

  items.forEach((car) => {
    const item = document.createElement('div');
    item.className = 'item reveal in';

    const left = document.createElement('div');
    const title = document.createElement('b');
    // textContent — XSS-ро пешгирӣ мекунад
    title.textContent = `${car.brand} ${car.model}`;
    const meta = document.createElement('small');
    meta.className = 'muted';
    meta.textContent = `${car.year} · ${car.tariff_name} · ${money(car.price_day)}/рӯз`;
    left.append(title, document.createElement('br'), meta);

    const status = document.createElement('span');
    status.className = `st ${car.status}`;
    status.textContent = car.status === 'active' ? 'Фаъол' : 'Ғайрифаъол';

    const link = document.createElement('a');
    link.className = 'btn sm';
    link.href = `/cars/${car.id}`;
    link.textContent = 'Дидан';

    item.append(left, status, link);
    list.appendChild(item);
  });

  container.appendChild(list);
}

// ---------- 3. Модали тасдиқи нест кардан ----------
function initDeleteModal() {
  const modal = document.getElementById('confirm-modal');
  if (!modal) return;

  const titleEl = modal.querySelector('[data-modal-title]');
  const bodyEl = modal.querySelector('[data-modal-body]');
  const form = modal.querySelector('form');
  let activeForm = null;

  // Вогузории ҳодиса: ҳамаи тугмаҳои [data-confirm]
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-confirm]');
    if (!btn) return;
    e.preventDefault();

    activeForm = btn.closest('form');
    const msg = btn.getAttribute('data-confirm') || 'Шумо мутмаин ҳастед?';
    if (titleEl) titleEl.textContent = 'Тасдиқ';
    if (bodyEl) bodyEl.textContent = msg;
    modal.classList.add('open');
  });

  modal.querySelector('[data-modal-cancel]')?.addEventListener('click', () => {
    modal.classList.remove('open');
    activeForm = null;
  });

  modal.querySelector('[data-modal-ok]')?.addEventListener('click', () => {
    if (activeForm) activeForm.submit();
    modal.classList.remove('open');
  });

  // Пӯшидан бо клик берун
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.classList.remove('open');
      activeForm = null;
    }
  });
}

// ---------- 4. Ҳисоби рӯзҳо дар шакли банд ----------
function initBookingCalc() {
  const s = document.querySelector('[name="date_start"], #s');
  const e = document.querySelector('[name="date_end"], #e');
  const sum = document.querySelector('.sum');
  if (!s || !e || !sum) return;

  const update = () => {
    const days = Math.max(1, Math.round((new Date(e.value) - new Date(s.value)) / 864e5) + 1);
    const price = +(sum.dataset.price || 0);
    const dd = document.getElementById('dd');
    const tt = document.getElementById('tt');
    if (dd) dd.textContent = String(days);
    if (tt) tt.textContent = money(days * price);
    e.min = s.value;
  };
  s.addEventListener('change', update);
  e.addEventListener('change', update);
}

// ---------- 5. Аниматсияҳои UI (аз лоиҳаи қаблӣ) ----------
function initUI() {
  const io = new IntersectionObserver(
    (es) =>
      es.forEach((x) => {
        if (x.isIntersecting) {
          x.target.classList.add('in');
          io.unobserve(x.target);
        }
      }),
    { threshold: 0.1 }
  );
  document.querySelectorAll('.reveal').forEach((el, i) => {
    el.style.transitionDelay = (i % 6) * 70 + 'ms';
    io.observe(el);
  });

  addEventListener('scroll', () => {
    document.getElementById('nav')?.classList.toggle('s', scrollY > 20);
  });

  document.querySelectorAll('.tilt').forEach((c) => {
    c.addEventListener('mousemove', (e) => {
      const r = c.getBoundingClientRect();
      const x = (e.clientX - r.left) / r.width - 0.5;
      const y = (e.clientY - r.top) / r.height - 0.5;
      c.style.transform = `perspective(800px) rotateY(${x * 8}deg) rotateX(${-y * 8}deg) translateY(-4px)`;
    });
    c.addEventListener('mouseleave', () => (c.style.transform = ''));
  });

  document.querySelectorAll('[data-n]').forEach((el) => {
    const t = +el.dataset.n;
    let n = 0;
    const s = Math.max(1, t / 40);
    const id = setInterval(() => {
      n = Math.min(t, n + s);
      el.textContent = String(Math.round(n));
      if (n >= t) clearInterval(id);
    }, 30);
  });
}

// ---------- Оғоз ----------
initFormValidation();
initLiveSearch();
initDeleteModal();
initBookingCalc();
initUI();
