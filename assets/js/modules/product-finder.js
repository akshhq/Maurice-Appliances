/**
 * MAURICE APPLIANCES — Interactive 3-Step Product Selector / Finder (LG style)
 * Step 1: Category & Room/Family Size -> Step 2: Power/Feature Preference -> Step 3: Instant Recommendations
 */

import { ALL_PRODUCTS, PRODUCTS_BY_CAT } from '../data/products.js?v=3.0';
import { renderProductCard } from '../core/catalog-utils.js?v=3.0';
import { initReveals } from '../core/scroll.js?v=3.0';

export function initProductFinder() {
  const container = document.getElementById('productFinderApp');
  if (!container) return;

  const state = {
    category: 'water-heaters',
    usage: 'family-medium',
    feature: 'glassline'
  };

  const optionsMap = {
    'water-heaters': {
      title: "Find Your Perfect Water Heater in 3 Steps",
      sub: "Match your family size, bathroom pressure, and tank durability standard.",
      usages: [
        { id: 'family-small', label: '1–2 Persons / Kitchen Sink', desc: 'Instant 1L to 3L rapid heating geyser' },
        { id: 'family-medium', label: '3–4 Persons / Standard Family', desc: '10L to 15L high-efficiency storage geyser' },
        { id: 'family-large', label: '5+ Persons / Multi-Bathroom', desc: '25L to 50L heavy-duty / high-rise pressure geyser' }
      ],
      features: [
        { id: 'glassline', label: 'Glassline Vitreous Enamel', desc: '5-year inner tank warranty & hard water protection' },
        { id: 'digital', label: 'Digital Temperature Display', desc: 'Real-time temperature feedback & smart controls' },
        { id: 'economy', label: 'Standard Metal / ABS Body', desc: 'Proven reliability and everyday value' }
      ]
    },
    'room-heaters': {
      title: "Find Your Ideal Winter Room Heater in 3 Steps",
      sub: "Choose by room dimensions, instant radiant warmth, or silent fan convection.",
      usages: [
        { id: 'room-small', label: 'Personal Desk / Small Room (<100 sq ft)', desc: '750W–800W compact quartz or single carbon pillar' },
        { id: 'room-medium', label: 'Master Bedroom (100–200 sq ft)', desc: '1500W Rado heat pillar or silent convection blower' },
        { id: 'room-large', label: 'Living Room / Large Hall (>200 sq ft)', desc: '2000W double element metal warmer or 2-in-1 gas heater' }
      ],
      features: [
        { id: 'carbon', label: 'Carbon Fiber Rods', desc: 'Glare-free deep radiant warmth with long node lifespan' },
        { id: 'quartz', label: 'Quartz Pillars', desc: 'Instant focused heat with safety tilt-over cut-off' },
        { id: 'blower', label: 'Turbo Fan Convection', desc: 'Rapid hot air circulation across entire room' }
      ]
    },
    'kitchen': {
      title: "Find Your Kitchen Appliances in 3 Steps",
      sub: "Select cooktops, high-torque mixer grinders, or smoke-free auto-clean chimneys.",
      usages: [
        { id: 'prep-cook', label: 'Cooking & Stoves', desc: '2 to 4 burner toughened glass / SS gas stoves & induction' },
        { id: 'grind-blend', label: 'Grinding & Churning', desc: '500W–600W 30-min continuous rated mixer grinders & madhani' },
        { id: 'clean-vent', label: 'Chimneys & Air Management', desc: '60cm / 90cm 1250 m³/h motion-sensor auto-clean chimneys' }
      ],
      features: [
        { id: 'auto-ignite', label: 'Touch & Auto Ignition', desc: 'Modern push ignition and smart touch sensor operation' },
        { id: 'heavy-duty', label: 'Heavy Duty 100% Copper', desc: 'Long-life commercial-rated motor & brass components' },
        { id: 'motion-clean', label: 'Motion Gesture & Auto-Clean', desc: 'Wave-to-operate chimney with thermal oil collector' }
      ]
    }
  };

  function getRecommendations() {
    if (state.category === 'water-heaters') {
      if (state.usage === 'family-small') {
        return ALL_PRODUCTS.filter(p => p.cat === 'water-heaters' && (p.capacity === '1L' || p.capacity === '3L')).slice(0, 3);
      } else if (state.usage === 'family-large') {
        return ALL_PRODUCTS.filter(p => p.cat === 'water-heaters' && (p.capacity === '25L' || p.capacity === '35L' || p.capacity === '50L')).slice(0, 3);
      } else {
        return ALL_PRODUCTS.filter(p => p.cat === 'water-heaters' && (p.capacity === '10L' || p.capacity === '15L')).slice(0, 3);
      }
    } else if (state.category === 'room-heaters') {
      if (state.usage === 'room-small') {
        return ALL_PRODUCTS.filter(p => p.cat === 'room-heaters' && (p.wattage <= 1000)).slice(0, 3);
      } else if (state.usage === 'room-large') {
        return ALL_PRODUCTS.filter(p => p.cat === 'room-heaters' && (p.wattage >= 2000 || p.model.includes('GAS'))).slice(0, 3);
      } else {
        return ALL_PRODUCTS.filter(p => p.cat === 'room-heaters' && (p.wattage === 1500 || p.model.includes('RADO'))).slice(0, 3);
      }
    } else {
      if (state.usage === 'clean-vent') {
        return ALL_PRODUCTS.filter(p => p.cat === 'chimneys').slice(0, 3);
      } else if (state.usage === 'grind-blend') {
        return ALL_PRODUCTS.filter(p => p.cat === 'mixer-grinders' || p.cat === 'madhani').slice(0, 3);
      } else {
        return ALL_PRODUCTS.filter(p => p.cat === 'gas-stoves' || p.cat === 'induction').slice(0, 3);
      }
    }
  }

  function render() {
    const activeData = optionsMap[state.category] || optionsMap['water-heaters'];
    const recs = getRecommendations();

    container.innerHTML = `
      <div class="finder-card">
        <div class="finder-header">
          <span class="eyebrow">Smart Selection Engine</span>
          <h2>${activeData.title}</h2>
          <p class="text-muted">${activeData.sub}</p>
        </div>

        <!-- Step 1: Category Selector Tabs -->
        <div class="finder-step">
          <p class="finder-step__title"><span class="step-num">1</span><span>Select Appliance Category</span></p>
          <div class="finder-cat-chips">
            <button type="button" class="finder-cat-btn ${state.category === 'water-heaters' ? 'active' : ''}" data-cat="water-heaters">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="3" width="12" height="14" rx="6"/><path d="M9 21h2M13 21h2"/><circle cx="12" cy="10" r="2"/></svg>
              <span>Water Heaters (Geysers)</span>
            </button>
            <button type="button" class="finder-cat-btn ${state.category === 'room-heaters' ? 'active' : ''}" data-cat="room-heaters">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 3v6M12 3v6M17 3v6"/><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 15h8"/></svg>
              <span>Room Heaters</span>
            </button>
            <button type="button" class="finder-cat-btn ${state.category === 'kitchen' ? 'active' : ''}" data-cat="kitchen">
              <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="8" cy="12" r="2"/><circle cx="16" cy="12" r="2"/></svg>
              <span>Kitchen & Cooking</span>
            </button>
          </div>
        </div>

        <!-- Step 2: Usage / Capacity Selection -->
        <div class="finder-step">
          <p class="finder-step__title"><span class="step-num">2</span><span>Select Size & Requirement</span></p>
          <div class="finder-options-grid">
            ${activeData.usages.map(u => `
              <div class="finder-opt-card ${state.usage === u.id ? 'active' : ''}" data-usage="${u.id}">
                <div class="finder-opt-card__radio"></div>
                <div>
                  <h4>${u.label}</h4>
                  <p>${u.desc}</p>
                </div>
              </div>
            `).join('')}
          </div>
        </div>

        <!-- Step 3: Recommended Results -->
        <div class="finder-step" style="border-bottom:none">
          <p class="finder-step__title"><span class="step-num">3</span><span>Recommended For You (<span style="color:var(--red)">${recs.length} Optimal Matches</span>)</span></p>
          <div class="finder-recs-grid">
            ${recs.map(p => renderProductCard(p, { showCompare: true })).join('')}
          </div>
        </div>
      </div>
    `;

    // Make rendered cards immediately visible
    setTimeout(() => {
      container.querySelectorAll('.pcard').forEach(c => c.classList.add('in-view'));
      initReveals();
    }, 10);

    // Attach click handlers
    container.querySelectorAll('.finder-cat-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        state.category = btn.dataset.cat;
        state.usage = optionsMap[state.category].usages[0].id;
        state.feature = optionsMap[state.category].features[0].id;
        render();
      });
    });

    container.querySelectorAll('[data-usage]').forEach(card => {
      card.addEventListener('click', () => {
        state.usage = card.dataset.usage;
        render();
      });
    });
  }

  render();
}
