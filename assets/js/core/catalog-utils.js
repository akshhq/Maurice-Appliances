/**
 * MAURICE APPLIANCES — Catalog, UI & Rendering Utilities
 * Universal helpers for pure client-side HTML/JS execution.
 */

import { ALL_PRODUCTS, PRODUCTS_BY_CAT, getProductBySlug, getCategory } from '../data/products.js';

export function formatINR(num) {
  if (num === null || num === undefined || isNaN(num)) return '₹0';
  return '₹' + Number(num).toLocaleString('en-IN');
}

export function getCategoryIconSvg(slug, stroke = 1.6) {
  const icons = {
    'room-heaters': '<path d="M7 3v6M12 3v6M17 3v6"/><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 15h8"/>',
    'water-heaters': '<rect x="6" y="3" width="12" height="14" rx="6"/><path d="M9 21h2M13 21h2"/><circle cx="12" cy="10" r="2"/>',
    'fans': '<circle cx="12" cy="12" r="2"/><path d="M12 10c0-4 1-7 3-7s2 4-1 6M14 12c4 0 7 1 7 3s-4 2-6-1M12 14c0 4-1 7-3 7s-2-4 1-6M10 12c-4 0-7-1-7-3s4-2 6 1"/>',
    'mixer-grinders': '<path d="M9 3h6l-1 5h-4z"/><rect x="8" y="8" width="8" height="8" rx="1"/><path d="M10 16v3h4v-3"/>',
    'gas-stoves': '<rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="8" cy="12" r="2"/><circle cx="16" cy="12" r="2"/>',
    'induction': '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>',
    'irons': '<path d="M4 15c0-4 4-7 9-7h7l-2 7z"/><path d="M4 15h13"/><circle cx="9" cy="11" r=".6"/>',
    'chimneys': '<path d="M4 20V10l8-5 8 5v10"/><path d="M8 20v-6h8v6"/>',
    'kitchen-appliances': '<rect x="5" y="4" width="14" height="16" rx="2"/><path d="M9 8h6M9 12h6"/>',
    'madhani': '<rect x="8" y="3" width="8" height="7" rx="1"/><path d="M12 10v9M9 19h6M10 13h4"/>',
    'coolers-ac': '<rect x="3" y="5" width="18" height="9" rx="2"/><path d="M7 18v1.4M11 18v2M15 18v1.4M19 18v2"/><path d="M6 9.5h.01M9.5 9.5h5"/>'
  };
  const path = icons[slug] || '<circle cx="12" cy="12" r="8"/>';
  return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="${stroke}" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${path}</svg>`;
}

export function getProductFrameSvg(cat = 'room-heaters', extraClass = '') {
  const frames = {
    'room-heaters': `<rect x="34" y="20" width="52" height="90" rx="8" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><rect x="44" y="34" width="32" height="58" rx="4" fill="none" stroke="var(--red)" stroke-width="2"/><line x1="52" y1="34" x2="52" y2="92" stroke="var(--red)" stroke-width="2"/><line x1="60" y1="34" x2="60" y2="92" stroke="var(--red)" stroke-width="2"/><line x1="68" y1="34" x2="68" y2="92" stroke="var(--red)" stroke-width="2"/><rect x="40" y="110" width="40" height="8" rx="3" fill="var(--ink)"/>`,
    'water-heaters': `<rect x="30" y="24" width="60" height="72" rx="26" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><circle cx="60" cy="58" r="9" fill="none" stroke="var(--red)" stroke-width="2.5"/><path d="M60 53v10" stroke="var(--red)" stroke-width="2"/><path d="M48 100l-3 8M72 100l3 8" stroke="#3b82f6" stroke-width="3" stroke-linecap="round"/><path d="M50 100h20" stroke="var(--ink)" stroke-width="2"/>`,
    'fans': `<circle cx="60" cy="52" r="34" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><circle cx="60" cy="52" r="6" fill="var(--red)"/><path d="M60 46c0-14 4-22 10-22s6 12-4 18" fill="none" stroke="var(--ink)" stroke-width="2"/><path d="M66 52c14 0 22 4 22 10s-12 6-18-4" fill="none" stroke="var(--ink)" stroke-width="2"/><path d="M60 58c0 14-4 22-10 22s-6-12 4-18" fill="none" stroke="var(--ink)" stroke-width="2"/><path d="M54 52c-14 0-22-4-22-10s12-6 18 4" fill="none" stroke="var(--ink)" stroke-width="2"/><path d="M60 86v22M48 108h24" stroke="var(--ink)" stroke-width="2.5"/>`,
    'mixer-grinders': `<path d="M46 22h28l-4 22H50z" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><path d="M52 22l3-8h10l3 8" fill="none" stroke="var(--red)" stroke-width="2"/><rect x="42" y="46" width="36" height="44" rx="6" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><circle cx="60" cy="70" r="7" fill="none" stroke="var(--red)" stroke-width="2.5"/><rect x="46" y="90" width="28" height="12" rx="3" fill="var(--ink)"/>`,
    'gas-stoves': `<rect x="18" y="46" width="84" height="36" rx="6" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><circle cx="42" cy="64" r="9" fill="none" stroke="var(--red)" stroke-width="2.5"/><circle cx="78" cy="64" r="9" fill="none" stroke="var(--red)" stroke-width="2.5"/><circle cx="42" cy="64" r="3" fill="var(--red)"/><circle cx="78" cy="64" r="3" fill="var(--red)"/><circle cx="30" cy="88" r="3" fill="var(--ink)"/><circle cx="60" cy="88" r="3" fill="var(--ink)"/><circle cx="90" cy="88" r="3" fill="var(--ink)"/>`,
    'induction': `<rect x="20" y="40" width="80" height="48" rx="8" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><circle cx="60" cy="60" r="18" fill="none" stroke="var(--red)" stroke-width="2.5"/><circle cx="60" cy="60" r="10" fill="none" stroke="var(--red)" stroke-width="1.5" opacity=".6"/><rect x="34" y="80" width="8" height="3" rx="1.5" fill="var(--ink)"/><rect x="46" y="80" width="8" height="3" rx="1.5" fill="var(--ink)"/><rect x="58" y="80" width="8" height="3" rx="1.5" fill="var(--red)"/>`,
    'irons': `<path d="M22 74c0-20 22-34 46-34h30l-8 34z" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><path d="M22 74h68" stroke="var(--ink)" stroke-width="2.5"/><path d="M60 40c6-10 18-12 26-6" fill="none" stroke="var(--red)" stroke-width="2.5" stroke-linecap="round"/><circle cx="44" cy="58" r="3" fill="var(--red)"/>`,
    'chimneys': `<path d="M30 96V60l30-22 30 22v36" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><path d="M44 96V74h32v22" fill="none" stroke="var(--red)" stroke-width="2"/><rect x="54" y="20" width="12" height="20" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><line x1="50" y1="84" x2="70" y2="84" stroke="var(--red)" stroke-width="2"/>`,
    'kitchen-appliances': `<rect x="34" y="24" width="52" height="80" rx="8" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><path d="M46 40h28M46 54h28" stroke="var(--red)" stroke-width="2"/><rect x="46" y="70" width="28" height="20" rx="3" fill="none" stroke="var(--ink)" stroke-width="2"/><circle cx="60" cy="80" r="4" fill="var(--red)"/>`,
    'madhani': `<rect x="44" y="20" width="32" height="30" rx="4" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><rect x="50" y="14" width="20" height="8" rx="4" fill="var(--red)"/><path d="M60 50v42" stroke="var(--ink)" stroke-width="2.5"/><path d="M50 92h20M52 84h16" stroke="var(--ink)" stroke-width="2.5" stroke-linecap="round"/><ellipse cx="60" cy="60" rx="18" ry="4" fill="none" stroke="var(--ink)" stroke-width="2"/>`,
    'coolers-ac': `<rect x="20" y="34" width="80" height="38" rx="8" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/><path d="M34 78v6M50 78v9M66 78v6M82 78v9" stroke="var(--red)" stroke-width="2.5" stroke-linecap="round"/><circle cx="34" cy="50" r="2.5" fill="var(--red)"/><path d="M46 50h30" stroke="var(--ink)" stroke-width="2"/><path d="M46 58h20" stroke="var(--ink)" stroke-width="2" opacity=".6"/>`
  };
  const inner = frames[cat] || `<circle cx="60" cy="60" r="34" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/>`;
  return `<svg class="pframe ${extraClass}" viewBox="0 0 120 128" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">${inner}</svg>`;
}

