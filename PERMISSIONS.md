# Permission & .htaccess Setup (Fix 403 / 500 Errors)

This doc explains common permission issues when running the WordPress site locally and how they were fixed.

---

## What Was Fixed in `.htaccess`

1. **Require all granted**  
   The root `.htaccess` now includes:
   - `Require all granted` (Apache 2.4+) so the directory is not denied by a restrictive parent config (fixes **403 Forbidden**).
   - Fallback `Order allow,deny` / `Allow from all` for older Apache.

2. **RewriteBase and paths**  
   The file is set up for **document-root** installs (e.g. `http://legaldivorcedocs.local` with VirtualHost `DocumentRoot` = this project folder):
   - `RewriteBase /`
   - EPC and WordPress rules use `/wp-content/...` and `/index.php` (no `legaldivorcedocs` in the path).

3. **.well-known**  
   `.well-known/.htaccess` already has `Require all granted` so ACME and other `.well-known` requests are allowed.

---

## If You Use a Subfolder (e.g. `http://legaldivorcedocs.local/legaldivorcedocs`)

Edit the root `.htaccess` and set:

- **RewriteBase** (in both NFD EPC and WordPress blocks) to:  
  `RewriteBase /legaldivorcedocs/`
- In the NFD EPC block:
  - First rule:  
    `RewriteRule ^legaldivorcedocs/wp-content/endurance-page-cache/ - [L]`
  - RewriteCond file check:  
    `%{DOCUMENT_ROOT}/legaldivorcedocs/wp-content/endurance-page-cache/$1/_index.html`
  - RewriteRule target:  
    `RewriteRule ^(.*)$ /legaldivorcedocs/wp-content/endurance-page-cache/$1/_index.html [L]`
- In the WordPress block:  
  `RewriteRule . /legaldivorcedocs/index.php [L]`

---

## Apache Configuration (Required for .htaccess to Work)

`.htaccess` is only read if Apache allows it. Use one of these:

### Option A: VirtualHost (recommended for local)

Create `/etc/apache2/sites-available/legaldivorcedocs.local.conf`:

```apache
<VirtualHost *:80>
    ServerName legaldivorcedocs.local
    DocumentRoot /home/connect2abdulaziz/Desktop/Personal/Business/Azquickdivoirce-Wordpress/legaldivorcedocs

    <Directory /home/connect2abdulaziz/Desktop/Personal/Business/Azquickdivoirce-Wordpress/legaldivorcedocs>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/legaldivorcedocs-error.log
    CustomLog ${APACHE_LOG_DIR}/legaldivorcedocs-access.log combined
</VirtualHost>
```

Then:

```bash
sudo a2ensite legaldivorcedocs.local.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Add to `/etc/hosts`:

```
127.0.0.1 legaldivorcedocs.local
```

- **AllowOverride All** — required so `.htaccess` (and thus `Require all granted` and rewrite rules) is applied. Without it you get 403 or broken permalinks.
- **Require all granted** — required so the Directory is not denied (avoids 403).

### Option B: XAMPP / default document root

If the site is under `htdocs/legaldivorcedocs`:

1. Ensure the parent `<Directory>` for `htdocs` (or the server root) has **AllowOverride All** (in XAMPP it often does).
2. Use the **subfolder** version of `.htaccess` (see “If You Use a Subfolder” above).

---

## File Permissions (Linux / WSL)

Suggested permissions so the web server can read files and write where needed:

```bash
# From the project root (legaldivorcedocs)

# Directories: 755
find . -type d -exec chmod 755 {} \;

# Files: 644
find . -type f -exec chmod 644 {} \;

# wp-config.php: 640 (optional, stricter)
chmod 640 wp-config.php

# wp-content (uploads, cache): writable by web server
# If your web server user is www-data:
sudo chown -R www-data:www-data wp-content
chmod -R 755 wp-content
find wp-content -type f -exec chmod 644 {} \;
```

If you run PHP as your own user (e.g. `php -S` or your user = web user), you don’t need to change ownership of `wp-content`; just ensure directories are 755 and files 644.

---

## Quick Checklist

| Issue | Fix |
|-------|-----|
| 403 Forbidden | `.htaccess` has `Require all granted`; Apache `<Directory>` has `AllowOverride All` and `Require all granted`. |
| Permalinks 404 / broken | `a2enmod rewrite`; restart Apache; `RewriteBase` in `.htaccess` matches your URL path (e.g. `/` for vhost, `/legaldivorcedocs/` for subfolder). |
| .htaccess ignored | Set `AllowOverride All` for this site’s Directory. |
| Uploads / cache fail | `wp-content` (and subdirs) writable by web server (755 dirs, 644 files; owner = web server if needed). |

---

## Summary

- **Root `.htaccess`** was updated with `Require all granted` and document-root–friendly `RewriteBase` and paths.
- **Apache** must have `AllowOverride All` and `Require all granted` for the site’s Directory.
- For **subfolder** installs, adjust `RewriteBase` and paths in `.htaccess` as in “If You Use a Subfolder” above.
- Use the file permission commands above if you see read/write or “failed to write” errors.
