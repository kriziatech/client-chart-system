#!/bin/bash

echo "🚀 Preparing Krivia for Azure Deployment..."

# 1. Clear Caches for clean state
echo "🧹 Clearing local caches..."
php artisan optimize:clear

# 2. Define Output Filename
OUTPUT_FILE="krivia_azure_deploy.zip"

# 3. Create Azure Bridge (index.php at root)
# This acts as a proxy if the server doesn't point to /public
echo "🔧 Creating Azure Root Bridge..."
cat > index.php <<EOF
<?php
/**
 * Azure App Service Bridge
 * Points root requests to the public directory
 */
require_once __DIR__.'/public/index.php';
EOF

# 4. Create .htaccess for Apache (if Azure is using Apache)
cat > .htaccess <<EOF
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteCond %{REQUEST_URI} !^public/
    RewriteRule ^(.*)$ public/\$1 [L]
</IfModule>
EOF

# 5. Create Zip (Excluding huge node_modules and sensitive .env)
echo "📦 Generating Deployment Package: $OUTPUT_FILE"
# Hum 'vendor' ko include kar rahe hain taaki Azure par 'composer install' ka wait na karna pade
zip -r $OUTPUT_FILE . -x \
    "node_modules/*" \
    ".env" \
    ".git/*" \
    ".github/*" \
    "tests/*" \
    "storage/*.log" \
    "phpunit.xml" \
    "README.md" \
    "*.zip" \
    "prepare_deployment.sh"

# Cleanup temporary bridge files after zipping
rm index.php .htaccess

echo "------------------------------------------------"
echo "✅ Done! Package created: $OUTPUT_FILE"
echo "------------------------------------------------"
echo "👉 NEXT STEPS FOR AZURE PORTAL:"
echo "1. Upload '$OUTPUT_FILE' via ZipDeploy or FTP."
echo "2. Azure Settings > Configuration > Application Settings mein ye add karein:"
echo "   - APP_KEY: (Aapka local .env wala key)"
echo "   - APP_NAME: Krivia"
echo "   - DB_CONNECTION, DB_HOST, DB_DATABASE, etc."
echo "3. Azure Settings > Configuration > General Settings > Startup Command mein likhein:"
echo "   cp /home/site/wwwroot/default /etc/nginx/sites-available/default && sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' /etc/nginx/sites-available/default && service nginx reload"
