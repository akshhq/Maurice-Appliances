/**
 * MAURICE APPLIANCES — Interactive Smart Selection Engine
 * Step 1: 10 Appliance Categories -> Step 2: Capacity & Room Requirement -> Step 2B: Feature Preference -> Step 3: Curated ISI Recommendations
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
    feature: 'all'
  };

  const optionsMap = {
    'water-heaters': {
      title: "Find Your Ideal Water Heater in 3 Steps",
      sub: "Match your family size, bathroom pressure, and tank durability standard.",
      usages: [
        { id: 'sink-instant', label: '1–2 Persons / Kitchen Sink', desc: 'Instant 1L to 3L rapid heating geyser with thermal cut-off' },
        { id: 'compact-bath', label: '2–3 Persons / Compact Bath', desc: '6L to 10L high-efficiency storage geyser for quick baths' },
        { id: 'family-medium', label: '3–4 Persons / Standard Family', desc: '15L storage geyser with high-density PUF insulation' },
        { id: 'family-large', label: '4–6+ Persons / Master Bath', desc: '25L to 35L heavy-duty / high-rise pressure geyser' },
        { id: 'commercial-heavy', label: 'Heavy Demand / Multi-Point', desc: '50L commercial storage water heater' }
      ],
      features: [
        { id: 'all', label: 'All Durability Standards' },
        { id: 'glassline', label: 'Glassline Vitreous Enamel (5-Yr Warranty)' },
        { id: 'digital', label: 'Digital Temperature Controller' },
        { id: 'abs', label: 'ABS Shock-Proof Outer Body' },
        { id: 'metal', label: 'Heavy Metal Outer Body' }
      ]
    },
    'room-heaters': {
      title: "Find Your Ideal Winter Room Heater in 3 Steps",
      sub: "Choose by room dimensions, instant radiant warmth, or silent fan convection.",
      usages: [
        { id: 'desk-personal', label: 'Personal Desk / Study (<100 sq ft)', desc: '750W–800W compact quartz or single rod warmer' },
        { id: 'bedroom-silent', label: 'Master Bedroom (100–180 sq ft)', desc: '1000W–1500W silent convection fan blower' },
        { id: 'heat-pillar', label: 'Master Room / 360° Heating', desc: '1500W–2000W Rado carbon & quartz heat pillar' },
        { id: 'hall-large', label: 'Living Room / Large Hall (>200 sq ft)', desc: '2000W double element metal heavy room heater' },
        { id: 'gas-hybrid', label: 'Winter Power Backup / Off-Grid', desc: 'Gas & Electric 2-in-1 combo room heater' }
      ],
      features: [
        { id: 'all', label: 'All Technologies' },
        { id: 'carbon', label: 'Carbon Fiber Rods (Glare-free)' },
        { id: 'quartz', label: 'Quartz Rods (Instant heat)' },
        { id: 'blower', label: 'Turbo Fan Convection' },
        { id: 'gas', label: 'Gas Flame Heating' }
      ]
    },
    'gas-stoves': {
      title: "Find Your Perfect Gas Stove / Hob in 3 Steps",
      sub: "Select burner count, heat-resistant toughened glass, or brushed stainless steel.",
      usages: [
        { id: '1-burner', label: 'Single Chef / Compact Kitchen', desc: '1 Burner toughened glass / SS portable stove' },
        { id: '2-burner', label: 'Standard Indian Household', desc: '2 Burner heavy brass burners with deep drawn drip tray' },
        { id: '3-burner', label: 'Multi-Dish Family Cooking', desc: '3 Burner tri-pin brass burners with toughened glass' },
        { id: '4-burner', label: 'Large Feast / Festive Kitchen', desc: '4 Burner jumbo brass cooktop for parallel cooking' },
        { id: 'commercial', label: 'Commercial Canteen / Large Vessel', desc: 'Heavy duty commercial single / double burner' }
      ],
      features: [
        { id: 'all', label: 'All Finishes' },
        { id: 'glass', label: 'Toughened Black Glass' },
        { id: 'stainless', label: 'Stainless Steel Body' },
        { id: 'auto', label: 'Auto-Ignition Piezo' }
      ]
    },
    'induction': {
      title: "Find Your Induction / Infrared Cooktop in 3 Steps",
      sub: "Energy-efficient electromagnetic cooking with Indian preset menus and timer controls.",
      usages: [
        { id: 'daily-home', label: 'Everyday Home Cooking', desc: '1600W–2000W push-button induction with Indian presets' },
        { id: 'touch-digital', label: 'Modern Touch Sensor', desc: '2000W crystal glass plate with feather touch controls' },
        { id: 'infrared', label: 'Multi-Cookware Friendly', desc: '2200W ceramic infrared cooktop (works with any utensil)' },
        { id: 'commercial', label: 'Commercial Catering / Restaurant', desc: '3500W–5000W heavy gauge stainless steel induction' }
      ],
      features: [
        { id: 'all', label: 'All Controls' },
        { id: 'touch', label: 'Touch Sensor Controls' },
        { id: 'push', label: 'Push Button Controls' },
        { id: 'heavy', label: 'Heavy Duty 3500W–5000W' }
      ]
    },
    'fans': {
      title: "Find Your Fan & Air Management in 3 Steps",
      sub: "High-speed air delivery, silent copper motors, and heavy ventilation systems.",
      usages: [
        { id: 'ceiling', label: 'Ceiling Air Circulation', desc: '1200mm (48") high-speed aerodynamic ceiling fans' },
        { id: 'table-desk', label: 'Personal Desk / Study Corner', desc: '300mm–400mm table and all-purpose portable fans' },
        { id: 'wall-mount', label: 'Space Saver Wall Fan', desc: '400mm 3-speed oscillating wall mount fan' },
        { id: 'farratta', label: 'Heavy Outdoor / Hall Air Delivery', desc: '600mm Farratta high-velocity pedestal fan' },
        { id: 'ventilation', label: 'Kitchen & Bathroom Fresh Air', desc: '150mm–300mm fresh air & exhaust fans' }
      ],
      features: [
        { id: 'all', label: 'All Fan Classes' },
        { id: 'copper', label: '100% Copper High-Torque Motor' },
        { id: 'high-speed', label: 'High Speed Aerodynamic' },
        { id: 'silent', label: 'Low Noise Engineering' }
      ]
    },
    'mixer-grinders': {
      title: "Find Your Mixer Grinder & JMG in 3 Steps",
      sub: "High-torque 30-minute continuous rated copper motors for tough Indian spices.",
      usages: [
        { id: '2-jar', label: 'Daily Chutney & Dry Spices', desc: '500W 2 stainless steel jars with safety lock' },
        { id: '3-jar', label: 'Complete Family Kitchen', desc: '600W 3 stainless steel jars with heavy-gauge blades' },
        { id: 'jmg', label: 'Juicing, Churning & Blending', desc: '500W Juicer Mixer Grinder with micro-mesh filter' },
        { id: 'heavy-duty', label: 'Heavy Duty 30-Min Continuous', desc: '600W copper motor with overload protection' }
      ],
      features: [
        { id: 'all', label: 'All Motor Ratings' },
        { id: 'copper', label: '100% Pure Copper Winding' },
        { id: 'continuous', label: '30-Min Continuous Rating' },
        { id: 'ss-jars', label: 'Stainless Steel Food-Grade Jars' }
      ]
    },
    'chimneys': {
      title: "Find Your Kitchen Chimney in 3 Steps",
      sub: "Smoke-free, oil-free cooking with auto-clean thermal technology and wave motion sensors.",
      usages: [
        { id: '60cm-curved', label: 'Standard 2–3 Burner Stove', desc: '60cm curved glass chimney with 1100 m³/h suction' },
        { id: '60cm-autoclean', label: 'Auto-Clean & Motion Gesture', desc: '60cm wave sensor chimney with 1250 m³/h suction' },
        { id: '90cm-island', label: 'Wide 3–4 Burner Hob / Kitchen', desc: '90cm high-capacity chimney with 1350 m³/h suction' }
      ],
      features: [
        { id: 'all', label: 'All Chimney Features' },
        { id: 'autoclean', label: 'Heat Auto-Clean with Oil Collector' },
        { id: 'motion', label: 'Motion Wave Gesture Sensor' },
        { id: 'baffle', label: 'Stainless Steel Baffle Filters' }
      ]
    },
    'irons': {
      title: "Find Your Dry Iron in 3 Steps",
      sub: "Effortless crease removal with uniform thermal heating and non-stick soleplates.",
      usages: [
        { id: 'light-daily', label: 'Quick Everyday Pressing', desc: '750W lightweight iron with non-stick Teflon soleplate' },
        { id: 'quick-press', label: 'High Heat Rapid Pressing', desc: '1000W Steelo iron with adjustable fabric thermostat' },
        { id: 'heavy-weight', label: 'Crisp Traditional Pressing', desc: '1200W Icon heavy weight metal body iron' }
      ],
      features: [
        { id: 'all', label: 'All Iron Types' },
        { id: 'heavy', label: 'Heavy Weight Metal Press' },
        { id: 'teflon', label: 'Teflon Non-Stick Base' },
        { id: 'thermostat', label: 'Precision Fabric Thermostat' }
      ]
    },
    'madhani': {
      title: "Find Your Electric Madhani & Madhani Mini in 3 Steps",
      sub: "Traditional butter and lassi churning from compact 75W Mini to 200W Commercial motors.",
      usages: [
        { id: 'mini', label: 'Compact Madhani Mini (75W Mini)', desc: '75W Aluminium & Copper winding with 2-way percolation blade for small families' },
        { id: 'domestic', label: 'Standard Home Madhani (125W–170W)', desc: '125W–170W double ball bearing motor with heavy wooden chakla' },
        { id: 'industrial', label: 'Commercial Madhana (175W–200W)', desc: '175W–200W heavy aluminium body & commercial double bearing for halwais & dairies' }
      ],
      features: [
        { id: 'all', label: 'All Winding & Body Types' },
        { id: 'mini', label: '75W Mini Series' },
        { id: 'copper', label: '100% Copper Winding' },
        { id: 'aluminium', label: 'Aluminium Double Bearing' },
        { id: 'heavy-duty', label: 'Commercial Heavy Duty' }
      ]
    },
    'kitchen-appliances': {
      title: "Find Kettles & Atta Chakki in 3 Steps",
      sub: "Automated domestic grain milling and rapid water boiling appliances.",
      usages: [
        { id: 'kettle', label: 'Instant Hot Water & Tea (1.5L–1.8L)', desc: '1200W–1500W cordless 360° stainless steel kettles' },
        { id: 'atta-chakki', label: 'Fresh Whole Grain Flour Milling', desc: 'Automated domestic Atta Chakki with traditional stone grinding' }
      ],
      features: [
        { id: 'all', label: 'All Appliances' },
        { id: 'stone', label: 'Traditional Stone Grinding' },
        { id: 'ss-heating', label: 'Concealed Stainless Steel Heating' }
      ]
    }
  };

  const categoryIcons = {
    'water-heaters': `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="3" width="12" height="14" rx="6"/><circle cx="12" cy="10" r="2"/></svg>`,
    'room-heaters': `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 3v6M12 3v6M17 3v6"/><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 15h8"/></svg>`,
    'gas-stoves': `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="8" cy="12" r="2"/><circle cx="16" cy="12" r="2"/></svg>`,
    'induction': `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="12" cy="12" r="4"/></svg>`,
    'fans': `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="2"/><path d="M12 10c0-4 1-7 3-7s2 4-1 6M14 12c4 0 7 1 7 3s-4 2-6-1M12 14c0 4-1 7-3 7s-2-4 1-6M10 12c-4 0-7-1-7-3s4-2 6 1"/></svg>`,
    'mixer-grinders': `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3h6l-1 5h-4zM6 8h12v12a2 2 0 01-2 2H8a2 2 0 01-2-2V8z"/></svg>`,
    'chimneys': `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20V10l8-5 8 5v10"/></svg>`,
    'irons': `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15c0-4 4-7 9-7h7l-2 7z"/><path d="M4 15h16v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3z"/></svg>`,
    'madhani': `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="8" y="3" width="8" height="7" rx="1"/><path d="M12 10v10M9 20h6"/></svg>`,
    'kitchen-appliances': `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="4" width="14" height="16" rx="2"/><path d="M9 9h6M9 13h6"/></svg>`
  };

  const categoryNames = {
    'water-heaters': 'Water Heaters',
    'room-heaters': 'Room Heaters',
    'gas-stoves': 'Gas Stoves',
    'induction': 'Induction',
    'fans': 'Fans & Exhaust',
    'mixer-grinders': 'Mixer Grinders',
    'chimneys': 'Chimneys',
    'irons': 'Dry Irons',
    'madhani': 'Madhani & Mini',
    'kitchen-appliances': 'Kettles & Chakki'
  };

  function getRecommendations() {
    const cat = state.category;
    let list = ALL_PRODUCTS.filter(p => p.cat === cat);

    // Filter by Usage
    if (cat === 'water-heaters') {
      if (state.usage === 'sink-instant') list = list.filter(p => p.capacity === '1L' || p.capacity === '3L');
      else if (state.usage === 'compact-bath') list = list.filter(p => p.capacity === '6L' || p.capacity === '10L');
      else if (state.usage === 'family-medium') list = list.filter(p => p.capacity === '15L');
      else if (state.usage === 'family-large') list = list.filter(p => p.capacity === '25L' || p.capacity === '35L');
      else if (state.usage === 'commercial-heavy') list = list.filter(p => p.capacity === '50L');
    } else if (cat === 'room-heaters') {
      if (state.usage === 'desk-personal') list = list.filter(p => (p.wattage || 0) <= 800);
      else if (state.usage === 'bedroom-silent') list = list.filter(p => p.model.includes('BLOWER') || p.title.includes('Blower') || (p.wattage || 0) === 1000);
      else if (state.usage === 'heat-pillar') list = list.filter(p => p.model.includes('PILLAR') || p.model.includes('RADO') || p.title.includes('Pillar'));
      else if (state.usage === 'hall-large') list = list.filter(p => (p.wattage || 0) >= 2000);
      else if (state.usage === 'gas-hybrid') list = list.filter(p => p.model.includes('GAS') || p.title.includes('Gas'));
    } else if (cat === 'gas-stoves') {
      if (state.usage === '1-burner') list = list.filter(p => p.model.includes('1') || p.title.includes('1 Burner'));
      else if (state.usage === '2-burner') list = list.filter(p => p.model.includes('2') || p.title.includes('2 Burner'));
      else if (state.usage === '3-burner') list = list.filter(p => p.model.includes('3') || p.title.includes('3 Burner'));
      else if (state.usage === '4-burner') list = list.filter(p => p.model.includes('4') || p.title.includes('4 Burner'));
      else if (state.usage === 'commercial') list = list.filter(p => p.model.includes('COMMERCIAL') || p.title.includes('Commercial'));
    } else if (cat === 'induction') {
      if (state.usage === 'daily-home') list = list.filter(p => (p.wattage || 0) <= 2000 && !p.model.includes('TOUCH') && !p.model.includes('INFRARED'));
      else if (state.usage === 'touch-digital') list = list.filter(p => p.model.includes('TOUCH') || p.specs?.some(s => s.toLowerCase().includes('touch')));
      else if (state.usage === 'infrared') list = list.filter(p => p.model.includes('INFRARED') || p.title.includes('Infrared'));
      else if (state.usage === 'commercial') list = list.filter(p => (p.wattage || 0) >= 3500 || p.model.includes('COMMERCIAL'));
    } else if (cat === 'fans') {
      if (state.usage === 'ceiling') list = list.filter(p => p.model.includes('CEILING') || p.title.includes('Ceiling'));
      else if (state.usage === 'table-desk') list = list.filter(p => p.model.includes('TABLE') || p.model.includes('ALL PURPOSE') || p.title.includes('Table'));
      else if (state.usage === 'wall-mount') list = list.filter(p => p.model.includes('WALL') || p.title.includes('Wall'));
      else if (state.usage === 'farratta') list = list.filter(p => p.model.includes('FARRATTA') || p.title.includes('Farratta'));
      else if (state.usage === 'ventilation') list = list.filter(p => p.model.includes('EXHAUST') || p.model.includes('FRESH AIR') || p.title.includes('Exhaust') || p.title.includes('Ventilating'));
    } else if (cat === 'mixer-grinders') {
      if (state.usage === '2-jar') list = list.filter(p => p.model.includes('2') || p.title.includes('2 Jar'));
      else if (state.usage === '3-jar') list = list.filter(p => p.model.includes('3') || p.title.includes('3 Jar') || p.model.includes('DX'));
      else if (state.usage === 'jmg') list = list.filter(p => p.model.includes('JMG') || p.title.includes('JMG') || p.title.includes('Juicer'));
      else if (state.usage === 'heavy-duty') list = list.filter(p => (p.wattage || 0) >= 600);
    } else if (cat === 'chimneys') {
      if (state.usage === '60cm-curved') list = list.filter(p => p.model.includes('60') && !p.model.includes('AC'));
      else if (state.usage === '60cm-autoclean') list = list.filter(p => p.model.includes('60') && p.model.includes('AC'));
      else if (state.usage === '90cm-island') list = list.filter(p => p.model.includes('90'));
    } else if (cat === 'irons') {
      if (state.usage === 'light-daily') list = list.filter(p => (p.wattage || 0) <= 750);
      else if (state.usage === 'quick-press') list = list.filter(p => (p.wattage || 0) === 1000);
      else if (state.usage === 'heavy-weight') list = list.filter(p => (p.wattage || 0) >= 1200 || p.model.includes('ICON'));
    } else if (cat === 'madhani') {
      if (state.usage === 'mini') list = list.filter(p => p.model.includes('MINI') || (p.wattage || 0) <= 75);
      else if (state.usage === 'domestic') list = list.filter(p => !p.model.includes('MINI') && (p.wattage || 0) <= 170);
      else if (state.usage === 'industrial') list = list.filter(p => (p.wattage || 0) >= 175);
    } else if (cat === 'kitchen-appliances') {
      if (state.usage === 'kettle') list = list.filter(p => p.cat === 'kitchen-appliances' && (p.model.includes('KETTLE') || p.title.includes('Kettle')));
      else if (state.usage === 'atta-chakki') list = list.filter(p => p.model.includes('ATTA') || p.title.includes('Atta'));
    }

    // Filter by Feature / Preference (if not 'all')
    if (state.feature && state.feature !== 'all') {
      const featList = list.filter(p => {
        const text = (p.model + ' ' + p.title + ' ' + (p.specs || []).join(' ') + ' ' + (p.elementType || '')).toLowerCase();
        if (state.feature === 'mini') return text.includes('mini') || (p.wattage || 0) <= 75;
        if (state.feature === 'glassline') return text.includes('glass') || text.includes('enamel') || text.includes('vitreous');
        if (state.feature === 'digital') return text.includes('digital') || text.includes('meter') || text.includes('display');
        if (state.feature === 'abs') return text.includes('abs') || text.includes('shock');
        if (state.feature === 'metal') return text.includes('metal') || text.includes('ss');
        if (state.feature === 'carbon') return text.includes('carbon');
        if (state.feature === 'quartz') return text.includes('quartz');
        if (state.feature === 'blower') return text.includes('blower') || text.includes('fan');
        if (state.feature === 'gas') return text.includes('gas');
        if (state.feature === 'touch') return text.includes('touch') || text.includes('sensor');
        if (state.feature === 'push') return text.includes('push') || text.includes('button');
        if (state.feature === 'heavy') return text.includes('heavy') || text.includes('commercial') || (p.wattage || 0) >= 3000;
        if (state.feature === 'copper') return text.includes('copper');
        if (state.feature === 'autoclean') return text.includes('auto clean') || text.includes('auto-clean') || text.includes('ac');
        if (state.feature === 'motion') return text.includes('motion') || text.includes('gesture') || text.includes('sensor');
        if (state.feature === 'stone') return text.includes('stone');
        if (state.feature === 'teflon') return text.includes('teflon') || text.includes('non-stick') || text.includes('coated');
        return true;
      });
      if (featList.length > 0) list = featList;
    }

    // Fallback if list is empty
    if (list.length === 0) {
      list = ALL_PRODUCTS.filter(p => p.cat === cat);
    }

    return list.slice(0, 3);
  }

  function render() {
    const activeData = optionsMap[state.category] || optionsMap['water-heaters'];
    const recs = getRecommendations();

    container.innerHTML = `
      <div class="finder-card">
        <div class="finder-header">
          <span class="eyebrow" style="color:var(--red);font-weight:700">Smart Selection Engine</span>
          <h2>${activeData.title}</h2>
          <p class="text-muted">${activeData.sub}</p>
        </div>

        <!-- Step 1: 10 Appliance Categories Tabs -->
        <div class="finder-step">
          <p class="finder-step__title"><span class="step-num">1</span><span>Select Appliance Category (${Object.keys(optionsMap).length} Categories)</span></p>
          <div class="finder-cat-chips">
            ${Object.keys(optionsMap).map(catKey => `
              <button type="button" class="finder-cat-btn ${state.category === catKey ? 'active' : ''}" data-cat="${catKey}">
                ${categoryIcons[catKey] || ''}
                <span>${categoryNames[catKey] || catKey}</span>
              </button>
            `).join('')}
          </div>
        </div>

        <!-- Step 2: Usage / Capacity Selection -->
        <div class="finder-step">
          <p class="finder-step__title"><span class="step-num">2</span><span>Select Capacity & Room Requirement</span></p>
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

          <!-- Step 2B: Feature Preference Filters -->
          ${activeData.features && activeData.features.length > 1 ? `
            <div style="margin-top:var(--s-4)">
              <p style="font-size:var(--fs-cap);font-weight:700;color:var(--text-3);text-transform:uppercase;letter-spacing:var(--tracking-caps);margin-bottom:var(--s-2)">Optional Tech & Feature Filter:</p>
              <div class="finder-feat-chips">
                ${activeData.features.map(f => `
                  <button type="button" class="finder-feat-btn ${state.feature === f.id ? 'active' : ''}" data-feat="${f.id}">
                    <span>${f.label}</span>
                  </button>
                `).join('')}
              </div>
            </div>
          ` : ''}
        </div>

        <!-- Step 3: Recommended Results -->
        <div class="finder-step" style="border-bottom:none">
          <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--s-2);margin-bottom:var(--s-4)">
            <p class="finder-step__title" style="margin-bottom:0">
              <span class="step-num">3</span>
              <span>Recommended For You (<b style="color:var(--red)">${recs.length} Optimal Matches</b>)</span>
            </p>
            <a href="products.html?cat=${encodeURIComponent(state.category)}" class="btn btn--xs btn--ghost">View All ${categoryNames[state.category]} (${(PRODUCTS_BY_CAT[state.category] || []).length}) &rarr;</a>
          </div>
          <div class="finder-recs-grid">
            ${recs.map(p => renderProductCard(p, { showCompare: true })).join('')}
          </div>
        </div>
      </div>
    `;

    // Make rendered cards immediately visible with reveal animation
    setTimeout(() => {
      container.querySelectorAll('.pcard').forEach(c => c.classList.add('in-view'));
      initReveals();
    }, 20);

    // Attach Category click handlers
    container.querySelectorAll('.finder-cat-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        state.category = btn.dataset.cat;
        state.usage = optionsMap[state.category].usages[0].id;
        state.feature = 'all';
        render();
      });
    });

    // Attach Usage click handlers
    container.querySelectorAll('[data-usage]').forEach(card => {
      card.addEventListener('click', () => {
        state.usage = card.dataset.usage;
        render();
      });
    });

    // Attach Feature click handlers
    container.querySelectorAll('[data-feat]').forEach(btn => {
      btn.addEventListener('click', () => {
        state.feature = btn.dataset.feat;
        render();
      });
    });
  }

  render();
}
