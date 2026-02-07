# Legal Divorce Docs (WordPress)

WordPress site for **Arizona Quick Divorce** / **Legal Divorce Docs** — streamlined, AI-oriented divorce assistance and document access.

---

## Project overview

### What it is
- **Purpose:** Marketing and service site for divorce assistance: “Streamline your divorce process” with AI-driven solutions, quick access to legal documents, and case support.
- **Branding:** “AZ Arizona Quick Divorce” with logo and primary CTA: **Get Your Divorce Started Today!**
- **Tech angle:** Site content references **Integromat / Make.com**–style workflow automation as part of the value proposition (e.g. connecting apps and automating steps).

### Implemented functionality
- **Pages (from your content):**
  - **Home** — Hero (“Streamline Your Divorce Process”), value proposition (AI-driven, documents, support), and Integromat/Make.com–related section.
  - **About**, **Contact**, **Services**, **Testimonials**, **Home-venture**, **Efficient Divorce Assistance** (or similar page).
- **Navigation:** Header with logo, page menu (About, Contact, Home, Home-venture, Services, Testimonials), and prominent CTA button.
- **Theme:** Full Site Editing (FSE) block theme — all layout and copy are block-based (stored in the database), not hard-coded in theme files.
- **Hosting/stack:** Built for Bluehost/Newfold (Endurance Page Cache, Jetpack WAF, .htaccess rules for cache/WebP/Cloudflare-style opts). Works locally with XAMPP after config and URL updates.

### Structure (codebase)
- **WordPress:** Core in repo root; table prefix `FVp_`.
- **Theme:** `wp-content/themes/bluehost-blueprint/`
  - Block-based FSE theme (templates: `*.html`; parts: header, footer variants; patterns: home, headers, footers, blog, 404, search, single post, WooCommerce).
  - Custom block styles (overlap, creative layouts), color/typography presets, sticky footer, form styles.
  - **WooCommerce-ready:** Templates and patterns for cart, checkout, single product, product archives, taxonomies — theme supports e‑commerce if WooCommerce plugin is installed.
- **Plugins (in repo):** Only **Hello Dolly** under `wp-content/plugins/`. WooCommerce and others (if any) come from the database/site setup, not from this repo.
- **Must-use plugins:** `wp-content/mu-plugins/` — Endurance Page Cache, SSO, WooCommerce analytics proxy (hosting-related).
- **Content:** All page content (hero, sections, Integromat block, CTAs) lives in the **database** (posts/pages and block markup), not in theme PHP/HTML files.
- **Assets:** Logo and uploads under `wp-content/uploads/` (e.g. `logo-azqd.webp`); theme fonts in `themes/bluehost-blueprint/assets/fonts/`.

### Summary
- **Done:** Full marketing site with Home, About, Contact, Services, Testimonials, and related pages; FSE theme with multiple headers/footers and patterns; WooCommerce-ready theme; caching and security setup for production hosting.
- **Content:** Fully editable in WordPress (block editor); no custom plugin code for the main marketing content — theme + DB content deliver the current functionality and structure.

---

## AZ MVP — Phase 1: Architecture & Security Foundation

Phase 1 goal: *Build a stable, secure base before feature velocity.*

### Done already
| Deliverable | Status | Notes |
|-------------|--------|--------|
| **WordPress dev setup** | ✅ | Local (XAMPP + Linux LAMP) works; repo has WordPress core, theme, mu-plugins. |
| **Case Engine plugin** | ✅ | Intake flow, case factory, client dashboard in `wp-content/plugins/case-engine/`. |
| **Intake flow (12 screens)** | ✅ | Uncontested gating, progress bar, validation, session storage in `az_intake_sessions`. Shortcode `[az_intake]`. |
| **Post-intake redirect** | ✅ | Payment placeholder → Client Dashboard page (auto-created). Shortcode `[az_client_dashboard]`. |
| **Intake data storage** | ✅ | `az_intake_sessions` (session_key, status, answers JSON, case_id). |
| **Core data models** | ✅ | All 7 tables created on activation: **az_cases**, **az_parties**, **az_intake_answers**, **az_document_states**, **az_payments**, **az_audit_logs** (+ az_intake_sessions). Used by `Case_Engine_Case_Factory` (create_from_session, mark_case_paid, audit log). See `DATA-MODELS.md`. |
| **Bilingual (EN/ES)** | ✅ | **GTranslate** plugin: language selector in header (top right); site content translates to EN/ES for visitors. No Polylang/WPML needed for user-facing translation. |
| **Role-based access control (RBAC)** | ✅ | Roles: **Administrator**, **Case Manager**, **Client**. Capabilities: `case_engine_view_sessions`, `create_case_from_session`, `view_cases`, `edit_cases`, `view_own_cases`, `manage_settings`. Enforced: admin menu (VIEW_SESSIONS), admin page load and “Create case” (CREATE_CASE_FROM_SESSION), client dashboard (VIEW_OWN_CASES). See `RBAC.md` and `class-case-engine-rbac.php`. |

