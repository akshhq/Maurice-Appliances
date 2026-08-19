/**
 * MAURICE APPLIANCES — Interactive Dealer Locator Module (Havells style)
 * State / District cascade + Instant Pincode & Keyword search + Map directions.
 */

import { DEALER_NETWORK, getStates, getDistricts, filterDealers } from '../data/dealers.js';

export function initDealerLocator() {
  const container = document.getElementById('dealerLocatorApp');
  if (!container) return;

  const states = getStates();

  container.innerHTML = `
    <div class="locator-box">
      <div class="locator-filters">
        <div class="locator-field">
          <label for="dealerStateSelect">Select State</label>
          <select id="dealerStateSelect" class="locator-select">
            <option value="">All States / UTs (${states.length})</option>
            ${states.map(s => `<option value="${s}">${s}</option>`).join('')}
          </select>
        </div>
        <div class="locator-field">
          <label for="dealerDistrictSelect">Select District / City</label>
          <select id="dealerDistrictSelect" class="locator-select">
            <option value="">All Districts</option>
          </select>
        </div>
        <div class="locator-field">
          <label for="dealerPincodeInput">Search by Pincode</label>
          <div class="locator-search-wrap">
            <input type="text" id="dealerPincodeInput" class="locator-input" placeholder="e.g. 175125 or 110039" maxlength="6">
            <button type="button" id="clearPincodeBtn" class="locator-clear-btn" aria-label="Clear pincode">&times;</button>
          </div>
        </div>
        <div class="locator-field locator-field--btn">
          <label>&nbsp;</label>
          <button type="button" class="btn btn--ghost" id="resetDealerFilters" style="width:100%">Reset Search</button>
        </div>
      </div>

      <div class="locator-meta">
        <p class="locator-count">Showing <b id="dealerResultCount">${DEALER_NETWORK.length}</b> authorized dealer & distribution points</p>
        <span class="locator-tag">Verified BIS & ISO Authorized Partners</span>
      </div>

      <div class="locator-results-grid" id="dealerResultsGrid">
        <!-- Rendered via JS -->
      </div>

      <div class="locator-empty is-hidden" id="dealerEmptyState">
        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <h3>No direct dealer located for this selection</h3>
        <p>Our network is continuously expanding. Call our central support team at <strong>1800 547 2505</strong> for express dispatch or nearest stockist directions.</p>
        <a href="become-dealer.html" class="btn" style="margin-top:var(--s-4)">Apply to become a dealer in this area</a>
      </div>
    </div>
  `;

  const stateSelect = document.getElementById('dealerStateSelect');
  const distSelect = document.getElementById('dealerDistrictSelect');
  const pinInput = document.getElementById('dealerPincodeInput');
  const countEl = document.getElementById('dealerResultCount');
  const gridEl = document.getElementById('dealerResultsGrid');
  const emptyEl = document.getElementById('dealerEmptyState');
  const resetBtn = document.getElementById('resetDealerFilters');
  const clearPinBtn = document.getElementById('clearPincodeBtn');

  function updateDistricts() {
    const selectedState = stateSelect.value;
    const districts = getDistricts(selectedState);
    distSelect.innerHTML = `<option value="">All Districts (${districts.length})</option>` +
      districts.map(d => `<option value="${d}">${d}</option>`).join('');
  }

  function renderDealers() {
    const results = filterDealers({
      state: stateSelect.value,
      district: distSelect.value,
      pincode: pinInput.value
    });

    if (countEl) countEl.textContent = results.length;
    emptyEl.classList.toggle('is-hidden', results.length > 0);
    gridEl.classList.toggle('is-hidden', results.length === 0);

    gridEl.innerHTML = results.map(d => {
      const gmapsQuery = encodeURIComponent(`${d.firm}, ${d.address}`);
      const gmapsUrl = `https://www.google.com/maps/search/?api=1&query=${gmapsQuery}`;
      const phoneClean = (d.phone || '').replace(/[^0-9+]/g, '');

      return `
        <div class="dealer-card ${d.isFactoryOutlet ? 'dealer-card--featured' : ''} in-view">
          <div class="dealer-card__head">
            <div>
              <span class="dealer-card__type">${d.type}</span>
              ${d.isFactoryOutlet ? '<span class="badge badge--red" style="margin-left:6px">Direct Manufacturing Depot</span>' : ''}
              <h3 class="dealer-card__firm">${d.firm}</h3>
            </div>
          </div>
          
          <div class="dealer-card__body">
            <p class="dealer-card__loc">
              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <span>${d.address}</span>
            </p>
            <p class="dealer-card__contact">
              <strong>Contact:</strong> ${d.contactPerson} &middot; 
              <a href="tel:${phoneClean}" class="dealer-card__phone">${d.phone}</a>
            </p>
            ${d.categories ? `
            <div class="dealer-card__tags">
              ${d.categories.map(c => `<span class="dealer-tag">${c}</span>`).join('')}
            </div>` : ''}
          </div>

          <div class="dealer-card__foot">
            <a href="tel:${phoneClean}" class="btn btn--sm">
              <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.4 2.1L8.1 9.9a16 16 0 006 6l1.3-1.3a2 2 0 012.1-.4c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg>
              <span>Call Dealer</span>
            </a>
            <a href="${gmapsUrl}" target="_blank" rel="noopener noreferrer" class="btn btn--sm btn--ghost">
              <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
              <span>Get Directions</span>
            </a>
          </div>
        </div>
      `;
    }).join('');
  }

  stateSelect.addEventListener('change', () => {
    updateDistricts();
    renderDealers();
  });

  distSelect.addEventListener('change', renderDealers);

  pinInput.addEventListener('input', () => {
    renderDealers();
  });

  clearPinBtn.addEventListener('click', () => {
    pinInput.value = '';
    renderDealers();
  });

  resetBtn.addEventListener('click', () => {
    stateSelect.value = '';
    updateDistricts();
    pinInput.value = '';
    renderDealers();
  });

  updateDistricts();
  renderDealers();
}
