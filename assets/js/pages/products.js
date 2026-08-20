/**
 * MAURICE APPLIANCES — Products & Catalog Page Controller (Pure Vanilla JS)
 * Supports faceted filters (Category, Capacity, Wattage, Price, Warranty), instant search, sorting, and compare tray.
 */

import { ALL_PRODUCTS, CATEGORIES, getCategory, getProducts } from '../data/products.js?v=3.0';
import { formatINR, renderProductCard, showToast } from '../core/catalog-utils.js?v=3.0';
import { initReveals } from '../core/scroll.js?v=3.0';

let initialized = false;

export function initProductsPage() {
  const grid = document.getElementById('productGrid');
  if (!grid || initialized) return;
  initialized = true;

  const searchInput = document.getElementById('productSearch');
  const sortSelect = document.getElementById('sortSelect');
  const countEl = document.getElementById('resultCount');
  const wordEl = document.getElementById('resultWord');
  const emptyState = document.getElementById('emptyState');
  const activeFiltersEl = document.getElementById('activeFilterPills');

  // Read URL parameters on load
  const urlParams = new URLSearchParams(window.location.search);
  const initialCat = urlParams.get('cat') || '';
  const initialQ = urlParams.get('q') || '';
  const initialSort = urlParams.get('sort') || 'featured';

  const state = {
    cat: initialCat,
    q: initialQ.toLowerCase(),
    sort: initialSort,
    bands: urlParams.getAll('band'),
    warrs: urlParams.getAll('warranty').map(w => parseInt(w, 10)),
    wattages: urlParams.getAll('wattage').map(w => parseInt(w, 10)),
    capacities: urlParams.getAll('capacity')
  };

  if (searchInput && initialQ) searchInput.value = initialQ;
  if (sortSelect && initialSort) sortSelect.value = initialSort;

  function filterAndRender() {
    let list = state.cat ? getProducts(state.cat) : ALL_PRODUCTS;

    list = list.filter(p => {
      // Search query
      if (state.q) {
        const text = `${p.model} ${p.title} ${(p.specs || []).join(' ')} ${p.cat}`.toLowerCase();
        if (!text.includes(state.q)) return false;
      }
      // Price bands
      if (state.bands.length > 0) {
        if (!state.bands.includes(p.priceBand)) return false;
      }
      // Warranties
      if (state.warrs.length > 0) {
        if (!state.warrs.some(w => (p.warrantyYears || 1) >= w)) return false;
      }
      // Wattages
      if (state.wattages.length > 0) {
        if (!p.wattage || !state.wattages.some(w => Math.abs(p.wattage - w) <= 100)) return false;
      }
      // Capacity
      if (state.capacities.length > 0) {
        if (!p.capacity || !state.capacities.includes(p.capacity)) return false;
      }
      return true;
    });

    // Sort
    list.sort((a, b) => {
      switch (state.sort) {
        case 'price-asc': return (a.mrp || 0) - (b.mrp || 0);
        case 'price-desc': return (b.mrp || 0) - (a.mrp || 0);
        case 'name': return (a.title || '').localeCompare(b.title || '');
        case 'warranty': return (b.warrantyYears || 1) - (a.warrantyYears || 1);
        default: return 0;
      }
    });

    // Render Grid
    if (countEl) countEl.textContent = list.length;
    if (wordEl) wordEl.textContent = list.length === 1 ? 'product' : 'products';
    if (emptyState) emptyState.classList.toggle('is-hidden', list.length > 0);
    grid.classList.toggle('is-hidden', list.length === 0);

    const isSubfolder = ['/company/', '/dealers/', '/support/', '/contact/', '/legal/', '/pages/'].some(p => window.location.pathname.includes(p));
    const basePath = isSubfolder ? '..' : '.';
    grid.innerHTML = list.map(p => renderProductCard(p, { basePath, showCompare: true })).join('');

    if (state.cat) {
      const catObj = getCategory(state.cat);
      if (catObj) {
        const titleEl = document.getElementById('categoryPageTitle');
        const leadEl = document.getElementById('categoryPageLead');
        const bcEl = document.getElementById('categoryTitleBreadcrumb');
        if (titleEl) titleEl.textContent = `${catObj.name} Collection`;
        if (leadEl) leadEl.textContent = catObj.blurb || 'Engineered for Indian homes — BIS (ISI) certified for safety and lasting performance.';
        if (bcEl) bcEl.textContent = catObj.name;
      }
    }

    renderActivePills();
    updateURL();

    // Trigger reveal class
    setTimeout(() => {
      grid.querySelectorAll('.pcard').forEach(c => c.classList.add('in-view'));
      initReveals();
    }, 20);
  }

  function renderActivePills() {
    if (!activeFiltersEl) return;
    const pills = [];

    if (state.cat) {
      const c = getCategory(state.cat);
      pills.push({ label: `Category: ${c ? c.name : state.cat}`, clear: () => { state.cat = ''; syncCategoryChips(); } });
    }
    if (state.q) {
      pills.push({ label: `Search: "${state.q}"`, clear: () => { state.q = ''; if (searchInput) searchInput.value = ''; } });
    }
    state.bands.forEach(b => {
      const labels = { 'under-1500': 'Under ₹1,500', '1500-3000': '₹1,500 – ₹3,000', '3000-6000': '₹3,000 – ₹6,000', '6000-plus': '₹6,000 & Above' };
      pills.push({ label: labels[b] || b, clear: () => { state.bands = state.bands.filter(x => x !== b); syncCheckboxes(); } });
    });
    state.warrs.forEach(w => {
      pills.push({ label: `${w}+ Yr Warranty`, clear: () => { state.warrs = state.warrs.filter(x => x !== w); syncCheckboxes(); } });
    });

    if (pills.length === 0) {
      activeFiltersEl.innerHTML = '';
      activeFiltersEl.classList.add('is-hidden');
      return;
    }

    activeFiltersEl.classList.remove('is-hidden');
    activeFiltersEl.innerHTML = `
      <span class="active-pills__label">Active Filters:</span>
      ${pills.map((p, idx) => `
        <button type="button" class="active-pill" data-idx="${idx}">
          <span>${p.label}</span>
          <span class="active-pill__x">&times;</span>
        </button>
      `).join('')}
      <button type="button" class="btn btn--xs btn--ghost" id="clearAllPillsBtn">Clear All</button>
    `;

    activeFiltersEl.querySelectorAll('.active-pill').forEach(pillBtn => {
      pillBtn.addEventListener('click', () => {
        const idx = parseInt(pillBtn.dataset.idx, 10);
        if (pills[idx]) {
          pills[idx].clear();
          filterAndRender();
        }
      });
    });

    document.getElementById('clearAllPillsBtn')?.addEventListener('click', () => {
      clearAllFilters();
    });
  }

  function syncCategoryChips() {
    document.querySelectorAll('.pchip').forEach(chip => {
      const chipCat = chip.dataset.cat || '';
      chip.classList.toggle('active', chipCat === state.cat);
    });
  }

  function syncCheckboxes() {
    document.querySelectorAll('input[data-filter="band"]').forEach(cb => {
      cb.checked = state.bands.includes(cb.value);
    });
    document.querySelectorAll('input[data-filter="warranty"]').forEach(cb => {
      cb.checked = state.warrs.includes(parseInt(cb.value, 10));
    });
    document.querySelectorAll('input[data-filter="wattage"]').forEach(cb => {
      cb.checked = state.wattages.includes(parseInt(cb.value, 10));
    });
  }

  function clearAllFilters() {
    state.q = '';
    state.bands = [];
    state.warrs = [];
    state.wattages = [];
    state.capacities = [];
    if (searchInput) searchInput.value = '';
    syncCheckboxes();
    filterAndRender();
  }

  function updateURL() {
    const params = new URLSearchParams();
    if (state.cat) params.set('cat', state.cat);
    if (state.q) params.set('q', state.q);
    if (state.sort !== 'featured') params.set('sort', state.sort);
    state.bands.forEach(b => params.append('band', b));
    state.warrs.forEach(w => params.append('warranty', String(w)));
    state.wattages.forEach(w => params.append('wattage', String(w)));

    const qs = params.toString();
    history.replaceState(null, '', qs ? `?${qs}` : window.location.pathname);
  }

  // Category chip clicks
  document.querySelectorAll('.pchip').forEach(chip => {
    chip.addEventListener('click', (e) => {
      e.preventDefault();
      state.cat = chip.dataset.cat || '';
      syncCategoryChips();
      filterAndRender();
    });
  });

  // Filter Checkbox Listeners
  document.querySelectorAll('input[data-filter]').forEach(cb => {
    cb.addEventListener('change', () => {
      const type = cb.dataset.filter;
      if (type === 'band') {
        state.bands = Array.from(document.querySelectorAll('input[data-filter="band"]:checked')).map(c => c.value);
      } else if (type === 'warranty') {
        state.warrs = Array.from(document.querySelectorAll('input[data-filter="warranty"]:checked')).map(c => parseInt(c.value, 10));
      } else if (type === 'wattage') {
        state.wattages = Array.from(document.querySelectorAll('input[data-filter="wattage"]:checked')).map(c => parseInt(c.value, 10));
      }
      filterAndRender();
    });
  });

  // Search input with debounce
  let searchTimer;
  searchInput?.addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
      state.q = searchInput.value.trim().toLowerCase();
      filterAndRender();
    }, 160);
  });

  // Sort dropdown
  sortSelect?.addEventListener('change', () => {
    state.sort = sortSelect.value;
    filterAndRender();
  });

  // Reset empty button
  document.getElementById('resetEmpty')?.addEventListener('click', clearAllFilters);
  document.getElementById('clearFilters')?.addEventListener('click', clearAllFilters);

  // Mobile Filter Drawer Toggle
  const filtersEl = document.getElementById('filters');
  document.getElementById('openFilters')?.addEventListener('click', () => {
    filtersEl?.classList.add('open');
    document.body.style.overflow = 'hidden';
  });
  const closeFilterDrawer = () => {
    filtersEl?.classList.remove('open');
    document.body.style.overflow = '';
  };
  document.getElementById('closeFilters')?.addEventListener('click', closeFilterDrawer);

  syncCheckboxes();
  syncCategoryChips();
  filterAndRender();
}

if (document.readyState !== 'loading') {
  initProductsPage();
} else {
  document.addEventListener('DOMContentLoaded', initProductsPage);
}
document.addEventListener('maurice:ready', initProductsPage);
