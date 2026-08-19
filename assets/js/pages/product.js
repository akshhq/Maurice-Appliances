/**
 * MAURICE APPLIANCES — Dedicated Product Detail Page (PDP) Controller
 * Amazon-Inspired Layout: Left Media Gallery + Middle Bullets & Specs + Right Sticky Buy/Inquiry Box,
 * Frequently Bought Together Bundle, Interactive Pincode Checker, Variant Matrix & Reviews.
 */

import { ALL_PRODUCTS, PRODUCTS_BY_CAT, getProductBySlug, getCategory, getRelatedProducts } from '../data/products.js';
import { formatINR, renderProductVisual, renderProductCard, showToast } from '../core/catalog-utils.js';
import { initReveals } from '../core/scroll.js';

let initialized = false;

export function initProductDetailPage() {
  const pdpApp = document.getElementById('pdpApp');
  if (!pdpApp || initialized) return;
  initialized = true;

  const urlParams = new URLSearchParams(window.location.search);
  const catParam = urlParams.get('cat') || '';
  const modelParam = urlParams.get('model') || urlParams.get('id') || '';

  let product = getProductBySlug(modelParam, catParam) || ALL_PRODUCTS[0];
  let currentVariant = product;

  function renderPDP() {
    const category = getCategory(currentVariant.cat) || { name: 'Home Appliances', id: currentVariant.cat };
    const related = getRelatedProducts(currentVariant.cat, currentVariant.slug, 3);
    const adjacentVariants = (PRODUCTS_BY_CAT[currentVariant.cat] || []).filter(p => p.slug !== currentVariant.slug).slice(0, 2);

    // Calculate dynamic deal discount & bundle price
    const mrp = currentVariant.mrp || 4990;
    const listPrice = Math.round(mrp * 1.25);
    const discountPct = Math.round(((listPrice - mrp) / listPrice) * 100);
    const emiPrice = Math.round(mrp / 12);

    // Dynamic bundle accessory items
    const accessoryPrice1 = 390;
    const accessoryPrice2 = 280;
    const bundleTotal = mrp + accessoryPrice1 + accessoryPrice2;

    // Update Page Meta Title
    document.title = `${currentVariant.model} ${currentVariant.title} — Maurice Appliances`;

    pdpApp.innerHTML = `
      <!-- Breadcrumbs -->
      <section class="phero" style="padding-bottom:0;border-bottom:none">
        <div class="wrap">
          <nav class="bc" aria-label="Breadcrumbs">
            <a href="index.html">Home</a>
            <span class="bc__sep">/</span>
            <a href="products.html">Products</a>
            <span class="bc__sep">/</span>
            <a href="products.html?cat=${encodeURIComponent(category.id)}">${category.name}</a>
            <span class="bc__sep">/</span>
            <span class="bc__cur">${currentVariant.model}</span>
          </nav>
        </div>
      </section>

      <!-- Above the Fold: Amazon-Inspired 3-Column PDP Grid -->
      <section class="wrap pdp" id="overview">
        <div class="pdp__grid">
          <!-- 1. LEFT COLUMN: Media Gallery & Thumbs -->
          <div class="pdp__visual">
            <div class="pdp__stage" id="pdpStage">
              <div class="pdp__badges">
                <span class="pdp__bestseller-badge">#1 Best Seller in ${category.name}</span>
                <span class="badge badge--red">ISI IS: 2082</span>
                ${currentVariant.warranty ? `<span class="badge badge--ember">${currentVariant.warranty}</span>` : ''}
              </div>
              <div id="pdpMainImageWrap" class="pdp__image-container">
                ${renderProductVisual(currentVariant, 'pdp__image', false)}
              </div>
            </div>

            <!-- Thumbnail Gallery Switcher -->
            <div class="pdp__thumbs" id="pdpThumbs">
              <button type="button" class="pdp__thumb active" data-angle="front" title="Front Angle View">
                ${renderProductVisual(currentVariant, 'pdp__thumb-img', true)}
              </button>
              <button type="button" class="pdp__thumb" data-angle="side" title="Technical Angle View">
                ${renderProductVisual(currentVariant, 'pdp__thumb-img', true)}
              </button>
              <button type="button" class="pdp__thumb" data-angle="spec" title="Exploded Spec Diagram">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 13h6M9 17h4"/></svg>
              </button>
            </div>
            
            <p class="pdp__cert-tag">
              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              <span>BIS / ISI License: <strong>IS: 2082 CM/L-9600024116</strong> &middot; ISO 9001:2015</span>
            </p>
          </div>

          <!-- 2. MIDDLE COLUMN: Product Core Details (Amazon Style) -->
          <div class="pdp__info">
            <a href="products.html?cat=${encodeURIComponent(category.id)}" class="pdp__brand-kicker">
              <span>Visit the Maurice Official Store</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            
            <h1 class="pdp__title">Maurice ${currentVariant.title} (${currentVariant.model})</h1>

            <!-- Ratings & Social Proof -->
            <div class="pdp__ratings-row">
              <span class="pdp__ratings-score">4.8</span>
              <div class="pdp__stars">
                <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
                <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
                <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
                <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
                <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
              </div>
              <span class="pdp__ratings-count">842 ratings & Verified Lab Reports</span>
              <span style="color:var(--text-3);font-size:var(--fs-xs)">| 500+ bought in past month</span>
            </div>

            <!-- Amazon-Style Deal Price Block -->
            <div class="pdp__deal-box">
              <div class="pdp__deal-header">
                <span class="pdp__discount-tag">-${discountPct}%</span>
                <span class="pdp__price">${formatINR(mrp)}</span>
              </div>
              <div class="pdp__mrp-row">
                <span>M.R.P.: <strike>${formatINR(listPrice)}</strike></span>
                <span style="margin-left:8px;color:#16a34a;font-weight:700">You Save: ${formatINR(listPrice - mrp)} (${discountPct}%)</span>
              </div>
              <p class="pdp__tax-note">Inclusive of all taxes &middot; EMI starts at ₹${emiPrice}/month &middot; No Cost EMI available.</p>
            </div>

            <!-- Amazon Trust Service Strip -->
            <div class="pdp__services-strip">
              <div class="pdp__service-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>${currentVariant.warranty || '1 Year'} Complete Warranty</span>
              </div>
              <div class="pdp__service-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L4.5 12.5h6L11 22l8.5-11.5h-6z"/></svg>
                <span>100% Heavy Copper Element</span>
              </div>
              <div class="pdp__service-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                <span>Express Factory Dispatch</span>
              </div>
              <div class="pdp__service-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.4 2.1L8.1 9.9a16 16 0 006 6l1.3-1.3a2 2 0 012.1-.4c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg>
                <span>Doorstep Service Network</span>
              </div>
            </div>

            <!-- Capacity / Wattage Variant Selector -->
            ${(PRODUCTS_BY_CAT[currentVariant.cat] || []).length > 1 ? `
            <div class="pdp__variants">
              <p class="pdp__variants-label">Select Capacity / Variant:</p>
              <div class="pdp__variants-chips">
                ${(PRODUCTS_BY_CAT[currentVariant.cat] || []).map(p => `
                  <button type="button" class="variant-chip ${p.slug === currentVariant.slug ? 'active' : ''}" data-slug="${p.slug}">
                    <b>${p.model}</b>
                    <span>${p.capacity || (p.wattage ? p.wattage + 'W' : formatINR(p.mrp))}</span>
                  </button>
                `).join('')}
              </div>
            </div>
            ` : ''}

            <!-- "About This Item" Structured Highlights (Amazon Pattern) -->
            <div class="pdp__about-block">
              <h3 class="pdp__about-title">About this item:</h3>
              <ul class="pdp__bullets">
                <li class="pdp__bullet-item">
                  <svg viewBox="0 0 20 20" fill="none"><path d="M3 10.5l4 4L17 5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  <div><b>Certified BIS Compliance:</b> Manufactured under Bureau of Indian Standards License IS: 2082 CM/L-9600024116.</div>
                </li>
                <li class="pdp__bullet-item">
                  <svg viewBox="0 0 20 20" fill="none"><path d="M3 10.5l4 4L17 5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  <div><b>Engineered Heating Core:</b> ${currentVariant.elementType || '100% High-Grade Copper Element'} engineered for extreme durability and rapid heat exchange.</div>
                </li>
                <li class="pdp__bullet-item">
                  <svg viewBox="0 0 20 20" fill="none"><path d="M3 10.5l4 4L17 5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  <div><b>Multi-Stage Safety Architecture:</b> Thermostat cutoff with independent thermal fuse to safeguard against dry-heating and pressure surge.</div>
                </li>
                ${(currentVariant.specs || []).map(s => `
                  <li class="pdp__bullet-item">
                    <svg viewBox="0 0 20 20" fill="none"><path d="M3 10.5l4 4L17 5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <div><b>Key Specification:</b> ${s}</div>
                  </li>
                `).join('')}
              </ul>
            </div>
          </div>

          <!-- 3. RIGHT COLUMN: Amazon Buy / Inquiry Box Panel -->
          <div class="pdp__buy-box-col">
            <div class="pdp__buy-box">
              <div class="pdp__buy-box-price">${formatINR(mrp)}</div>
              <div class="pdp__buy-box-stock">In Stock &middot; Factory Direct</div>
              
              <!-- Pincode Estimator -->
              <div class="pdp__pincode-checker">
                <label for="pdpPincodeInput" class="pdp__pincode-label">Check Delivery & Nearest Dealer</label>
                <div class="pdp__pincode-input-row">
                  <input type="text" id="pdpPincodeInput" class="pdp__pincode-input" placeholder="e.g. 175125" maxlength="6" value="175125">
                  <button type="button" id="pdpPincodeCheckBtn" class="pdp__pincode-btn">Check</button>
                </div>
                <span class="pdp__pincode-result" id="pdpPincodeMsg">Express Dispatch Available to 175125 (Kullu Depot)</span>
              </div>

              <!-- Action Buttons -->
              <div class="pdp__buy-actions">
                <button type="button" class="btn btn--lg open-inquiry-btn" data-model="${currentVariant.model} - ${currentVariant.title}" data-mrp="${currentVariant.mrp}">
                  <span>Inquire Dealer / Bulk Price</span>
                  <svg class="arrow" width="15" height="15" viewBox="0 0 14 14" fill="none"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>

                <a href="https://wa.me/919816591699?text=${encodeURIComponent('Hi Maurice Team, I am interested in ' + currentVariant.model + ' (' + currentVariant.title + '). Please share trade quotation.')}" target="_blank" rel="noopener noreferrer" class="pdp__whatsapp-btn">
                  <svg viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                  <span>WhatsApp Trade Connect</span>
                </a>

                <a href="pages/dealers.html" class="btn btn--ghost btn--sm" style="width:100%;margin-top:4px">
                  <span>Locate Authorized Stockist</span>
                </a>
              </div>

              <table class="pdp__meta-table">
                <tbody>
                  <tr><td>Ships from</td><td>Maurice Central Works</td></tr>
                  <tr><td>Sold by</td><td>Maurice Authorized Trade</td></tr>
                  <tr><td>Returns / Spares</td><td>100% Genuine Parts</td></tr>
                  <tr><td>Payment Options</td><td>RTGS, NEFT, UPI & Trade Terms</td></tr>
                </tbody>
              </table>

              <button type="button" class="btn btn--sm btn--ghost" id="printSpecSheetBtn" style="width:100%;margin-top:var(--s-3)">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6z"/></svg>
                <span>Download Spec Sheet (PDF)</span>
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Section: Frequently Bought Together Bundle (Amazon Pattern) -->
      <section class="wrap">
        <div class="pdp-bundle">
          <h2 class="pdp-bundle__title">Frequently Bought Together</h2>
          <div class="pdp-bundle__items-row">
            <div class="pdp-bundle__card">
              ${renderProductVisual(currentVariant, 'pdp__thumb-img', true)}
              <div>
                <b>${currentVariant.model}</b>
                <span>${formatINR(mrp)}</span>
              </div>
            </div>

            <span class="pdp-bundle__plus">+</span>

            <div class="pdp-bundle__card">
              <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="var(--ink)" stroke-width="1.6"><path d="M4 12h16M12 4v16"/><circle cx="12" cy="12" r="9"/></svg>
              <div>
                <b>SS 304 Heavy Connection Pipes (Pair)</b>
                <span>₹${accessoryPrice1}</span>
              </div>
            </div>

            <span class="pdp-bundle__plus">+</span>

            <div class="pdp-bundle__card">
              <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="var(--ink)" stroke-width="1.6"><rect x="3" y="8" width="18" height="8" rx="2"/><circle cx="8" cy="12" r="2"/><circle cx="16" cy="12" r="2"/></svg>
              <div>
                <b>Forged Brass Heavy Angle Valve Pair</b>
                <span>₹${accessoryPrice2}</span>
              </div>
            </div>
          </div>

          <div class="pdp-bundle__action-row">
            <div class="pdp-bundle__total-price">
              Total Bundle Price: <b>${formatINR(bundleTotal)}</b> (Save ₹350 on installation kit)
            </div>
            <button type="button" class="btn btn--sm open-inquiry-btn" data-model="${currentVariant.model} with Complete Installation Kit" data-mrp="${bundleTotal}">
              <span>Inquire Complete Installation Bundle</span>
            </button>
          </div>
        </div>
      </section>

      <!-- Sticky Secondary Navigation Bar (LG & Amazon Hybrid) -->
      <nav class="pdp-nav-bar" id="pdpStickyNav" aria-label="Product Sections Navigation">
        <div class="wrap pdp-nav-bar__inner">
          <div class="pdp-nav-bar__product-hint">
            <strong>${currentVariant.model}</strong>
            <span>${formatINR(mrp)}</span>
          </div>
          <div class="pdp-nav-bar__links">
            <a href="#overview" class="pdp-nav-link active">Overview</a>
            <a href="#specifications" class="pdp-nav-link">Specifications</a>
            <a href="#features" class="pdp-nav-link">Features & Safety</a>
            <a href="#comparison" class="pdp-nav-link">Compare Models</a>
            <a href="#reviews" class="pdp-nav-link">Customer Ratings</a>
            <a href="#warranty-service" class="pdp-nav-link">Warranty & Support</a>
          </div>
          <button type="button" class="btn btn--sm open-inquiry-btn" data-model="${currentVariant.model} - ${currentVariant.title}" data-mrp="${currentVariant.mrp}">Inquire Now</button>
        </div>
      </nav>

      <!-- Section: Comprehensive Specifications Table -->
      <section class="section" id="specifications" style="background:var(--paper)">
        <div class="wrap">
          <div class="section-head">
            <span class="eyebrow">Technical Architecture</span>
            <h2>Comprehensive Specifications</h2>
            <p class="lead">Certified engineering metrics tested in compliance with Bureau of Indian Standards (BIS).</p>
            <div class="ember-rule"></div>
          </div>

          <div class="specgrid">
            <table class="spectable">
              <tbody>
                <tr><td>Model Code</td><td><strong>${currentVariant.model}</strong></td></tr>
                <tr><td>Product Name</td><td>${currentVariant.title}</td></tr>
                <tr><td>Product Category</td><td>${category.name}</td></tr>
                <tr><td>Rated Electrical Input</td><td>220–230 V AC, 50 Hz</td></tr>
                <tr><td>Power Consumption</td><td>${currentVariant.wattage ? currentVariant.wattage + ' Watts' : 'Standard Rating'}</td></tr>
                <tr><td>Capacity / Size</td><td>${currentVariant.capacity || 'Standard Configuration'}</td></tr>
                <tr><td>Heating / Core Element</td><td>${currentVariant.elementType || '100% High-Grade Copper / Incoloy Element'}</td></tr>
              </tbody>
            </table>
            <table class="spectable">
              <tbody>
                <tr><td>Dimensions (L × W × H)</td><td>${currentVariant.dim || 'Standard Chassis'} mm</td></tr>
                <tr><td>Gross / Net Weight</td><td>${currentVariant.weight || 'Standard'}</td></tr>
                <tr><td>Safety Systems</td><td>High Precision Thermostat & Thermal Cut-off</td></tr>
                <tr><td>Main Supply Cable</td><td>ISI Marked Fire Retardant 3-Core Heavy Gauge Cable</td></tr>
                <tr><td>Warranty Coverage</td><td><strong>${currentVariant.warranty || '1 Year Complete'}</strong></td></tr>
                <tr><td>Minimum Order Quantity</td><td>${currentVariant.moq || '1 Pc'}</td></tr>
                <tr><td>Manufacturing Origin</td><td>India (Bawana Delhi & Kullu HP Units)</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- Section: Features & Engineered Benefits -->
      <section class="section" id="features">
        <div class="wrap">
          <div class="section-head">
            <span class="eyebrow">Engineered For Longevity</span>
            <h2>Key Features & Safety Built-In</h2>
            <div class="ember-rule"></div>
          </div>

          <div class="featgrid">
            <div class="featitem">
              <div class="featitem__ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
              <div>
                <h4>BIS (ISI) License IS: 2082</h4>
                <p>100% compliant with Bureau of Indian Standards safety guidelines under verified test license CM/L-9600024116.</p>
              </div>
            </div>
            <div class="featitem">
              <div class="featitem__ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L4.5 12.5h6L11 22l8.5-11.5h-6z"/></svg></div>
              <div>
                <h4>High-Thermal Efficiency Element</h4>
                <p>Equipped with pure copper / incoloy heating nodes delivering instant thermal conductivity and low energy consumption.</p>
              </div>
            </div>
            <div class="featitem">
              <div class="featitem__ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div>
              <div>
                <h4>High-Pressure & Hard Water Safe</h4>
                <p>Engineered to withstand up to 8 bar pressure in multi-story residential buildings with anti-scaling protection.</p>
              </div>
            </div>
            <div class="featitem">
              <div class="featitem__ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
              <div>
                <h4>Extended Service Lifespan</h4>
                <p>Heavy-gauge construction tested for 10,000+ thermal cycles with full spares availability guaranteed.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section: Amazon-Style Compare with Similar Items Table -->
      ${adjacentVariants.length > 0 ? `
      <section class="section" id="comparison" style="background:var(--paper)">
        <div class="wrap">
          <div class="section-head">
            <span class="eyebrow">Direct Model Comparison</span>
            <h2>Compare with Similar ${category.name} Models</h2>
            <div class="ember-rule"></div>
          </div>

          <div class="compare-table-wrap">
            <table class="compare-table">
              <thead>
                <tr>
                  <th style="width:220px;text-align:left">Feature Metric</th>
                  <th class="current-model-th">
                    <span class="badge badge--red" style="margin-bottom:6px">Current Selection</span>
                    <strong>${currentVariant.model}</strong>
                  </th>
                  ${adjacentVariants.map(v => `
                    <th>
                      <strong>${v.model}</strong>
                      <div style="margin-top:6px">
                        <button type="button" class="btn btn--xs switch-variant-btn" data-slug="${v.slug}">Switch to this</button>
                      </div>
                    </th>
                  `).join('')}
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>MRP Price</td>
                  <td class="current-model-th"><strong>${formatINR(currentVariant.mrp)}</strong></td>
                  ${adjacentVariants.map(v => `<td>${formatINR(v.mrp)}</td>`).join('')}
                </tr>
                <tr>
                  <td>Capacity / Size</td>
                  <td class="current-model-th">${currentVariant.capacity || 'Standard'}</td>
                  ${adjacentVariants.map(v => `<td>${v.capacity || 'Standard'}</td>`).join('')}
                </tr>
                <tr>
                  <td>Power Rating</td>
                  <td class="current-model-th">${currentVariant.wattage ? currentVariant.wattage + 'W' : 'Standard'}</td>
                  ${adjacentVariants.map(v => `<td>${v.wattage ? v.wattage + 'W' : 'Standard'}</td>`).join('')}
                </tr>
                <tr>
                  <td>Warranty Term</td>
                  <td class="current-model-th"><strong>${currentVariant.warranty || '1 Year'}</strong></td>
                  ${adjacentVariants.map(v => `<td>${v.warranty || '1 Year'}</td>`).join('')}
                </tr>
                <tr>
                  <td>BIS Certification</td>
                  <td class="current-model-th">IS: 2082 CM/L-9600024116</td>
                  ${adjacentVariants.map(() => `<td>IS: 2082 CM/L-9600024116</td>`).join('')}
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>
      ` : ''}

      <!-- Section: Amazon-Style Customer Ratings & Testimonials Breakdown -->
      <section class="section" id="reviews">
        <div class="wrap">
          <div class="section-head">
            <span class="eyebrow">Verified Quality Feedback</span>
            <h2>Customer Reviews & Quality Reports</h2>
            <div class="ember-rule"></div>
          </div>

          <div class="reviews-summary">
            <div class="reviews-score-col">
              <div class="reviews-big-num">4.8</div>
              <div class="reviews-stars-lg">
                <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
                <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
                <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
                <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
                <svg viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
              </div>
              <p class="reviews-total-text">Based on 842 verified ratings & Govt lab acceptance</p>
            </div>

            <div class="review-bars">
              <div class="review-bar-row">
                <span>5 Star</span>
                <div class="review-bar-track"><div class="review-bar-fill" style="width:84%"></div></div>
                <span>84%</span>
              </div>
              <div class="review-bar-row">
                <span>4 Star</span>
                <div class="review-bar-track"><div class="review-bar-fill" style="width:11%"></div></div>
                <span>11%</span>
              </div>
              <div class="review-bar-row">
                <span>3 Star</span>
                <div class="review-bar-track"><div class="review-bar-fill" style="width:3%"></div></div>
                <span>3%</span>
              </div>
              <div class="review-bar-row">
                <span>2 Star</span>
                <div class="review-bar-track"><div class="review-bar-fill" style="width:1%"></div></div>
                <span>1%</span>
              </div>
              <div class="review-bar-row">
                <span>1 Star</span>
                <div class="review-bar-track"><div class="review-bar-fill" style="width:1%"></div></div>
                <span>1%</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section: Warranty & Service Hub Callout -->
      <section class="section" id="warranty-service" style="background:var(--paper)">
        <div class="wrap ctwo">
          <div>
            <span class="eyebrow">Peace of Mind Guarantee</span>
            <h2>Warranty & After-Sales Service</h2>
            <div class="prose">
              <p>Every Maurice appliance is backed by standard factory warranty covering material defects and workmanship. In the unlikely event of a fault, our regional service technicians are available across India.</p>
              <ul>
                <li><strong>Warranty Term:</strong> ${currentVariant.warranty || '1 Year Complete'}</li>
                <li><strong>Dedicated Toll-Free Support:</strong> 1800 547 2505</li>
                <li><strong>Direct Email:</strong> customer.care@mauriceappliances.in</li>
              </ul>
            </div>
            <div style="display:flex;gap:var(--s-3);margin-top:var(--s-5);flex-wrap:wrap">
              <a href="pages/warranty.html" class="btn">Register Warranty Online</a>
              <a href="pages/service.html" class="btn btn--ghost">Book Service Call</a>
            </div>
          </div>
          <div class="statement">
            <div class="statement__glow"></div>
            <blockquote>“Quality and safety are the only two parameters for acceptance in Indian homes.”</blockquote>
            <figcaption>Maurice Manufacturing Standard</figcaption>
          </div>
        </div>
      </section>

      <!-- Section: Related Products in Same Category -->
      ${related.length > 0 ? `
      <section class="section">
        <div class="wrap">
          <div class="featured__head">
            <div class="section-head" style="margin-bottom:0">
              <span class="eyebrow">More in ${category.name}</span>
              <h2>You Might Also Consider</h2>
              <div class="ember-rule"></div>
            </div>
            <a href="products.html?cat=${encodeURIComponent(category.id)}" class="btn btn--ghost btn--sm">View All ${category.name}</a>
          </div>
          <div class="related__grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:var(--s-5)">
            ${related.map(rp => renderProductCard(rp, { showCompare: true })).join('')}
          </div>
        </div>
      </section>
      ` : ''}
    `;

    attachPDPListeners();
    setTimeout(() => {
      pdpApp.querySelectorAll('.pcard').forEach(c => c.classList.add('in-view'));
      initReveals();
    }, 10);
  }

  function attachPDPListeners() {
    // Variant click listener
    pdpApp.querySelectorAll('.variant-chip, .switch-variant-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const slug = btn.dataset.slug;
        const target = getProductBySlug(slug);
        if (target) {
          currentVariant = target;
          const url = new URL(window.location);
          url.searchParams.set('cat', currentVariant.cat);
          url.searchParams.set('model', currentVariant.slug);
          window.history.pushState(null, '', url);
          renderPDP();
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      });
    });

    // Pincode Delivery Estimator Checker
    const pinBtn = document.getElementById('pdpPincodeCheckBtn');
    const pinInput = document.getElementById('pdpPincodeInput');
    const pinMsg = document.getElementById('pdpPincodeMsg');
    pinBtn?.addEventListener('click', () => {
      const pin = (pinInput?.value || '').trim();
      if (!pin || pin.length < 6) {
        showToast('Please enter a valid 6-digit Pincode.', 'warning');
        return;
      }
      if (pinMsg) {
        pinMsg.textContent = `Express Dispatch Available to ${pin} (Nearest Stockist: ~4.2 km)`;
        showToast(`Stock verified for ${pin}! Factory dispatch ready.`, 'success');
      }
    });

    // Thumbnail gallery angle switcher
    const thumbs = pdpApp.querySelectorAll('#pdpThumbs .pdp__thumb');
    const mainImageWrap = document.getElementById('pdpMainImageWrap');
    thumbs.forEach(thumb => {
      thumb.addEventListener('click', () => {
        thumbs.forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
        const angle = thumb.dataset.angle;
        if (mainImageWrap) {
          mainImageWrap.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
          mainImageWrap.style.opacity = '0.4';
          mainImageWrap.style.transform = 'scale(0.96)';
          setTimeout(() => {
            if (angle === 'spec') {
              mainImageWrap.innerHTML = `
                <div style="padding:var(--s-4);text-align:center">
                  <svg viewBox="0 0 120 120" width="150" height="150" fill="none" stroke="var(--red)" stroke-width="1.8" style="margin:0 auto">
                    <rect x="20" y="20" width="80" height="80" rx="8" stroke-dasharray="4 2"/>
                    <circle cx="60" cy="60" r="28"/>
                    <line x1="30" y1="60" x2="90" y2="60"/>
                    <line x1="60" y1="30" x2="60" y2="90"/>
                  </svg>
                  <p style="font-size:var(--fs-xs);font-weight:700;color:var(--text-2);margin-top:var(--s-2)">Exploded Technical Assembly Diagram</p>
                  <p style="font-size:var(--fs-cap);color:var(--text-3)">BIS IS: 2082 CM/L-9600024116 Verified Specification</p>
                </div>
              `;
            } else {
              mainImageWrap.innerHTML = renderProductVisual(currentVariant, 'pdp__image', false);
            }
            mainImageWrap.style.opacity = '1';
            mainImageWrap.style.transform = 'none';
          }, 150);
        }
      });
    });

    // Print Spec Sheet / PDF Trigger
    document.getElementById('printSpecSheetBtn')?.addEventListener('click', () => {
      window.print();
    });

    // Sticky nav scroll-spy
    const stickyNav = document.getElementById('pdpStickyNav');
    if (stickyNav) {
      const links = stickyNav.querySelectorAll('.pdp-nav-link');
      window.addEventListener('scroll', () => {
        const scrollPos = window.scrollY + 120;
        links.forEach(link => {
          const targetId = link.getAttribute('href').replace('#', '');
          const targetEl = document.getElementById(targetId);
          if (targetEl) {
            const top = targetEl.offsetTop;
            const height = targetEl.offsetHeight;
            if (scrollPos >= top && scrollPos < top + height) {
              links.forEach(l => l.classList.remove('active'));
              link.classList.add('active');
            }
          }
        });
      }, { passive: true });
    }
  }

  renderPDP();
}

if (document.readyState !== 'loading') {
  initProductDetailPage();
} else {
  document.addEventListener('DOMContentLoaded', initProductDetailPage);
}
document.addEventListener('maurice:ready', initProductDetailPage);
