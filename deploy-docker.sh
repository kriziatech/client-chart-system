#!/bin/bash
set -e

echo "🚀 Starting Docker Deployment..."

# 1. Pull latest code
echo "⬇️ Pulling latest changes from git..."
git pull origin main

# 2. Build and Restart Containers
echo "🏗️ Building and starting containers..."
docker-compose up -d --build

# 3. Wait for Database to be ready
echo "⏳ Waiting for database connection (itpm_db)..."
RETRIES=10
until docker-compose exec -T app php artisan db:monitor || [ $RETRIES -eq 0 ]; do
  echo "Retrying database connection... ($RETRIES left)"
  sleep 3
  RETRIES=$((RETRIES-1))
done

if [ $RETRIES -eq 0 ]; then
    echo "❌ Error: Could not connect to database after several attempts."
    exit 1
fi

# 4. Running Laravel Commands inside the container
echo "🐘 Running backend maintenance..."
docker-compose exec -T app php artisan migrate --force
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache
docker-compose exec -T app php artisan storage:link || true

# 5. Success message
echo "✅ Deployment Successful!"
echo "📡 Access your app at: http://your-server-ip:8081"
