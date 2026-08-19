/**
 * MAURICE APPLIANCES — Universal Inquiry & Lead Modal
 * Handles SKU prefilled inquiries, dealer inquiries, and WhatsApp direct links.
 */

import { COMPANY } from '../data/products.js';
import { showToast } from '../core/catalog-utils.js';

export function initInquiryModal() {
  let modal = document.getElementById('inquiryModal');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'inquiryModal';
    modal.className = 'modal';
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    modal.innerHTML = `
      <div class="modal__backdrop" id="inquiryBackdrop"></div>
      <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="inquiryTitle">
        <button type="button" class="modal__close" id="closeInquiryBtn" aria-label="Close modal">&times;</button>
        <div class="modal__head">
          <span class="eyebrow" style="margin-bottom:var(--s-2)">Direct Factory Inquiry</span>
          <h2 id="inquiryTitle" style="font-size:var(--fs-h3)">Inquire About Product</h2>
          <p class="modal__lead text-muted" id="inquirySubtitle">Get instant trade pricing, bulk discounts, and specifications directly from our Kullu & Delhi manufacturing units.</p>
        </div>
        <form class="modal__form" id="inquiryForm" novalidate>
          <div class="field">
            <label for="inq-product">Selected Product / Model</label>
            <input type="text" id="inq-product" name="product" readonly style="background:var(--surface-2);font-weight:600;color:var(--ink)">
          </div>
          <div class="fieldgrid" style="display:grid;grid-template-columns:1fr 1fr;gap:var(--s-3)">
            <div class="field">
              <label for="inq-name">Your Name <span class="req">*</span></label>
              <input type="text" id="inq-name" name="name" required placeholder="Full Name">
            </div>
            <div class="field">
              <label for="inq-phone">Phone / WhatsApp <span class="req">*</span></label>
              <input type="tel" id="inq-phone" name="phone" required placeholder="10-digit mobile">
            </div>
          </div>
          <div class="fieldgrid" style="display:grid;grid-template-columns:1fr 1fr;gap:var(--s-3)">
            <div class="field">
              <label for="inq-email">Email Address</label>
              <input type="email" id="inq-email" name="email" placeholder="name@company.com">
            </div>
            <div class="field">
              <label for="inq-city">City / State <span class="req">*</span></label>
              <input type="text" id="inq-city" name="city" required placeholder="e.g. Shimla, HP">
            </div>
          </div>
          <div class="field">
            <label for="inq-type">Inquiry Type</label>
            <select id="inq-type" name="inquiry_type">
              <option value="Dealer & Retail Price">Dealer / Distribution Trade Pricing</option>
              <option value="Bulk Order">Bulk Purchase (Institutions / Govt / Builders)</option>
              <option value="OEM / Private Label">OEM / Private Label Manufacturing</option>
              <option value="Retail Customer">Individual Customer Purchase</option>
            </select>
          </div>
          <div class="field">
            <label for="inq-notes">Requirement / Message (Optional)</label>
            <textarea id="inq-notes" name="notes" rows="2" placeholder="Tell us about your estimated quantity or specific questions..."></textarea>
          </div>
          <div class="modal__actions" style="display:flex;gap:var(--s-3);margin-top:var(--s-4);flex-wrap:wrap">
            <button type="submit" class="btn" style="flex:1" id="submitInquiryBtn">
              <span>Submit Inquiry</span>
              <svg class="arrow" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <button type="button" class="btn btn--ghost" id="inquiryWhatsAppBtn" style="color:#25D366;border-color:rgba(37,211,102,0.4)">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 14.99 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/></svg>
              <span>Instant WhatsApp</span>
            </button>
          </div>
        </form>
      </div>
    `;
    document.body.appendChild(modal);
  }

  const form = document.getElementById('inquiryForm');
  const productInput = document.getElementById('inq-product');
  const waBtn = document.getElementById('inquiryWhatsAppBtn');

  function openModal(productName = '') {
    if (productInput) productInput.value = productName || 'Maurice Home Appliance General Inquiry';
    modal.style.display = 'flex';
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeModal() {
    modal.classList.remove('is-open');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  // Global click delegator for open & close buttons
  document.addEventListener('click', (e) => {
    // Open modal
    const openBtn = e.target.closest('.open-inquiry-btn');
    if (openBtn) {
      e.preventDefault();
      const model = openBtn.dataset.model || '';
      openModal(model);
      return;
    }

    // Close modal
    if (e.target.closest('#closeInquiryBtn') || e.target.closest('#inquiryBackdrop')) {
      e.preventDefault();
      closeModal();
    }
  });

  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
  });

  // Handle WhatsApp Direct Send
  waBtn?.addEventListener('click', () => {
    const p = productInput.value;
    const name = document.getElementById('inq-name').value.trim();
    const city = document.getElementById('inq-city').value.trim();
    const type = document.getElementById('inq-type').value;
    const text = encodeURIComponent(`Hello Maurice Appliances Team, I would like to inquire about *${p}*. \nType: ${type}\nName: ${name || 'Customer'}\nLocation: ${city || 'India'}`);
    window.open(`https://wa.me/${COMPANY.whatsapp.replace(/[^0-9]/g, '')}?text=${text}`, '_blank');
  });

  // Handle Form Submission
  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    const name = document.getElementById('inq-name').value.trim();
    const phone = document.getElementById('inq-phone').value.trim();
    const city = document.getElementById('inq-city').value.trim();

    if (!name || !phone || !city) {
      showToast('Please fill in your Name, Phone Number, and City.', 'warning');
      return;
    }

    // Save lead to localStorage
    const lead = {
      id: Date.now(),
      product: productInput.value,
      name,
      phone,
      email: document.getElementById('inq-email').value.trim(),
      city,
      type: document.getElementById('inq-type').value,
      notes: document.getElementById('inq-notes').value.trim(),
      date: new Date().toISOString()
    };
    try {
      const existing = JSON.parse(localStorage.getItem('maurice_leads') || '[]');
      existing.push(lead);
      localStorage.setItem('maurice_leads', JSON.stringify(existing));
    } catch (err) {}

    showToast('Thank you! Your inquiry has been submitted. Our regional sales team will contact you shortly.', 'success');
    form.reset();
    closeModal();
  });
}