export function renderProductVisual(product, extraClass = '', isDecorative = false, basePath = '') {
  if (!product) return getProductFrameSvg('room-heaters', extraClass);
  if (product.image) {
    const fullSrc = basePath ? `${basePath}/${product.image}` : product.image;
    const alt = isDecorative ? '' : `${product.model} ${product.title}`;
    return `<img class="pframe ${extraClass}" src="${fullSrc}" alt="${alt}" loading="lazy" decoding="async" ${isDecorative ? 'aria-hidden="true"' : ''} onerror="this.onerror=null;this.classList.add('pframe--fallback')">`;
  }
  return getProductFrameSvg(product.cat, extraClass);
}

export function renderProductCard(p, options = {}) {
  const { basePath = '', showCompare = true } = options;
  const productUrl = `${basePath ? basePath + '/' : ''}product.html?cat=${encodeURIComponent(p.cat)}&model=${encodeURIComponent(p.slug)}`;
  const specPills = (p.specs || []).slice(0, 3).map(s => `<span class="pcard__spec-pill">${s}</span>`).join('');
  const compareChecked = getCompareList().includes(p.slug) ? 'checked' : '';

  return `
    <article class="pcard reveal" data-name="${(p.model + ' ' + p.title + ' ' + (p.specs || []).join(' ')).toLowerCase()}" data-price="${p.mrp || 0}" data-band="${p.priceBand}" data-warranty="${p.warrantyYears}" data-wattage="${p.wattage || 0}" data-title="${p.title}" data-cat="${p.cat}" data-slug="${p.slug}">
      <div class="pcard__stage">
        <div class="pcard__badges">
          <span class="badge badge--red">ISI Certified</span>
          ${p.warranty ? `<span class="badge badge--ember">${p.warranty}</span>` : ''}
          ${p.mrp >= 8000 ? `<span class="badge">Best Seller</span>` : ''}
        </div>
        ${showCompare ? `
        <label class="pcard__compare" data-no-nav="true" title="Add to Compare">
          <input type="checkbox" class="compare-checkbox" data-slug="${p.slug}" ${compareChecked} aria-label="Compare ${p.model}">
          <span>Compare</span>
        </label>
        ` : ''}
        <a href="${productUrl}" class="pcard__img-wrap" data-cursor="View">
          ${renderProductVisual(p, 'pcard__image', false, basePath)}
        </a>
      </div>
      <div class="pcard__body">
        <p class="pcard__model">${p.model}</p>
        <h3 class="pcard__title"><a href="${productUrl}">${p.title}</a></h3>
        <div class="pcard__spec-pills">
          ${specPills}
        </div>
        <div class="pcard__foot">
          <div class="pcard__price-wrap">
            <span class="pcard__mrp">${formatINR(p.mrp)}</span>
            <span class="pcard__tax-note">MRP (incl. taxes)</span>
          </div>
          <div class="pcard__actions">
            <button type="button" class="btn btn--sm btn--ghost open-inquiry-btn" data-model="${p.model} - ${p.title}" data-mrp="${p.mrp}" data-cursor="Inquire">Inquire</button>
            <a href="${productUrl}" class="btn btn--sm" data-cursor="Specs">Specs</a>
          </div>
        </div>
      </div>
    </article>
  `;
}

