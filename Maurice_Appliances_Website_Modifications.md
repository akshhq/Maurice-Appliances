# Maurice Appliances — Comprehensive Website Redesign & Enhancement Plan

**Target Website:** [https://www.mauriceappliances.in/](https://www.mauriceappliances.in/)  
**Document Purpose:** Complete technical and UI/UX blueprint extracting the best-performing architecture, layout patterns, and lead funnels from industry reference sites (*Havells, LG, Haier, Morphy Richards, Midea, Bajaj Electronics*).

---

## 1. Executive Summary & Strategy Matrix

Maurice Appliances operates both as a consumer discovery platform and a high-volume B2B/dealer channel with over 118 products across 11 categories. To elevate the brand from a standard catalog to an authoritative, high-converting digital platform, we blend the strengths of the reference leaders:

| Reference Brand | Extracted Architectural Strength | Implementation in Maurice Appliances |
| :--- | :--- | :--- |
| **Havells** | High-authority Mega-Menu & Dealer Locator | Visual Category Mega-Menu + Pincode/State Dealer Locator |
| **Morphy Richards** | Lifestyle-led Hero & Minimalist Feature Cards | In-context room photography + Icon-based USP Pills |
| **LG** | Sticky Spec Bar & Multi-Product Comparison Drawer | Tabbed Technical Specs (`Overview`, `Specs`, `Manuals`, `Warranty`) + Compare Tool |
| **Haier** | Faceted Catalog Filters & Certification Badges | Sidebar Filter by Wattage, Capacity, Star Rating, ISI Badges |
| **Midea** | Integrated After-Sales Hub & Dual Inquiry CTAs | 4-Card Service Portal + Dual Action Buttons (`Download Spec Sheet`, `Dealer Inquiry`) |
| **Bajaj Electronics** | Quick Category Launchers & High Clarity Pricing | Horizontal Scrollable Pill Chips on Hero + Clear MRP & Warranty Callouts |

---

## 2. Global Header & Navigation Modifications

### Top Utility Bar
* **Left:** `ISI & ISO 9001:2015 Certified Indian Manufacturer | Kullu (HP) & Bawana (Delhi)`
* **Right:** 
  * Toll-Free Support: `1800 547 2505`
  * Direct Links: `Dealer Portal` | `Warranty Registration` | `Download Full Catalogue (PDF)`

### Main Navigation Bar
* **Brand Logo:** High-res SVG with tagline *"Bright Ideas For Beautiful Homes"*.
* **Mega-Menu ("Products"):**
  * **Column 1 (Heating Solutions):** Water Heaters (Storage, Instant, Gas, Solar), Room Heaters (Quartz Column, Fan Heaters, Oil Filled Radiators, Halogen).
  * **Column 2 (Kitchen & Cooking):** Gas Stoves, Induction & Infrared Cooktops, Chimneys, Mixer Grinders, Madhani.
  * **Column 3 (Cooling & Utility):** Ceiling Fans, Ventilation Fans, Coolers & AC (Coming Soon), Dry & Steam Irons.
  * **Column 4 (Featured Highlight Box):** Dynamic promo card (e.g., *Featured: Lava Digital Glassline 25L with 5-year Tank Warranty* + Direct Link).
* **Primary CTAs:**
  * Secondary Button: `Locate Dealer`
  * High-Contrast Primary Button: `Become a Dealer / Bulk Inquiry`

---

## 3. Homepage Section-by-Section Wireframe & Modifications

```
+-----------------------------------------------------------------------------------+
|  1. HERO BANNER: Dynamic Lifestyle Slider (Morphy Richards & Midea aesthetic)     |
|     - High-res lifestyle setting (living room / modern Indian kitchen)            |
|     - Dynamic headline: "Engineered for Indian Homes. Built to Last."             |
|     - Dual CTAs: [ Explore Range ]   [ Dealer & Distribution Inquiries ]          |
+-----------------------------------------------------------------------------------+
|  2. QUICK-ACCESS CATEGORY PILL CHIPS (Bajaj Electronics pattern)                  |
|     - [ Room Heaters ] [ Geysers ] [ Fans ] [ Induction ] [ Mixer Grinders ] ... |
+-----------------------------------------------------------------------------------+
|  3. FLAGSHIP PRODUCT SHOWCASE WITH CATEGORY TABS (Haier style)                    |
|     - Tabs: [ Winter Care ] [ Kitchen Essentials ] [ Fans & Cooling ]             |
|     - Product Cards with Specs, MRP, ISI Tag, and Quick Inquire Drawer            |
+-----------------------------------------------------------------------------------+
|  4. INTERACTIVE PRODUCT SELECTOR / FINDER (LG style)                              |
|     - "Find the Right Geyser / Room Heater in 3 Steps"                            |
|     - Step 1: Select Family/Room Size -> Step 2: Power Preference -> Recommended  |
+-----------------------------------------------------------------------------------+
|  5. BRAND CREDIBILITY & INFRASTRUCTURE COUNTER (Havells authority)               |
|     - 118+ ISI Certified Products | 9,600+ Units Supplied to HP Govt              |
|     - 2 Manufacturing Units (Kullu & Delhi) | ISO 9001:2015 Registered Labs       |
+-----------------------------------------------------------------------------------+
|  6. B2B / DEALER ONBOARDING ACCELERATOR                                           |
|     - Value Proposition: High margins, OEM capability, dedicated territory support|
|     - 3-Field Express Form: [ Name ] [ City/State ] [ Phone ] -> Submit           |
+-----------------------------------------------------------------------------------+
|  7. CUSTOMER SERVICE & WARRANTY HUB (Midea pattern)                               |
|     - [ Register Warranty ] [ Book Service Call ] [ Download Manuals ] [ FAQs ]  |
+-----------------------------------------------------------------------------------+
|  8. MODERN FOOTER: Complete Sitemaps, Compliance, and Social Proof                |
+-----------------------------------------------------------------------------------+
```

---

## 4. Product Catalog / Listing Page (PLP) Overhaul

### Sidebar Faceted Filters (Haier & LG Inspired)
Implement instant client-side or AJAX multi-filtering:
1. **Category:** Water Heaters, Room Heaters, Fans, Induction, etc.
2. **Capacity / Size:** 1L, 3L, 10L, 15L, 25L, 35L, 50L.
3. **Power / Wattage:** 500W, 1000W, 1500W, 2000W, 3000W.
4. **Heating / Element Type:** Quartz Tube, Carbon Fibre, Glasslined Tank, Copper Element.
5. **Certification & Warranty:** ISI Certified, 1-Year, 2-Year, 5-Year Warranty.

### Upgraded Product Card Component
* **Thumbnail Area:** High-res transparent PNG with hover image toggle (front view / side view / application view).
* **Badges (Top Left/Right):** `ISI Certified`, `5-Year Tank Warranty`, `Best Seller`.
* **Title & Sub-heading:** Clear Model Name + Capacity + Form Factor.
* **Key Spec Bullet Pills:** 
  * `100% Copper Element`
  * `Thermal Cut-off Safety`
  * `High Density PUF Insulation`
* **Price & Action Area:**
  * Strikethrough MRP + Display Price.
  * Primary Action: `View Specifications`
  * Secondary Action: `Inquire Dealer Price` (Opens modal prefilled with product model).

---

## 5. Product Details Page (PDP) Architecture

Follow the high-retention structure modeled by **LG** and **Morphy Richards**:

### 1. Above the Fold
* **Left Column:** Interactive Media Gallery (Main high-res image, 360° product rotator or multi-angle thumbs, exploded view of internal parts e.g. heating coils).
* **Right Column:**
  * Product Title, Model Code, BIS/ISI License Number.
  * Key USP Bullet Points with Micro-icons.
  * Wattage & Capacity Variant Switchers (e.g. toggle between `15L` and `25L` dynamically updating specs and price).
  * Direct Actions:
    * `[ Download Technical Spec Sheet (PDF) ]`
    * `[ Dealer & Bulk Price Inquiry ]`
    * `[ Locate Nearest Retailer ]`

### 2. Sticky Secondary Navigation Bar
As the user scrolls down, a persistent top bar appears with tabs:
* `Overview` | `Full Specifications` | `Key Features` | `Installation & Dimensions` | `Warranty & Service` | `Downloads`

### 3. Comprehensive Technical Spec Table (LG style)
Clean alternating two-column specification table:
* Electrical Ratings (Voltage, Frequency, Wattage)
* Physical Dimensions & Weight
* Tank Construction & Coating
* Safety Mechanisms (Thermostat, Thermal Cutout, Multi-function Valve)
* Included In Box Accessories

### 4. Interactive Side-by-Side Comparison Module
Allow users to compare the currently viewed model with 2 adjacent variants (e.g., comparing *Lava 15L*, *Lava 25L*, and *Lava Digital 25L*) across capacity, warranty, dimensions, and heating time.

---

## 6. B2B & Dealer Ecosystem Upgrades (Havells / Midea)

To maximize dealer onboarding and streamline regional distribution:

1. **Interactive Dealer Locator:**
   * Dropdown filters by **State** and **District/City**, or instant **Pincode Lookup**.
   * Results display authorized distributor name, address, contact phone, and Google Maps directions link.
2. **Dealer Benefits Section:**
   * Highlight key dealer advantages: Direct factory pricing from Kullu & Bawana units, verified ISI test certifications, POS marketing materials, and regional service backstops.
3. **Quick Digital Catalogue Download:**
   * Download single category leaflets or the complete 2026 Master Product Catalog in exchange for verified phone/email capture.

---

## 7. Performance, SEO, and Technical Stack Recommendations

* **Image Optimization:** Convert all product and lifestyle assets to modern `.webp` formats with responsive `<picture>` tags and lazy loading (`loading="lazy"`).
* **Structured Data / Schema Markup:**
  * `Product` and `AggregateRating` Schema for every SKU to secure rich snippets on Google Search.
  * `LocalBusiness` / `Manufacturer` Schema specifying manufacturing locations in Kullu and Delhi.
  * `FAQPage` Schema for warranty and installation queries.
* **Speed & Core Web Vitals:**
  * Inline critical CSS for header and hero.
  * Defer non-critical scripts and modal drawers.
  * Ensure touch targets on mobile filter chips are at least 48px high.
* **Lead Capture & CRM Webhooks:**
  * Connect inquiry and warranty registration forms directly to Google Sheets / CRM / WhatsApp Business API for instant sales team notification.

---

## 8. Implementation Checklist & Phase Rollout

- [ ] **Phase 1: Brand & Layout Foundation**
  - [ ] Implement new Mega-Menu with 11 product categories.
  - [ ] Deploy Morphy Richards-style lifestyle Hero Banner and quick-category pills.
- [ ] **Phase 2: Product Catalog & Search Enhancements**
  - [ ] Build faceted filter sidebar (wattage, capacity, warranty) on product archives.
  - [ ] Integrate standardized product card badges (ISI, BIS, Warranty).
- [ ] **Phase 3: Product Detail Page & Spec Engine**
  - [ ] Deploy LG-style sticky spec tab bar and downloadable PDF spec sheets.
  - [ ] Add variant selector (Capacity / Color / Wattage) on PDPs.
- [ ] **Phase 4: B2B Lead Funnels & Support Center**
  - [ ] Create State/Pincode Dealer Locator.
  - [ ] Build 4-Card Service & Warranty Portal (Midea style).
