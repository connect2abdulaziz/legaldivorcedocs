#!/usr/bin/env bash
# Fix file/directory permissions for WordPress (run from project root).
# Use: ./fix-permissions.sh   or   bash fix-permissions.sh

set -e
ROOT="$(cd "$(dirname "$0")" && pwd)"
cd "$ROOT"

echo "Fixing permissions in: $ROOT"

# Directories: 755
find . -type d -exec chmod 755 {} \; 2>/dev/null || true

# Files: 644
find . -type f -exec chmod 644 {} \; 2>/dev/null || true

# wp-config.php: 640 (stricter)
[ -f wp-config.php ] && chmod 640 wp-config.php

# .htaccess must be readable by Apache
[ -f .htaccess ] && chmod 644 .htaccess
[ -f .well-known/.htaccess ] && chmod 644 .well-known/.htaccess

echo "Done. Directories=755, files=644, wp-config.php=640."
echo "If uploads/cache still fail, ensure wp-content is writable by your web server user (see PERMISSIONS.md)."
