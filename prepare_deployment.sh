#!/bin/bash

echo "🚀 Preparing project for deployment..."

# 1. Clear Caches
echo "🧹 Clearing caches..."
php artisan optimize:clear

# 2. Define Output Filename
OUTPUT_FILE="project_deploy.zip"

# 2.5 Create Root Redirects for Azure
echo "🔧 Creating root index.php and .htaccess for Azure..."
cat > index.php <<EOF
<?php
\$uri = urldecode(
    parse_url(\$_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
if (\$uri !== '/' && file_exists(__DIR__.'/public'.\$uri)) {
    return false;
}
require_once __DIR__.'/public/index.php';
EOF

EOF

# 3. Create Zip (Excluding node_modules, .env, .git, but INCLUDING vendor)
echo "📦 Zipping files..."
zip -r $OUTPUT_FILE . -x "node_modules/*" ".env" ".git/*" ".github/*" "tests/*" "phpunit.xml" "README.md" "deployment_sop.zip" "deployment_checklist.zip" "prepare_deployment.sh"

# Cleanup
rm index.php .htaccess

echo "✅ Done! Default file created: $OUTPUT_FILE"
echo "📂 Upload '$OUTPUT_FILE' to your server's public_html folder."
