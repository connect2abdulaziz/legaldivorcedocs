# Local Development Setup for Legal Divorce Docs

## Prerequisites
- LAMP stack (Linux, Apache, MySQL, PHP)
- Git

## Database Setup

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