/* ---------------- Comparison Storage & Manager ---------------- */
const COMPARE_KEY = 'maurice_compare_skus';

export function getCompareList() {
  try {
    const raw = localStorage.getItem(COMPARE_KEY);
    return raw ? JSON.parse(raw) : [];
  } catch (e) {
    return [];
  }
}

export function addToCompare(slug) {
  const list = getCompareList();
  if (list.includes(slug)) return list;
  if (list.length >= 4) {
    showToast('You can compare a maximum of 4 products at a time.', 'warning');
    return list;
  }
  list.push(slug);
  try { localStorage.setItem(COMPARE_KEY, JSON.stringify(list)); } catch (e) {}
  dispatchCompareUpdate();
  showToast('Product added to comparison drawer.', 'success');
  return list;
}

export function removeFromCompare(slug) {
  let list = getCompareList();
  list = list.filter(s => s !== slug);
  try { localStorage.setItem(COMPARE_KEY, JSON.stringify(list)); } catch (e) {}
  dispatchCompareUpdate();
  return list;
}

export function clearCompare() {
  try { localStorage.removeItem(COMPARE_KEY); } catch (e) {}
  dispatchCompareUpdate();
}

function dispatchCompareUpdate() {
  document.dispatchEvent(new CustomEvent('maurice:compare-updated', {
    detail: { list: getCompareList() }
  }));
}

/* ---------------- Toast Notification System ---------------- */
export function showToast(message, type = 'info') {
  let container = document.getElementById('mauriceToastContainer');
  if (!container) {
    container = document.createElement('div');
    container.id = 'mauriceToastContainer';
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `toast toast--${type}`;
  toast.innerHTML = `
    <div class="toast__content">
      <span class="toast__dot"></span>
      <p class="toast__msg">${message}</p>
    </div>
    <button type="button" class="toast__close" aria-label="Close notification">&times;</button>
  `;

  container.appendChild(toast);
  setTimeout(() => toast.classList.add('is-visible'), 10);

  const remove = () => {
    toast.classList.remove('is-visible');
    setTimeout(() => toast.remove(), 300);
  };

  toast.querySelector('.toast__close').addEventListener('click', remove);
  setTimeout(remove, 4500);
}
