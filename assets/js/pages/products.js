/* Products page — instant client-side filter / search / sort layered over the
   server-rendered grid (works without JS; enhances when present).
   Plus compare tray + modal. */

document.addEventListener('maurice:ready', () => {
  const grid = document.getElementById('productGrid');
  if (!grid) return;

  const cards = Array.from(grid.querySelectorAll('.pcard'));
  const searchInput = document.getElementById('productSearch');
  const sortSelect = document.getElementById('sortSelect');
  const countEl = document.getElementById('resultCount');
  const wordEl = document.getElementById('resultWord');
  const emptyState = document.getElementById('emptyState');
  const filterForm = document.getElementById('filterForm');
  const bandChecks = Array.from(document.querySelectorAll('input[data-filter="band"]'));
  const warrChecks = Array.from(document.querySelectorAll('input[data-filter="warranty"]'));

  const state = { q: (searchInput?.value || '').toLowerCase(), sort: sortSelect?.value || 'featured', bands: [], warrs: [] };
  syncFiltersFromDOM();

  function syncFiltersFromDOM() {
    state.bands = bandChecks.filter(c => c.checked).map(c => c.value);
    state.warrs = warrChecks.filter(c => c.checked).map(c => parseInt(c.value, 10));
  }

  function apply() {
    let visible = 0;
    cards.forEach((card) => {
      const price = parseInt(card.dataset.price, 10) || 0;
      const band = card.dataset.band;
      const warranty = parseInt(card.dataset.warranty, 10) || 0;
      const name = card.dataset.name || '';

      let show = true;
      if (state.q && !name.includes(state.q)) show = false;
      if (state.bands.length && !state.bands.includes(band)) show = false;
      if (state.warrs.length && !state.warrs.some(w => warranty >= w)) show = false;

      card.classList.toggle('is-hidden', !show);
      if (show) visible++;
    });

    // Sort visible cards
    const vis = cards.filter(c => !c.classList.contains('is-hidden'));
    vis.sort((a, b) => {
      switch (state.sort) {
        case 'price-asc': return (+a.dataset.price) - (+b.dataset.price);
        case 'price-desc': return (+b.dataset.price) - (+a.dataset.price);
        case 'name': return (a.dataset.title || '').localeCompare(b.dataset.title || '');
        default: return 0;
      }
    });
    if (state.sort !== 'featured') vis.forEach(c => grid.appendChild(c));

    if (countEl) countEl.textContent = String(visible);
    if (wordEl) wordEl.textContent = visible === 1 ? 'product' : 'products';
    if (emptyState) emptyState.classList.toggle('is-hidden', visible !== 0);
    grid.classList.toggle('is-hidden', visible === 0);

    updateURL();
    // Re-trigger reveal on newly shown cards
    vis.forEach(c => c.classList.add('in-view'));
  }

  function updateURL() {
    const params = new URLSearchParams(window.location.search);
    state.q ? params.set('q', state.q) : params.delete('q');
    state.sort !== 'featured' ? params.set('sort', state.sort) : params.delete('sort');
    params.delete('band'); state.bands.forEach(b => params.append('band', b));
    params.delete('warranty'); state.warrs.forEach(w => params.append('warranty', String(w)));
    const qs = params.toString();
    history.replaceState(null, '', qs ? `?${qs}` : window.location.pathname);
  }

  // Debounced search
  let t;
  searchInput?.addEventListener('input', () => {
    clearTimeout(t);
    t = setTimeout(() => { state.q = searchInput.value.trim().toLowerCase(); apply(); }, 160);
  });
  sortSelect?.addEventListener('change', () => { state.sort = sortSelect.value; apply(); });
  [...bandChecks, ...warrChecks].forEach(c =>
    c.addEventListener('change', () => { syncFiltersFromDOM(); apply(); }));

  // Clear filters
  function clearAll() {
    bandChecks.forEach(c => c.checked = false);
    warrChecks.forEach(c => c.checked = false);
    if (searchInput) searchInput.value = '';
    state.q = ''; syncFiltersFromDOM(); apply();
  }
  document.getElementById('clearFilters')?.addEventListener('click', clearAll);
  document.getElementById('resetEmpty')?.addEventListener('click', clearAll);

  // Mobile filter drawer
  const filters = document.getElementById('filters');
  document.getElementById('openFilters')?.addEventListener('click', () => {
    filters.classList.add('open'); document.body.style.overflow = 'hidden';
  });
  const closeFilters = () => { filters.classList.remove('open'); document.body.style.overflow = ''; };
  document.getElementById('closeFilters')?.addEventListener('click', closeFilters);

  // Prevent compare-label clicks from triggering card navigation
  document.querySelectorAll('[data-no-nav]').forEach(el => {
    el.addEventListener('click', (e) => e.stopPropagation());
  });

  initCompare();
  apply();
});

