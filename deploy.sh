#!/bin/bash

# SatuSehat Laravel Application Deployment Script
# Tanpa Database dan Migration

set -e

echo "🚀 Memulai deployment SatuSehat Laravel Application..."

# Check if .env.production exists
if [ ! -f .env.production ]; then
    echo "❌ File .env.production tidak ditemukan!"
    echo "📝 Silakan copy .env.example ke .env.production dan sesuaikan konfigurasinya"
    exit 1
fi

# Copy production environment file
echo "📋 Menggunakan konfigurasi production..."
cp .env.production .env

# Generate application key if not exists
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    docker run --rm -v $(pwd):/var/www/html -w /var/www/html php:8.1-cli php artisan key:generate --no-interaction
fi

# Build and start containers
echo "🏗️  Building Docker image..."
docker-compose -f docker-compose.prod.yml build

echo "🔄 Starting containers..."
docker-compose -f docker-compose.prod.yml up -d

# Wait for container to be ready
echo "⏳ Menunggu container siap..."
sleep 10

# Optimize Laravel for production
echo "⚡ Optimizing Laravel for production..."
docker-compose -f docker-compose.prod.yml exec laravel.app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec laravel.app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec laravel.app php artisan view:cache

# Set proper permissions
echo "🔐 Setting proper permissions..."
docker-compose -f docker-compose.prod.yml exec laravel.app chown -R www-data:www-data /var/www/html/storage
docker-compose -f docker-compose.prod.yml exec laravel.app chown -R www-data:www-data /var/www/html/bootstrap/cache
docker-compose -f docker-compose.prod.yml exec laravel.app chmod -R 775 /var/www/html/storage
docker-compose -f docker-compose.prod.yml exec laravel.app chmod -R 775 /var/www/html/bootstrap/cache

echo "✅ Deployment selesai!"
echo "🌐 Aplikasi berjalan di: http://localhost:$(grep APP_PORT .env | cut -d '=' -f2)"
echo ""
echo "📊 Status container:"
docker-compose -f docker-compose.prod.yml ps
echo ""
echo "📝 Logs container:"
docker-compose -f docker-compose.prod.yml logs laravel.app 