# Maurice Appliances — Design System & Visual Authority

This document formalizes the visual and interaction standards of the Maurice Appliances web platform.

---

## 1. Brand Identity & POV

Maurice is an Indian manufacturer of electrical appliances (heating solutions, cooking, cooling, and utility) founded in 2010 with dual manufacturing plants in Kullu (Himachal Pradesh) and Bawana (Delhi). The brand aesthetic balances **industrial reliability, BIS (ISI) safety assurance**, and **modern Indian home aesthetics** inspired by premium reference standards (Havells, LG, Haier, Midea).

---

## 2. Color System

### Primary & Accent Palette
- **Maurice Red (`--red`)**: `#E01E26` — Primary brand color, primary CTAs, key accent elements, active states.
- **Deep Red (`--red-deep`)**: `#B3161C` — Button hover layers, destructive/error accents.
- **Ember (`--ember`)**: `#FF6A3D` — Secondary radiant warmth accent, gradient transitions, badges.
- **Ember Deep (`--ember-deep`)**: `#D95228` — High-contrast text on light tinted badges.

### Neutral Palette
- **Ink (`--ink`)**: `#131416` — Deepest charcoal/black for primary display headings, top utility bar, and footer.
- **Graphite (`--graphite`)**: `#2E3036` — Secondary text, dark cards, and toast backgrounds.
- **Slate (`--slate`)**: `#4A4F58` — Subtitles and secondary body text.
- **Silver (`--silver`)**: `#8B9099` — Borders, muted labels, icon strokes.
- **Fog (`--fog`)**: `#C4C8CE` — Scrollbars, inactive radio buttons.
- **Mist (`--mist`)**: `#ECEEF1` — Neutral badge backgrounds, secondary card fills.
- **Paper (`--paper`)**: `#F8F9FA` — Main background tint.
- **White (`--white`)**: `#FFFFFF` — Elevated cards, navbar surface, inputs.

### Gradients & Elevation
- **Ember Glow (`--ember-glow`)**: `radial-gradient(circle, #FF6A3D 0%, #E01E26 55%, transparent 72%)`
- **Ember Line (`--ember-line`)**: `linear-gradient(90deg, var(--ink) 0%, var(--ember) 55%, var(--red) 100%)`
- **Hero Ambient (`--hero-bg`)**: Multi-stop atmospheric glow with soft warmth.

---

## 3. Typography Hierarchy

- **Display & Headings**: `Clash Display` (600, 700) + fallback `Space Grotesk`, `system-ui`, `sans-serif`
- **Body & Controls**: `Inter` (400, 500, 600, 700) + fallback `-apple-system`, `Segoe UI`, `sans-serif`

| Scale Token | Fluid Size | Usage |
| :--- | :--- | :--- |
| `--fs-display` | `clamp(3.2rem, 1.4rem + 7vw, 6.5rem)` | Homepage Hero display headline |
| `--fs-h1` | `clamp(2.4rem, 1.4rem + 3.8vw, 3.8rem)` | Page H1 titles |
| `--fs-h2` | `clamp(1.85rem, 1.3rem + 2.2vw, 2.75rem)` | Major section headings |
| `--fs-h3` | `clamp(1.3rem, 1.05rem + 0.9vw, 1.65rem)` | Card titles, PDP sub-headlines |
| `--fs-h4` | `clamp(1.1rem, 1rem + 0.4vw, 1.3rem)` | Small card headings, drawer labels |
| `--fs-lg` | `clamp(1.1rem, 1rem + 0.4vw, 1.3rem)` | Lead paragraphs |
| `--fs-body` | `clamp(1rem, 0.96rem + 0.2vw, 1.1rem)` | Standard body text |
| `--fs-sm` | `0.9375rem` (15px) | Secondary descriptions, filter options |
| `--fs-xs` | `0.8125rem` (13px) | Metadata, tag labels, small buttons |
| `--fs-cap` | `0.75rem` (12px) | Uppercase category kickers, badge text |

---

## 4. Spacing, Radii & Depth

### Radius Tokens
- `--r-xs`: `6px` (Checkboxes, small tags)
- `--r-sm`: `10px` (Inputs, filter pills, dropdowns)
- `--r`: `16px` (Standard cards, FAQ items)
- `--r-lg`: `24px` (Featured cards, modals, service hubs)
- `--r-xl`: `32px` (Hero stage, B2B accelerator card)
- `--r-pill`: `999px` (Buttons, search bars, category chips)

### Warm Shadow System
- `--sh-1`: Subtly lifted elements (`0 1px 2px rgba(19,20,22,0.04), 0 2px 6px rgba(19,20,22,0.05)`)
- `--sh-2`: Interactive card resting/hover state (`0 1px 3px rgba(19,20,22,0.05), 0 10px 28px rgba(19,20,22,0.08)`)
- `--sh-3`: Modal dialogs, product card hover (`0 2px 4px rgba(19,20,22,0.06), 0 24px 56px rgba(19,20,22,0.12)`)
- `--sh-4`: Mega menu, full screen overlays (`0 4px 8px rgba(19,20,22,0.07), 0 40px 80px rgba(19,20,22,0.14)`)
- `--sh-ember`: Brand primary glow for red buttons (`0 8px 30px rgba(224,30,38,0.22)`)

---

## 5. Signature Components & Patterns

1. **Top Utility Bar**: Sticky top anchor carrying ISI certifications, toll-free direct dial, and quick links.
2. **Sticky Glass Navbar & 4-Column Mega-Menu**: Multi-category breakdown (Heating, Cooking, Cooling & Utility) + live promotional showcase.
3. **Category Pill Chips**: Horizontal scrollable quick-filters on Homepage and Catalog.
4. **Flagship Showcase Tabs**: Dynamic category switching for high-ticket appliances.
5. **3-Step Product Finder**: Interactive capacity, room-size, and budget selector (LG style).
6. **Product Cards**: Multi-angle view links, compare drawer integration, spec badges, and direct inquiry hooks.
7. **PDP Architecture**:
   - Multi-angle visual stage with thumbnail triggers.
   - Live variant selector (switching capacities & wattage).
   - Sticky secondary navigation bar with section anchors (Overview, Specs, Features, Compare).
   - LG-style comprehensive two-column technical specification matrix.
   - Printable PDF spec sheet styles (`@media print`).
8. **Dealer Locator App**: Real-time multi-facet filter by State, District, and 6-digit Pincode with Google Maps route generator.
9. **Compare Drawer**: Fixed bottom tray supporting up to 4 simultaneous appliances with side-by-side spec comparison table.
