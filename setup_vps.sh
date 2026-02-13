#!/bin/bash
set -e

# --- Configuration ---
DOMAIN="krivia.yourdomain.com"
DB_NAME="krivia"
DB_USER="krivia_user"
DB_PASS="StrongP@ssw0rd!"

echo "🚀 Starting Krivia VPS Setup (Updated for PHP 8.4)..."

# 1. Update & Basic Tools
echo "📦 Updating system..."
sudo apt update && sudo apt upgrade -y
sudo apt install -y git curl zip unzip nginx software-properties-common

# 2. Install PHP 8.4 & Extensions
echo "🐘 Installing PHP 8.4..."
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.4 php8.4-fpm php8.4-mysql php8.4-mbstring php8.4-xml php8.4-bcmath php8.4-curl php8.4-gd php8.4-intl php8.4-zip

# 3. Install Composer
echo "🎵 Installing Composer..."
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
fi

# 4. Install Node.js & NPM
echo "🟢 Installing Node.js..."
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
    sudo apt install -y nodejs
fi

# 5. Install & Configure MySQL
echo "🗄️ Installing MySQL..."
sudo apt install -y mysql-server
sudo mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"
sudo mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
sudo mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# 6. Setup Project Directory
echo "📂 Setting up project directory..."
sudo mkdir -p /var/www/krivia
sudo chown -R $USER:www-data /var/www/krivia
sudo chmod -R 775 /var/www/krivia

# 7. Configure Nginx (Updated for PHP 8.4 FPM)
echo "🌐 Configuring Nginx..."
sudo tee /etc/nginx/sites-available/krivia > /dev/null <<EOF
server {
    listen 80;
    server_name $DOMAIN;
    root /var/www/krivia/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

sudo ln -sf /etc/nginx/sites-available/krivia /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx

echo "✅ Setup Complete (PHP 8.4 Installed)! Now run './deploy.sh' again."
