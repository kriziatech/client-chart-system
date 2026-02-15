#!/bin/bash
set -e

echo "🚀 Starting Docker Deployment..."

# 1. Pull latest code
echo "⬇️ Pulling latest changes from git..."
git pull origin main

# 2. Build and Restart Containers
echo "🏗️ Building and starting containers..."
docker-compose up -d --build

# 3. Running Laravel Commands inside the container
echo "🐘 Running backend maintenance..."
docker-compose exec -T app php artisan migrate --force
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache
docker-compose exec -T app php artisan storage:link || true

# 4. Success message
echo "✅ Deployment Successful!"
echo "📡 Access your app at: http://your-server-ip:8081"