### Not done yet (Phase 1 to‑do)
| # | Deliverable | What to do |
|---|-------------|------------|
| 1 | **WordPress staging/production** | Define staging/production (DB, URLs, `WP_ENVIRONMENT_TYPE`). Document deploy. |
| 2 | **Secure secrets** | Move DB/API keys out of `wp-config.php` into `.env` or server env; load in config. |

### Intake flow (12 screens, uncontested gating)
The **Case Engine** plugin includes the full intake flow. **Where it lives and what it affects** is documented in `wp-content/plugins/case-engine/INTAKE-SCREENS.md`.

**How to use it:**
1. Activate the **Case Engine (AZ Divorce)** plugin (Plugins → Case Engine → Activate).
2. Create a new **Page** (e.g. “Intake” or “Start your divorce”).
3. Add the shortcode **`[az_intake]`** in the page content.
4. Publish and open the page (e.g. `/intake/`). Users see the 12-screen flow; gates stop immediately on any contested signal.

**Principle:** Eligibility first → details later → payment before documents. Sensitive info is only collected after uncontested gates pass; payment (Screen 11) sets case state to PAID and unlocks document generation.

### What’s done vs what’s left (all phases)

**Done now**
- **Phase 1:** Local WordPress setup, Case Engine plugin, intake flow (12 screens, `[az_intake]`), post-intake redirect to Client Dashboard (`[az_client_dashboard]`), **core data models** (az_cases, az_parties, az_intake_answers, az_document_states, az_payments, az_audit_logs), **RBAC** (Admin / Case Manager / Client roles and caps enforced on admin menu, admin page, “Create case”, client dashboard).
- **Phase 2 (partial):** Intake creates case via Case Factory (Review → create_from_session; Payment → mark_case_paid). No intake versioning or full case lifecycle UI yet.

**Left to do**
- **Phase 1:** Staging/production, secrets in env.
- **Phase 2:** Bilingual intake, intake versioning, create real Case from intake, case lifecycle (Draft → Paid → Generated → Finalized).
- **Phase 3:** Stripe Checkout, webhooks, payment-gated document access, payment → case state.
- **Phase 4:** PDF automation (AcroForm, pdftk, FPDI, packets, hashing, admin kill switch).
- **Phase 5:** Full client dashboard (status, downloads, uploads, payment history), Twilio SMS, notifications.
- **Phase 6:** Security hardening, field-level encryption, audit validation, QA, launch.

---

## Prerequisites
- LAMP stack (Linux, Apache, MySQL, PHP) — or **XAMPP on Windows**
- Git

---

## Setup on Windows (XAMPP)

If you pulled this repo and use **XAMPP** on Windows, follow these steps.

### 1. Start XAMPP
- Open **XAMPP Control Panel** and start **Apache** and **MySQL**.

### 2. Create the database and user
- Open **http://localhost/phpmyadmin**
- Click **User accounts** → **Add user**:
  - User name: `wpuser`
  - Host: `localhost`
  - Password: `MySQL@123` (or choose one and update `wp-config.php` later)
  - Check **Create database with same name and grant all privileges** — or create the database separately:
- Click **Databases** → **New** → Database name: `legaldivorcedocs_local` → Create.

