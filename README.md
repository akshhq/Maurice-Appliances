# Maurice Appliances — Official Website

> **BIS (ISI) Certified · ISO 9001:2015**  
> Serving Indian households since 2010

The official marketing and product-catalogue website for **Maurice Appliances**, an Indian home-appliance manufacturer headquartered in Kullu, Himachal Pradesh. Built with pure PHP 8 and vanilla CSS/JS — no framework, no build step, deployable directly to any Apache + PHP 8 shared host.

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Tech Stack](#tech-stack)
3. [Directory Structure](#directory-structure)
4. [Pages & Features](#pages--features)
5. [Product Catalogue](#product-catalogue)
6. [API Endpoints](#api-endpoints)
7. [Integrations](#integrations)
8. [Configuration (.env)](#configuration-env)
9. [Installation](#installation)
10. [HTTPS Setup](#https-setup)
11. [Mail Configuration](#mail-configuration)
12. [Folder Permissions](#folder-permissions)
13. [Security](#security)
14. [Adding / Updating Products](#adding--updating-products)
15. [Diagnostics & Troubleshooting](#diagnostics--troubleshooting)
16. [Company Information](#company-information)
17. [License](#license)

---

## Project Overview

| Detail | Value |
|---|---|
| **Brand** | Maurice Appliances (`maurice®`) |
| **Established** | 2010 (registered brand: 2012) |
| **Live Domain** | www.mauriceappliances.in |
| **Certifications** | BIS (ISI) Certified, ISO 9001:2015 |
| **Products** | 108 products across 11 categories |
| **Language** | PHP 8.0+ |
| **Hosting** | Hostinger (Apache shared hosting) |
| **Timezone** | Asia/Kolkata |
| **Data Store** | JSON flat-file (`database/schema/products.json`) |

The website is a **server-side rendered** PHP application with no external database dependency. All 108 products are stored in a single versioned JSON file and served via PHP helper functions with per-request in-memory caching.

---

## Tech Stack

| Layer | Technology |
|---|---|
| **Server-side** | PHP 8.0+ (strict types throughout) |
| **Templating** | Native PHP partials / sections |
| **Styling** | Vanilla CSS (no framework) |
| **Scripting** | Vanilla JavaScript + GSAP animations |
| **Animations** | GSAP, Lenis (smooth scroll), SplitType, Lottie |
| **Data** | JSON flat-file database |
| **Web server** | Apache + `.htaccess` rewrite rules |
| **Mail** | PHP `mail()` / SMTP (configurable) |
| **PWA** | Web App Manifest + Service Worker |
| **SEO** | Schema.org JSON-LD on every page |

---

## Directory Structure

```
maurice-main-website/
│
├── index.php                    # Homepage (entry point)
├── products.php                 # Full product catalogue
├── product.php                  # Single product detail page
├── category.php                 # Category listing page
├── sitemap.php                  # Dynamic XML sitemap
├── check.php                    # Server diagnostics (delete after install)
│
├── .htaccess                    # Apache rewrites + security rules
├── .env                         # Live settings (not committed)
├── .env.example                 # Settings template
├── manifest.json                # PWA manifest
├── service-worker.js            # PWA service worker
├── robots.txt                   # Crawler rules
├── browserconfig.xml            # Windows tile config
│
├── app/                         # Core PHP application layer
│   ├── bootstrap/               # App bootstrapper (loads env, constants, helpers)
│   ├── config/                  # Runtime config loaders
│   ├── controllers/             # Page controllers
│   ├── functions/               # Data access functions (products, SEO, etc.)
│   ├── helpers/                 # Utility helpers
│   ├── interfaces/              # PHP interfaces
│   ├── libraries/               # Internal libraries
│   ├── mail/                    # Mail sender abstraction (PHP mail / SMTP)
│   ├── middleware/              # Request middleware
│   ├── models/                  # Data models
│   ├── notifications/           # Notification system
│   ├── repositories/            # Data repositories
│   ├── routing/                 # URL routing
│   ├── services/                # Business-logic services
│   ├── storage/                 # Storage abstraction
│   ├── traits/                  # PHP traits
│   └── validation/              # Input validation
│
├── api/                         # REST API
│   ├── v1/                      # Stable API (categories, products, contact, dealer, newsletter)
│   └── v2/                      # Next API version
│
├── assets/                      # Front-end static assets
│   ├── css/                     # Stylesheets
│   ├── js/                      # JavaScript modules
│   ├── images/                  # Images (logos, heroes, products)
│   ├── svg/                     # SVG icons & illustrations
│   ├── fonts/                   # Web fonts
│   ├── lottie/                  # Lottie JSON animations
│   ├── videos/                  # Video assets
│   ├── audio/                   # Audio assets
│   ├── webgl/                   # WebGL assets
│   ├── documents/               # Downloadable documents
│   └── downloads/               # User-facing download files
│
├── config/                      # App configuration files (app.php, database.php, mail.php, etc.)
├── database/                    # Data layer
│   ├── schema/                  # products.json — single source of truth for all products
│   ├── migrations/              # Schema migrations
│   ├── seeders/                 # Data seeders
│   ├── sql/                     # SQL scripts
│   └── backups/                 # Database backups
│
├── company/                     # Company pages (about, journey, manufacturing, vision, values, careers, media)
├── dealers/                     # Dealer network & application pages (dealers, become-dealer)
├── support/                     # Customer support pages (service, warranty, faq, downloads)
├── contact/                     # Contact page
├── legal/                       # Legal & policy pages (privacy, terms)
├── partials/                    # Reusable HTML partials (header, footer, nav)
├── sections/                    # Section-level components (home-hero, home-stats, etc.)
├── layouts/                     # Page layout wrappers (main-header.php, main-footer.php)
├── components/                  # Atomic UI components
├── templates/                   # Email & document templates
│
├── auth/                        # Authentication
├── admin/                       # Admin panel
├── dashboard/                   # Dashboard views
├── portal/                      # Dealer / partner portal
│
├── integrations/                # Third-party integrations
│   ├── analytics/               # Analytics (Google Analytics, etc.)
│   ├── crm/                     # CRM integration
│   ├── google/                  # Google services (Maps, Search Console, etc.)
│   ├── meta/                    # Meta (Facebook) pixel
│   ├── paypal/                  # PayPal gateway
│   ├── razorpay/                # Razorpay gateway
│   ├── stripe/                  # Stripe gateway
│   ├── smtp/                    # SMTP integration
│   └── whatsapp/                # WhatsApp Business API
│
├── includes/                    # Legacy / shared includes
├── scripts/                     # CLI / cron scripts
├── cron/                        # Scheduled cron jobs
│
├── storage/                     # App-generated storage (writable)
├── logs/                        # PHP & app error logs (writable)
├── cache/                       # File cache (writable)
├── uploads/                     # User uploads (writable)
├── tmp/                         # Temporary files (writable)
├── media/                       # Media library
└── errors/                      # Custom error pages (404, 500, etc.)
```

---

## Pages & Features

| Page | URL | Description |
|---|---|---|
| Homepage | `/index.html` | Hero, marquee, categories, stats, featured products, journey timeline, dealer CTA |
| Product Catalogue | `/products.html` | 100+ products with filter, search, sort & compare |
| Product Detail | `/product.html?model={slug}` | Full specs, images, downloads for a single product |
| Category | `/category.html?cat={slug}` | Filtered view of one product category |
| About | `/company/about.html` | Company overview |
| Our Journey | `/company/journey.html` | Brand history timeline (2010 – present) |
| Manufacturing | `/company/manufacturing.html` | Factory & production labs |
| Vision & Mission | `/company/vision.html` | Strategic objectives |
| Core Values | `/company/values.html` | Nine core brand values |
| Careers | `/company/careers.html` | Careers / job application form |
| Media | `/company/media.html` | Press & media resources |
| Dealers | `/dealers/dealers.html` | Dealer network locator |
| Become a Dealer | `/dealers/become-dealer.html` | Dealer application form (live) |
| Service | `/support/service.html` | After-sales service info |
| Warranty | `/support/warranty.html` | Warranty policy & digital registration |
| FAQ | `/support/faq.html` | Frequently asked questions |
| Downloads | `/support/downloads.html` | Product catalogues & spec sheets |
| Contact | `/contact/contact.html` | Contact form & office locations |
| Privacy Policy | `/legal/privacy.html` | Privacy policy |
| Terms of Service | `/legal/terms.html` | Terms and conditions |
| Sitemap | `/sitemap.xml` | Dynamic XML sitemap |
| Diagnostics | `/check.php` | Server health check (**delete after install**) |

All pages are **fully mobile responsive** and include **Schema.org JSON-LD** structured data for SEO.

---

## Product Catalogue

All product data lives in a **single JSON file**:

```
database/schema/products.json
```

- **108 products** across **11 categories**
- Edit the file directly to update products, prices or descriptions
- Changes appear on the site immediately (no rebuild required)
- Keep JSON syntax exact — commas, quotes and brackets must stay intact

### Product Categories

| # | Category | Status |
|---|---|---|
| 1 | Water Heaters (Geysers) | Active |
| 2 | Mixer Grinders & JMG | Active |
| 3 | Room Heaters | Active |
| 4 | Fans (Table, Wall, Pedestal, Ceiling) | Active |
| 5 | Induction Cooktops | Active |
| 6 | Gas Stoves | Active |
| 7 | Electric Chimneys | Active |
| 8 | Irons (Dry & Steam) | Active |
| 9 | Small Kitchen Appliances | Active |
| 10 | Heat Pillars & Blowers | Active |
| 11 | Coolers & AC | Coming Soon |

### Adding Product Photos

Product images currently display as clean line drawings. To switch to real photos:

1. Place photos in `assets/images/products/`
2. Ask your developer to update the `product_frame()` function in `app/functions/products.php`

---

## API Endpoints

All endpoints live under `/api/v1/` and return JSON.

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/products.php` | List / search products |
| `GET` | `/api/v1/categories.php` | List all categories |
| `POST` | `/api/v1/contact.php` | Submit a contact enquiry |
| `POST` | `/api/v1/dealer.php` | Submit a dealer application |
| `POST` | `/api/v1/newsletter.php` | Newsletter sign-up |

A v2 API is scaffolded at `/api/v2/` for future use.

---

## Integrations

| Integration | Folder | Notes |
|---|---|---|
| Analytics | `integrations/analytics/` | Google Analytics / custom analytics |
| CRM | `integrations/crm/` | CRM lead sync |
| Google | `integrations/google/` | Google services (Maps, Search Console, etc.) |
| Meta Pixel | `integrations/meta/` | Facebook / Instagram ad tracking |
| Razorpay | `integrations/razorpay/` | Indian payment gateway |
| PayPal | `integrations/paypal/` | International payment gateway |
| Stripe | `integrations/stripe/` | Card payment gateway |
| WhatsApp | `integrations/whatsapp/` | WhatsApp Business API |
| SMTP | `integrations/smtp/` | Transactional email via SMTP |

---

## Configuration (.env)

Copy `.env.example` to `.env` and adjust as needed.

```dotenv
# ── Your website address ──────────────────────────────────────────
# Leave blank and the site auto-detects its own URL.
# Set this once your final domain is live:
APP_URL=https://www.mauriceappliances.in

# ── Application ───────────────────────────────────────────────────
APP_ENV=production        # production | development
APP_DEBUG=false           # true enables on-screen error output
APP_TIMEZONE=Asia/Kolkata

# ── Contact form recipients ───────────────────────────────────────
MAIL_TO_ENQUIRIES=mauriceappliances@gmail.com
MAIL_TO_DEALERS=mauriceappliances@gmail.com
MAIL_TO_CAREERS=mauriceappliances@gmail.com

# ── Mail driver ───────────────────────────────────────────────────
MAIL_DRIVER=mail          # mail | smtp
MAIL_FROM_ADDRESS=noreply@mauriceappliances.in
MAIL_FROM_NAME="Maurice Appliances"

# SMTP (only when MAIL_DRIVER=smtp)
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=info@yourdomain.com
MAIL_PASSWORD=your-mailbox-password
MAIL_ENCRYPTION=tls

# ── Data ──────────────────────────────────────────────────────────
DB_CONNECTION=json        # flat-file JSON — no SQL database needed

# ── Cache ─────────────────────────────────────────────────────────
CACHE_ENABLED=true
CACHE_DRIVER=file
CACHE_TTL=3600            # seconds
```

> **Note:** `.htaccess` blocks direct HTTP access to `.env`. Never delete `.htaccess`.

---

## Installation

### Requirements

- PHP **8.0** or higher
- Apache with `mod_rewrite` enabled
- No database, no Composer, no Node.js required

### Steps (Hostinger / Apache shared host)

```
1. Log in to hPanel -> File Manager -> open public_html
2. Delete any default index.html or Hostinger placeholder
3. Upload maurice-website.zip into public_html
4. Right-click the zip -> Extract -> extract INTO public_html
5. Delete the zip file
6. hPanel -> Advanced -> PHP Configuration -> set PHP to 8.0+
7. Visit your domain — the site loads immediately
```

> The app auto-detects its own base URL. No configuration is required for the site to run out of the box.

---

## HTTPS Setup

Once your SSL certificate is active:

1. `hPanel -> Security -> SSL` — install / activate free SSL
2. Open `.htaccess` in `public_html`
3. Find and **uncomment** these two lines (remove the leading `#`):

```apache
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
```

4. Save the file

---

## Mail Configuration

### Default (PHP mail)

Works out of the box. Messages are delivered to the address set in `.env -> MAIL_TO_ENQUIRIES`.

### If mail doesn't arrive (SMTP)

1. Create a mailbox in `hPanel -> Emails`
2. Set in `.env`:

```dotenv
MAIL_DRIVER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=info@yourdomain.com
MAIL_PASSWORD=your-mailbox-password
```

---

## Folder Permissions

Hostinger handles permissions automatically in most cases. If contact forms or the newsletter fail to save data, set these folders to **755**:

```
storage/   logs/   cache/   uploads/
```

---

## Security

> **WARNING: Never delete `.htaccess`.** It is the only thing preventing direct HTTP access to application folders and sensitive files.

### After installation, verify these URLs return an error (not file content):

```
yourdomain.com/.env
yourdomain.com/database/schema/products.json
```

If either URL returns file content, **stop immediately** and contact your developer.

### Protected directories (blocked by `.htaccess`)

```
app/   config/   database/   integrations/   scripts/   cron/   storage/   logs/   cache/   tmp/
```

### Delete after installation

```
check.php   — server diagnostics page (must be removed once live)
```

---

## Adding / Updating Products

All products are defined in one place:

```
database/schema/products.json
```

**Editing rules:**
- Keep all commas, quote marks and brackets exactly as they are
- Changes appear on the site immediately — no cache clear needed
- Back up the file before editing

**Field reference (per product):**

| Field | Description |
|---|---|
| `id` | URL slug (unique, lowercase, hyphens) |
| `name` | Product display name |
| `category` | Category slug |
| `motor` / `power` | Motor or wattage spec |
| `price` | MRP in INR |
| `warranty` | Warranty duration |
| `specs` | Key-value specification pairs |
| `image` | Image filename in `assets/images/products/` |

---

## Diagnostics & Troubleshooting

| Problem | Solution |
|---|---|
| **500 error** | Visit `yourdomain.com/check.php` — it reports exactly what is wrong |
| **PHP version error** | `hPanel -> Advanced -> PHP Configuration` -> set to 8.0+ |
| **Contact form not sending** | Switch `MAIL_DRIVER` to `smtp` in `.env` |
| **Blank page with no error** | Enable `APP_DEBUG=true` in `.env` temporarily |
| **Images not loading** | Check `assets/images/products/` path; confirm filenames match `products.json` |
| **Cache stale** | Clear the `cache/` directory contents |
| **Permissions error** | Set `storage/`, `logs/`, `cache/`, `uploads/` to 755 |

---

## Company Information

| Field | Detail |
|---|---|
| **Company** | Maurice Appliances |
| **Founded** | 2010 |
| **Brand Registered** | 2012 |
| **Registered Address** | VPO, Jia Teh. Bhunter, Distt Kullu, Himachal Pradesh - 175 125 |
| **Corporate Office** | 6487 C-6, Vatika Complex, Vasant Kunj, New Delhi - 110 070 |
| **Manufacturing Units** | Bawana, Delhi · Jia, Kullu (Himachal Pradesh) |
| **Certifications** | BIS (ISI) Certified · ISO 9001:2015 |
| **Toll-Free** | 1800 547 2505 |
| **Sales** | +91 98165-91699 · +91 90154-88584 |
| **Email** | mauriceappliances@gmail.com |
| **Info Email** | info@mauriceappliances.com |
| **Website** | www.mauriceappliances.in |

### Brand Milestones

| Year | Milestone |
|---|---|
| 2010 | Founded in Kullu, Himachal Pradesh |
| 2012 | Formally registered `maurice®`; launched Heat Pillar & Water Heaters |
| 2014 | Launched Gas Stoves & Electric Chimneys; ABS body water heaters |
| 2015 | Irons, Mixer Grinders, JMG, Hand Blenders; factories in Delhi & H.P. |
| 2016 | Expanded Room Heater & Ceiling Fan variants |
| 2017 | BIS (ISI) Certification achieved |
| 2018 | Govt. supply — 45,000 Induction Cooktops & 95,000 Heat Pillars (H.P. Govt.) |
| 2019 | ABS Heat Blowers series; Single Rod Heat Pillars |
| 2021 | Electric Kettles (1.5 L & 1.8 L) |
| 2022 | Sandwich Toasters, Atta Chakki, Exhaust & Ventilating Fans |

---

## License

Copyright (c) 2026 Maurice Appliances. All rights reserved.

This software, design, source code, content and assets are the proprietary property of **Maurice Appliances** and are provided solely for the operation of www.mauriceappliances.in.

No part of this project may be reproduced, distributed, sublicensed or used to create derivative works without prior written permission from Maurice Appliances.

Third-party libraries loaded at runtime (GSAP, Lenis, SplitType) remain subject to their own respective licences.

---

*For support, contact **mauriceappliances@gmail.com** or call **+91 98165-91699**.*
