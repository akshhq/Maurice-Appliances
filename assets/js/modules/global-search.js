/**
 * MAURICE APPLIANCES — Global Instant Search Command Palette (CTRL/CMD + K)
 * Fast live search across all 118+ SKUs with thumbnails, pricing, specs, and direct navigation.
 */

import { ALL_PRODUCTS } from '../data/products.js?v=3.0';
import { formatINR } from '../core/catalog-utils.js?v=3.0';

const CATEGORY_KEYWORDS = {
  'water-heaters': ['water heater', 'water heaters', 'geyser', 'geysers', 'gyser', 'water heating', 'storage', 'instant', 'hot water'],
  'room-heaters': ['room heater', 'room heaters', 'heater', 'heaters', 'blower', 'blowers', 'quartz', 'carbon', 'heat pillar', 'rado', 'radiator', 'heating'],
  'fans': ['fan', 'fans', 'ceiling fan', 'table fan', 'pedestal fan', 'wall fan', 'exhaust', 'ventilation', 'farratta'],
  'mixer-grinders': ['mixer', 'grinder', 'mixie', 'juicer', 'jmg', 'blender'],
  'gas-stoves': ['gas stove', 'gas stoves', 'stove', 'stoves', 'cooktop', 'cooktops', 'chulha', 'burner', 'glass top'],
  'induction': ['induction', 'infrared', 'induction cooker', 'cooker', 'electric stove'],
  'irons': ['iron', 'irons', 'press', 'dry iron', 'steam iron', 'heavy weight'],
  'chimneys': ['chimney', 'chimneys', 'hood', 'kitchen hood', 'motion sensor', 'auto clean'],
  'kitchen-appliances': ['kettle', 'kettles', 'toaster', 'toasters', 'air fryer', 'otg', 'oven', 'atta chakki'],
  'madhani': ['madhani', 'churner', 'lassi', 'milk churner', 'valona'],
  'coolers-ac': ['cooler', 'coolers', 'air cooler', 'ac', 'air conditioner']
};

