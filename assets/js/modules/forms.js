/**
 * MAURICE APPLIANCES — Form Handling (AJAX submissions to Hostinger PHP backend)
 */

import { showToast } from '../core/catalog-utils.js?v=3.0';

const EMAIL_RE = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;

function getApiEndpoint() {
  const isSubfolder = ['/company/', '/dealers/', '/support/', '/contact/', '/legal/', '/pages/'].some(p => window.location.pathname.includes(p));
  return isSubfolder ? '../api/submit-form.php' : './api/submit-form.php';
}

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

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = form.querySelector('input[name="email"]');
    const email = (input?.value || '').trim();

    if (!EMAIL_RE.test(email)) {
      setMsg('Please enter a valid email address.', false);
      input?.focus();
      return;
    }

    const btn = form.querySelector('button[type="submit"]');
    const originalBtnContent = btn ? btn.innerHTML : '';
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = 'Subscribing...';
    }

    const formData = new FormData();
    formData.append('email', email);
    formData.append('formType', 'newsletter_subscription');

    try {
      const response = await fetch(getApiEndpoint(), {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error(result.message || 'Subscription failed');
      }

      setMsg('Thank you for subscribing to Maurice updates!', true);
      showToast('Subscribed successfully! You will receive new launch updates.', 'success');
      form.reset();

    } catch (error) {
      console.error('Newsletter submission error:', error);
      setMsg('Unable to subscribe right now. Please try again later.', false);
      showToast('Unable to subscribe right now. Please try again.', 'error');
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = originalBtnContent;
      }
    }
  });
}

/* ---------------- Generic Forms (Contact, Dealer, Warranty, Service) ---------------- */
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

      const originalLabel = btn ? btn.innerHTML : '';
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = 'Submitting...';
      }

      const formData = new FormData(form);
      formData.append('formType', form.dataset.formType || 'general_submission');

      try {
        const response = await fetch(getApiEndpoint(), {
          method: 'POST',
          body: formData
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
          throw new Error(result.message || 'Submission failed');
        }

        setMsg('Thank you! Your request has been submitted. Our team will contact you shortly.', true);
        showToast('Form submitted successfully! We will contact you soon.', 'success');
        form.reset();

      } catch (error) {
        console.error('Form submission error:', error);
        setMsg('Something went wrong. Please try again later or email customer.care@mauriceappliances.in', false);
        showToast('Unable to submit the form. Please try again later.', 'error');
      } finally {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = originalLabel;
        }
      }
    });
  });
}