If you use the SQL tab or CLI instead:
```sql
CREATE DATABASE legaldivorcedocs_local;
CREATE USER 'wpuser'@'localhost' IDENTIFIED BY 'MySQL@123';
GRANT ALL PRIVILEGES ON legaldivorcedocs_local.* TO 'wpuser'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Import the database (if you have a dump)
- If you have `legaldivorcedocs_for_partner.sql`, in phpMyAdmin select `legaldivorcedocs_local` → **Import** → Choose file → Go.
- Or in PowerShell (from project root):
```powershell
& "C:\xampp\mysql\bin\mysql.exe" -u wpuser -p legaldivorcedocs_local < legaldivorcedocs_for_partner.sql
```
- Then update site URLs (see step 3 in "Database Setup" below; use `http://localhost/legaldivorcedocs` as YOUR_LOCAL_DOMAIN if using the folder in htdocs).

### 4. Create wp-config.php
- `wp-config.php` is not in the repo (it’s in .gitignore). Create it from `wp-config-sample.php` or use the backup:
  - Copy `wp-config-sample.php` to `wp-config.php`, **or** copy `wp-config.php.bak` to `wp-config.php`.
- Edit `wp-config.php`:
  - Set database name, user, password (e.g. `legaldivorcedocs_local`, `wpuser`, `MySQL@123`).
  - Set `$table_prefix = 'FVp_';` (must match the database).
  - After the opening `<?php`, add (for local HTTP):
```php
define('FORCE_SSL_ADMIN', false);
define('FORCE_SSL_LOGIN', false);
$_SERVER['HTTPS'] = 'off';
```

### 5. Open the site in the browser
- If the project is in `C:\xampp\htdocs\legaldivorcedocs`:
  - **http://localhost/legaldivorcedocs**
- If you get “Error establishing a database connection”, check database name, user, password and that MySQL is running.

### 6. If you didn’t import a database
- Visit **http://localhost/legaldivorcedocs** and complete the WordPress installation wizard (use the same database and table prefix `FVp_`).

---

## Database Setup (Linux / same steps)

1. Create database and user:
```sql
mysql -u root -p

CREATE DATABASE legaldivorcedocs_local;
CREATE USER 'wpuser'@'localhost' IDENTIFIED BY 'MySQL@123';
GRANT ALL PRIVILEGES ON legaldivorcedocs_local.* TO 'wpuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

2. Import database:
```bash
mysql -u wpuser -p legaldivorcedocs_local < legaldivorcedocs_for_partner.sql
```

3. Update URLs in database (replace YOUR_LOCAL_DOMAIN):
```sql
mysql -u wpuser -p legaldivorcedocs_local

UPDATE FVp_options SET option_value = 'http://YOUR_LOCAL_DOMAIN' WHERE option_name = 'siteurl';
UPDATE FVp_options SET option_value = 'http://YOUR_LOCAL_DOMAIN' WHERE option_name = 'home';
EXIT;
```

## WordPress Files Setup

1. Extract WordPress files to your web directory

2. Update wp-config.php:
```php
define('DB_NAME', 'legaldivorcedocs_local');
define('DB_USER', 'wpuser');
define('DB_PASSWORD', 'MySQL@123');
define('DB_HOST', 'localhost');

$table_prefix = 'FVp_';
```

3. Add these lines at the top of wp-config.php (after <?php):
```php
define('FORCE_SSL_ADMIN', false);
define('FORCE_SSL_LOGIN', false);
$_SERVER['HTTPS'] = 'off';
```

4. Update .htaccess:
```apache
RewriteBase /
```
(Change from /website_4704f9a4/ to just /)

## Apache Virtual Host

Create: /etc/apache2/sites-available/legaldivorcedocs.local.conf
```apache
<VirtualHost *:80>
    ServerName legaldivorcedocs.local
    DocumentRoot /path/to/your/wordpress
    
    <Directory /path/to/your/wordpress>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/legaldivorcedocs-error.log
    CustomLog ${APACHE_LOG_DIR}/legaldivorcedocs-access.log combined
</VirtualHost>
```

Enable site:
```bash
sudo a2ensite legaldivorcedocs.local.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## /etc/hosts

Add:
```
127.0.0.1 legaldivorcedocs.local
```

## Access

Visit: http://legaldivorcedocs.local

Admin login: (check database for existing users or create new one)