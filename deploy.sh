#!/bin/bash
set -e

# --- Configuration ---
PROJECT_DIR="/var/www/krivia"
REPO_URL="https://github.com/kriziatech/client-chart-system.git"

echo "🚀 Starting Deployment..."

# 1. Update Code from Git
echo "⬇️ Pulling latest code..."
cd $PROJECT_DIR
if [ -d ".git" ]; then
    git pull origin main
else
    git clone $REPO_URL .
fi

# 2. Install Dependencies
echo "📦 Installing backend dependencies..."
composer install --no-dev --optimize-autoloader

echo "🎨 Installing frontend dependencies..."
npm install && npm run build

# 3. Environment & Migrations
echo "🐘 Configuring Environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# 4. Migrate Database
echo "🗄️ Running Migrations..."
php artisan migrate --force

# 5. Optimize Caches
echo "🧹 Clearing and caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Set Permissions
echo "🔒 Fixing Permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 7. Restart Queue Worker (if using supervisor)
# echo "🔄 Restarting Queue..."
# sudo supervisorctl restart krivia-worker:*

echo "✅ Success! Krivia is Live 🚀"
