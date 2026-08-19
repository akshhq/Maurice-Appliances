/**
 * MAURICE APPLIANCES — Multi-Product Comparison Engine (LG style)
 * Persistent compare tray + detailed side-by-side spec modal.
 */

import { getProductBySlug, ALL_PRODUCTS } from '../data/products.js';
import { getCompareList, removeFromCompare, clearCompare, formatINR, renderProductVisual } from '../core/catalog-utils.js';

export function initCompareDrawer(basePath = '') {
  let tray = document.getElementById('compareTray');
  if (!tray) {
    tray = document.createElement('aside');
    tray.id = 'compareTray';
    tray.className = 'compare-tray';
    tray.setAttribute('aria-label', 'Product comparison tray');
    tray.innerHTML = `
      <div class="wrap compare-tray__inner">
        <div class="compare-tray__left">
          <span class="compare-tray__badge"><b id="compareCount">0</b> / 4</span>
          <span class="compare-tray__label">Selected for Comparison</span>
        </div>
        <div class="compare-tray__items" id="compareTrayItems"></div>
        <div class="compare-tray__actions">
          <button type="button" class="btn btn--sm" id="openCompareModalBtn" disabled>
            <span>Compare Now</span>
            <svg class="arrow" width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <button type="button" class="btn btn--sm btn--ghost" id="clearCompareBtn">Clear</button>
        </div>
      </div>
    `;
    document.body.appendChild(tray);
  }

  let modal = document.getElementById('compareModal');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'compareModal';
    modal.className = 'modal modal--wide';
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    modal.innerHTML = `
      <div class="modal__backdrop" id="compareBackdrop"></div>
      <div class="modal__dialog modal__dialog--wide" role="dialog" aria-modal="true" aria-labelledby="compareModalTitle">
        <button type="button" class="modal__close" id="closeCompareModalBtn" aria-label="Close comparison">&times;</button>
        <div class="modal__head">
          <span class="eyebrow">LG-Inspired Spec Engine</span>
          <h2 id="compareModalTitle">Side-by-Side Appliance Comparison</h2>
          <p class="text-muted">Compare technical specifications, dimensions, power consumption, and warranty terms across chosen models.</p>
        </div>
        <div class="compare-table-wrap" id="compareTableContainer">
          <!-- Injected via JS -->
        </div>
      </div>
    `;
    document.body.appendChild(modal);
  }

  function syncTray() {
    const list = getCompareList();
    const countEl = document.getElementById('compareCount');
    const itemsEl = document.getElementById('compareTrayItems');
    const compareBtn = document.getElementById('openCompareModalBtn');

    if (countEl) countEl.textContent = list.length;
    if (compareBtn) compareBtn.disabled = list.length < 2;

    if (list.length > 0) {
      tray.classList.add('is-visible');
    } else {
      tray.classList.remove('is-visible');
    }

    if (itemsEl) {
      itemsEl.innerHTML = list.map(slug => {
        const p = getProductBySlug(slug);
        if (!p) return '';
        return `
          <div class="compare-thumb">
            <span class="compare-thumb__name">${p.model}</span>
            <button type="button" class="compare-thumb__remove" data-slug="${p.slug}" title="Remove ${p.model}">&times;</button>
          </div>
        `;
      }).join('');

      itemsEl.querySelectorAll('.compare-thumb__remove').forEach(btn => {
        btn.addEventListener('click', (e) => {
          e.stopPropagation();
          removeFromCompare(btn.dataset.slug);
          syncTray();
        });
      });
    }
  }

  function renderModalContent() {
    const list = getCompareList();
    const products = list.map(slug => getProductBySlug(slug)).filter(Boolean);
    const tableWrap = document.getElementById('compareTableContainer');
    if (!tableWrap || products.length === 0) return;

    tableWrap.innerHTML = `
      <table class="compare-table">
        <thead>
          <tr>
            <th style="width:200px;text-align:left">Appliance Details</th>
            ${products.map(p => `
              <th>
                <div style="width:100px;height:100px;margin:0 auto var(--s-3)">
                  ${renderProductVisual(p, 'compare-modal__img', true)}
                </div>
                <strong>${p.model}</strong>
                <p style="font-size:var(--fs-xs);color:var(--text-3);margin-top:2px">${p.title}</p>
                <p style="font-size:var(--fs-sm);font-weight:700;color:var(--red);margin-top:4px">${formatINR(p.mrp)}</p>
                <div style="display:flex;gap:4px;justify-content:center;margin-top:var(--s-3)">
                  <a href="${basePath}/product.html?cat=${encodeURIComponent(p.cat)}&model=${encodeURIComponent(p.slug)}" class="btn btn--xs">View Page</a>
                  <button type="button" class="btn btn--xs btn--ghost remove-from-modal-btn" data-slug="${p.slug}">Remove</button>
                </div>
              </th>
            `).join('')}
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Category</td>
            ${products.map(p => `<td>${p.cat}</td>`).join('')}
          </tr>
          <tr>
            <td>Power Rating</td>
            ${products.map(p => `<td>${p.wattage ? p.wattage + ' Watts' : 'Standard'}</td>`).join('')}
          </tr>
          <tr>
            <td>Capacity / Size</td>
            ${products.map(p => `<td>${p.capacity || 'Standard Configuration'}</td>`).join('')}
          </tr>
          <tr>
            <td>Heating / Element Type</td>
            ${products.map(p => `<td>${p.elementType || 'High-Grade Component'}</td>`).join('')}
          </tr>
          <tr>
            <td>Chassis Dimensions</td>
            ${products.map(p => `<td>${p.dim ? p.dim + ' mm' : 'Standard'}</td>`).join('')}
          </tr>
          <tr>
            <td>Net Weight</td>
            ${products.map(p => `<td>${p.weight || 'Standard'}</td>`).join('')}
          </tr>
          <tr>
            <td>Warranty Coverage</td>
            ${products.map(p => `<td><strong>${p.warranty || '1 Year Complete'}</strong></td>`).join('')}
          </tr>
          <tr>
            <td>BIS / ISI License</td>
            ${products.map(() => `<td>IS: 2082 CM/L-9600024116</td>`).join('')}
          </tr>
        </tbody>
      </table>
    `;

    tableWrap.querySelectorAll('.remove-from-modal-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        removeFromCompare(btn.dataset.slug);
        syncTray();
        if (getCompareList().length < 2) {
          closeModal();
        } else {
          renderModalContent();
        }
      });
    });
  }

  function openModal() {
    renderModalContent();
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

  document.addEventListener('click', (e) => {
    if (e.target.closest('#openCompareModalBtn')) {
      e.preventDefault();
      openModal();
      return;
    }
    if (e.target.closest('#closeCompareModalBtn') || e.target.closest('#compareBackdrop')) {
      e.preventDefault();
      closeModal();
      return;
    }
    if (e.target.closest('#clearCompareBtn')) {
      e.preventDefault();
      clearCompare();
      syncTray();
    }
  });

  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
  });

  window.addEventListener('storage', syncTray);
  document.addEventListener('maurice:compare-updated', syncTray);

  syncTray();
}
