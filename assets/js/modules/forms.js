/**
 * MAURICE APPLIANCES — Form Handling (Client-side validation, localStorage backup & toast feedback)
 */

import { showToast } from '../core/catalog-utils.js';

const EMAIL_RE = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

export function initForms() {
  initNewsletter();
  initAjaxForms();
}

/* ---------------- Newsletter (Footer) ---------------- */
function initNewsletter() {
  const form = document.getElementById('newsletterForm');
  if (!form) return;

  const msg = document.getElementById('newsletterMsg');
  const setMsg = (text, ok) => {
    if (!msg) return;
    msg.textContent = text;
    msg.className = 'footer__form-msg ' + (ok ? 'ok' : 'err');
  };

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const input = form.querySelector('input[name="email"]');
    const email = (input?.value || '').trim();

    if (!EMAIL_RE.test(email)) {
      setMsg('Please enter a valid email address.', false);
      input?.focus();
      return;
    }

    try {
      const subs = JSON.parse(localStorage.getItem('maurice_newsletter_subscribers') || '[]');
      subs.push({ email, date: new Date().toISOString() });
      localStorage.setItem('maurice_newsletter_subscribers', JSON.stringify(subs));
    } catch (err) {}

    setMsg('Thank you for subscribing to Maurice updates!', true);
    showToast('Subscribed successfully! You will receive new launch updates.', 'success');
    form.reset();
  });
}

/* ---------------- Generic Forms (Contact, Dealer, Warranty, Service) ---------------- */
function initAjaxForms() {
  document.querySelectorAll('[data-ajax-form]').forEach((form) => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      const out = form.querySelector('[data-form-msg]');
      const btn = form.querySelector('[type="submit"]');
      const setMsg = (text, ok) => {
        if (!out) return;
        out.textContent = text;
        out.className = 'form-msg ' + (ok ? 'ok' : 'err');
      };

      // Honeypot for bot detection
      const hp = form.querySelector('input[name="website"]');
      if (hp && hp.value) return;

      // Required field validation
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
        setMsg('Please complete all highlighted mandatory fields.', false);
        showToast('Please check the required fields.', 'warning');
        firstBad?.focus();
        return;
      }

      // Collect form data
      const formData = new FormData(form);
      const dataObj = {};
      formData.forEach((val, key) => { dataObj[key] = val; });
      dataObj.submittedAt = new Date().toISOString();

      // Store in localStorage
      try {
        const formType = form.dataset.formType || 'general_submission';
        const key = `maurice_form_${formType}`;
        const existing = JSON.parse(localStorage.getItem(key) || '[]');
        existing.push(dataObj);
        localStorage.setItem(key, JSON.stringify(existing));
      } catch (err) {}

      const originalLabel = btn ? btn.innerHTML : '';
      if (btn) { btn.disabled = true; btn.innerHTML = 'Submitting...'; }

      setTimeout(() => {
        if (btn) { btn.disabled = false; btn.innerHTML = originalLabel; }
        setMsg('Thank you! Your request has been recorded. Our team will contact you shortly.', true);
        showToast('Form submitted successfully! We will contact you soon.', 'success');
        form.reset();
      }, 500);
    });
  });
}