/* ---------------- Compare ---------------- */
function initCompare() {
  const dataEl = document.getElementById('compareData');
  const data = dataEl ? JSON.parse(dataEl.textContent || '{}') : {};
  const tray = document.getElementById('compareTray');
  const trayItems = document.getElementById('compareItems');
  const trayCount = document.getElementById('compareCount');
  const modal = document.getElementById('compareModal');
  const wrap = document.getElementById('compareTableWrap');
  const MAX = 4;
  let selected = [];

  const checks = Array.from(document.querySelectorAll('.compareCheck'));

  function refresh() {
    if (trayCount) trayCount.textContent = String(selected.length);
    if (trayItems) {
      trayItems.innerHTML = selected.map(uid => {
        const p = data[uid]; if (!p) return '';
        return `<span class="ctray__chip">${escapeHtml(p.title)}<button type="button" data-remove="${escapeHtml(uid)}" aria-label="Remove">
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M3 3l6 6M9 3l-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        </button></span>`;
      }).join('');
    }
    tray?.classList.toggle('show', selected.length > 0);
    trayItems?.querySelectorAll('[data-remove]').forEach(btn => {
      btn.addEventListener('click', () => remove(btn.dataset.remove));
    });
  }

  function remove(uid) {
    selected = selected.filter(u => u !== uid);
    const chk = checks.find(c => c.value === uid);
    if (chk) chk.checked = false;
    refresh();
  }

  checks.forEach(chk => {
    chk.addEventListener('change', () => {
      if (chk.checked) {
        if (selected.length >= MAX) { chk.checked = false; flash(chk); return; }
        selected.push(chk.value);
      } else {
        selected = selected.filter(u => u !== chk.value);
      }
      refresh();
    });
  });

  document.getElementById('compareClear')?.addEventListener('click', () => {
    selected = []; checks.forEach(c => c.checked = false); refresh();
  });

  document.getElementById('compareOpen')?.addEventListener('click', () => {
    if (selected.length < 2) { alert('Select at least 2 products to compare.'); return; }
    buildTable();
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
  });

  function buildTable() {
    const items = selected.map(uid => data[uid]).filter(Boolean);
    const maxSpecs = Math.max(...items.map(p => p.specs.length));
    const rows = [];
    rows.push(row('Category', items.map(p => escapeHtml(p.cat))));
    rows.push(row('Price', items.map(p => `<span class="price">${escapeHtml(p.mrpFmt)}</span>`)));
    rows.push(row('Warranty', items.map(p => escapeHtml(p.warranty || '—'))));
    rows.push(row('Dimensions (mm)', items.map(p => escapeHtml(p.dim || '—'))));
    rows.push(row('Weight', items.map(p => escapeHtml(p.weight || '—'))));
    rows.push(row('Min. order', items.map(p => escapeHtml(p.moq || '—'))));
    for (let i = 0; i < maxSpecs; i++) {
      rows.push(row(i === 0 ? 'Features' : '', items.map(p => escapeHtml(p.specs[i] || '—'))));
    }
    const head = '<th></th>' + items.map(p =>
      `<th><span class="pcard__model" style="color:var(--red)">${escapeHtml(p.model)}</span><br>${escapeHtml(p.title)}<br><a href="${p.url}" style="color:var(--red);font-size:var(--fs-cap);font-weight:600">View →</a></th>`).join('');
    wrap.innerHTML = `<table class="ctable"><thead><tr>${head}</tr></thead><tbody>${rows.join('')}</tbody></table>`;
  }
  function row(label, cells) {
    return `<tr><td class="rowlabel">${label}</td>${cells.map(c => `<td>${c}</td>`).join('')}</tr>`;
  }

  modal?.querySelectorAll('[data-close]').forEach(el =>
    el.addEventListener('click', () => { modal.classList.remove('open'); document.body.style.overflow = ''; }));
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal?.classList.contains('open')) {
      modal.classList.remove('open'); document.body.style.overflow = '';
    }
  });

  function flash(el) {
    const chip = el.closest('.pcard__compare');
    if (!chip) return;
    chip.style.transition = 'color .2s'; chip.style.color = 'var(--red)';
    setTimeout(() => { chip.style.color = ''; }, 600);
  }
  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
  }
}