export function initGlobalSearch(basePath = '') {
  let modal = document.getElementById('globalSearchModal');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'globalSearchModal';
    modal.className = 'search-modal';
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    modal.innerHTML = `
      <div class="search-modal__backdrop" id="searchBackdrop"></div>
      <div class="search-modal__dialog" role="dialog" aria-modal="true" aria-label="Search Appliances">
        <div class="search-modal__input-wrap">
          <svg class="search-modal__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
          </svg>
          <input type="search" class="search-modal__input" id="globalSearchInput" placeholder="Search 118+ appliances (e.g. Geyser, Lava, 25L, Quartz, Glass Top)..." autocomplete="off" autocorrect="off" spellcheck="false">
          <kbd class="search-modal__kbd">ESC</kbd>
        </div>

        <div class="search-modal__quick-filters" id="searchQuickFilters">
          <button type="button" class="search-modal__tag is-active" data-search-tag="all">All</button>
          <button type="button" class="search-modal__tag" data-search-tag="water-heaters">Water Heaters</button>
          <button type="button" class="search-modal__tag" data-search-tag="room-heaters">Room Heaters</button>
          <button type="button" class="search-modal__tag" data-search-tag="fans">Fans</button>
          <button type="button" class="search-modal__tag" data-search-tag="gas-stoves">Gas Stoves</button>
          <button type="button" class="search-modal__tag" data-search-tag="induction">Induction</button>
          <button type="button" class="search-modal__tag" data-search-tag="chimneys">Chimneys</button>
        </div>

        <div class="search-modal__results" id="globalSearchResults" role="listbox">
          <!-- Live search items injected here -->
        </div>

        <div class="search-modal__footer">
          <span class="search-modal__tip"><kbd>↑</kbd><kbd>↓</kbd> to navigate</span>
          <span class="search-modal__tip"><kbd>ENTER</kbd> to select</span>
          <span class="search-modal__tip"><kbd>ESC</kbd> to dismiss</span>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
  }

  const input = document.getElementById('globalSearchInput');
  const resultsContainer = document.getElementById('globalSearchResults');
  const backdrop = document.getElementById('searchBackdrop');
  const tags = modal.querySelectorAll('[data-search-tag]');

  let activeIndex = -1;
  let currentFilter = 'all';

  function openSearch() {
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
      input.focus();
      renderResults(input.value.trim());
    }, 50);
  }

  function closeSearch() {
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    activeIndex = -1;
  }

  function highlightMatches(text, query) {
    if (!query || !text) return text || '';
    const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return String(text).replace(regex, '<mark>$1</mark>');
  }

  function renderResults(query = '') {
    let filtered = ALL_PRODUCTS || [];

    if (currentFilter !== 'all') {
      filtered = filtered.filter(p => (p.cat === currentFilter || p.category === currentFilter));
    }

    if (query) {
      const q = query.trim().toLowerCase();
      filtered = filtered.filter(p => {
        if (!p) return false;
        const model = (p.model || '').toLowerCase();
        const title = (p.title || '').toLowerCase();
        const cat = (p.cat || p.category || '').toLowerCase();
        const cap = (p.capacity || '').toLowerCase();
        const wat = p.wattage ? String(p.wattage).toLowerCase() : '';
        const specs = Array.isArray(p.specs) ? p.specs.join(' ').toLowerCase() : '';
        const synonyms = CATEGORY_KEYWORDS[p.cat || p.category] || [];

        if (model.includes(q) || title.includes(q) || cat.includes(q) || cap.includes(q) || wat.includes(q) || specs.includes(q)) {
          return true;
        }

        if (synonyms.some(s => s.includes(q) || q.includes(s))) {
          return true;
        }

        return false;
      });
    }

    // Limit to top 15 results for optimal performance & scanning
    const topResults = filtered.slice(0, 15);

    if (topResults.length === 0) {
      resultsContainer.innerHTML = `
        <div class="search-modal__empty">
          <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          <p>No appliances found matching <strong>"${query}"</strong></p>
          <a href="${basePath ? basePath + '/' : ''}products.html" class="btn btn--sm btn--ghost" style="margin-top:12px">View Full 118+ Product Catalog &rarr;</a>
        </div>
      `;
      activeIndex = -1;
      return;
    }

    const itemsHtml = topResults.map((p, idx) => {
      const cat = p.category || p.cat || '';
      const pUrl = `${basePath ? basePath + '/' : ''}product.html?cat=${encodeURIComponent(cat)}&model=${encodeURIComponent(p.slug)}`;
      const catLabel = cat ? cat.replace(/-/g, ' ').toUpperCase() : 'APPLIANCE';
      const mrpFormatted = formatINR(p.mrp);
      const isSelected = idx === activeIndex;
      const imgPath = p.image || (p.images && p.images[0]) || '';
      const fullImgSrc = imgPath ? (basePath ? `${basePath}/${imgPath}` : imgPath) : '';

      // Extract high-value specs
      const specSnippet = (p.wattage ? `${p.wattage}W` : '') || (p.capacity ? `${p.capacity}` : '') || (Array.isArray(p.specs) && p.specs[0]) || '';

      return `
        <a href="${pUrl}" class="search-result-item ${isSelected ? 'is-selected' : ''}" role="option" aria-selected="${isSelected}" data-index="${idx}">
          <div class="search-result-item__visual">
            ${fullImgSrc ? `<img src="${fullImgSrc}" alt="${p.model}" loading="lazy">` : `<span class="search-result-item__fallback">${catLabel.slice(0, 2)}</span>`}
          </div>
          <div class="search-result-item__info">
            <div class="search-result-item__top">
              <span class="search-result-item__cat">${catLabel}</span>
              <span class="badge badge--red" style="font-size:0.65rem;padding:1px 6px">ISI</span>
            </div>
            <h4 class="search-result-item__title">${highlightMatches(p.model + ' ' + (p.title || ''), query)}</h4>
            ${specSnippet ? `<p class="search-result-item__spec">${specSnippet}</p>` : ''}
          </div>
          <div class="search-result-item__price">
            <span class="search-result-item__mrp">${mrpFormatted}</span>
            <span class="search-result-item__arrow">&rarr;</span>
          </div>
        </a>
      `;
    }).join('');

    resultsContainer.innerHTML = itemsHtml;

    // Add click listeners to items
    resultsContainer.querySelectorAll('.search-result-item').forEach(item => {
      item.addEventListener('mouseenter', () => {
        activeIndex = parseInt(item.dataset.index, 10);
        updateSelection();
      });
    });
  }

  function updateSelection() {
    const items = resultsContainer.querySelectorAll('.search-result-item');
    items.forEach((item, idx) => {
      if (idx === activeIndex) {
        item.classList.add('is-selected');
        item.setAttribute('aria-selected', 'true');
        item.scrollIntoView({ block: 'nearest' });
      } else {
        item.classList.remove('is-selected');
        item.setAttribute('aria-selected', 'false');
      }
    });
  }

  // Keyboard navigation
  input.addEventListener('keydown', (e) => {
    const items = resultsContainer.querySelectorAll('.search-result-item');
    if (items.length === 0) return;

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      activeIndex = (activeIndex + 1) % items.length;
      updateSelection();
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeIndex = (activeIndex - 1 + items.length) % items.length;
      updateSelection();
    } else if (e.key === 'Enter') {
      e.preventDefault();
      if (activeIndex >= 0 && items[activeIndex]) {
        items[activeIndex].click();
      } else if (items[0]) {
        items[0].click();
      }
    }
  });

  // Debounced input handler
  let debounceTimer;
  input.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
      activeIndex = -1;
      renderResults(input.value.trim());
    }, 80);
  });

  // Category filter tags
  tags.forEach(tag => {
    tag.addEventListener('click', () => {
      tags.forEach(t => t.classList.remove('is-active'));
      tag.classList.add('is-active');
      currentFilter = tag.dataset.searchTag;
      activeIndex = -1;
      renderResults(input.value.trim());
    });
  });

  // Backdrop and escape to close
  backdrop.addEventListener('click', closeSearch);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.style.display !== 'none') {
      closeSearch();
    }
    // Global hotkey: CTRL+K or CMD+K or '/'
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      if (modal.style.display === 'none') {
        openSearch();
      } else {
        closeSearch();
      }
    }
    if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
      e.preventDefault();
      openSearch();
    }
  });

  // Open triggers
  document.querySelectorAll('[data-open-search]').forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      openSearch();
    });
  });

  // Expose global helper
  window.__openMauriceSearch = openSearch;
  window.__closeMauriceSearch = closeSearch;
}
