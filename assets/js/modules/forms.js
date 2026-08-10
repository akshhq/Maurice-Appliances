/* Form handling — newsletter + generic AJAX forms.
   Progressive enhancement: every form has a real `action` and `method`,
   so submissions still work if JavaScript fails to load. */

const EMAIL_RE = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

export function initForms() {
  initNewsletter();
  initAjaxForms();
}

/* ---------------- Newsletter (footer) ---------------- */
function initNewsletter() {
  const form = document.getElementById('newsletterForm');
  if (!form) return;

  const msg = document.getElementById('newsletterMsg');
  const setMsg = (text, ok) => {
    if (!msg) return;
    msg.textContent = text;
    msg.className = 'footer__form-msg ' + (ok ? 'ok' : 'err');
  };

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = form.querySelector('input[name="email"]');
    const btn = form.querySelector('[type="submit"]');
    const email = (input?.value || '').trim();

    if (!EMAIL_RE.test(email)) {
      setMsg('Please enter a valid email address.', false);
      input?.focus();
      return;
    }

    if (btn) btn.disabled = true;
    setMsg('Subscribing…', true);

    try {
      const res = await fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'fetch' },
      });
      const data = await res.json().catch(() => ({ ok: res.ok }));
      setMsg(data.message || (data.ok ? 'Thank you — you are on the list.' : 'Something went wrong.'), !!data.ok);
      if (data.ok) form.reset();
    } catch {
      setMsg('Network error. Please try again shortly.', false);
    } finally {
      if (btn) btn.disabled = false;
    }
  });
}

/* ---------------- Generic AJAX forms ---------------- */
function initAjaxForms() {
  document.querySelectorAll('[data-ajax-form]').forEach((form) => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const out = form.querySelector('[data-form-msg]');
      const btn = form.querySelector('[type="submit"]');
      const setMsg = (text, ok) => {
        if (!out) return;
        out.textContent = text;
        out.className = 'form-msg ' + (ok ? 'ok' : 'err');
      };

      // Honeypot — silently succeed for bots.
      const hp = form.querySelector('input[name="website"]');
      if (hp && hp.value) return;

      // Client-side required/email validation
      let valid = true;
      let firstBad = null;
      form.querySelectorAll('[required]').forEach((field) => {
        const val = (field.value || '').trim();
        const bad = !val || (field.type === 'email' && !EMAIL_RE.test(val));
        field.classList.toggle('invalid', bad);
        field.setAttribute('aria-invalid', bad ? 'true' : 'false');
        if (bad && !firstBad) firstBad = field;
        if (bad) valid = false;
      });
      if (!valid) {
        setMsg('Please complete the highlighted fields.', false);
        firstBad?.focus();
        return;
      }

      const originalLabel = btn ? btn.textContent : '';
      if (btn) { btn.disabled = true; btn.textContent = 'Sending…'; }

      try {
        const res = await fetch(form.action, {
          method: form.method || 'POST',
          body: new FormData(form),
          headers: { 'X-Requested-With': 'fetch' },
        });
        const data = await res.json().catch(() => ({ ok: res.ok }));
        setMsg(data.message || (data.ok ? 'Thank you — we will be in touch.' : 'Something went wrong. Please try again.'), !!data.ok);
        if (data.ok) form.reset();
      } catch {
        setMsg('Network error. Please try again, or contact customer support.', false);
      } finally {
        if (btn) { btn.disabled = false; btn.textContent = originalLabel; }
      }
    });
  });
}
