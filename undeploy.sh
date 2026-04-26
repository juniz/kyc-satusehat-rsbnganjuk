#!/bin/bash

# SatuSehat Laravel Application Undeployment Script

set -e

echo "🛑 Menghentikan deployment SatuSehat Laravel Application..."

# Stop and remove containers
echo "🔄 Menghentikan containers..."
docker-compose -f docker-compose.prod.yml down

# Remove images
echo "🗑️  Menghapus Docker images..."
docker rmi satusehat-laravel:latest 2>/dev/null || echo "Image tidak ditemukan"

# Clean up volumes (optional)
read -p "Apakah Anda ingin menghapus volume data? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🧹 Membersihkan volume data..."
    docker volume prune -f
fi

# Clean up networks (optional)
read -p "Apakah Anda ingin menghapus network yang tidak digunakan? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🌐 Membersihkan network..."
    docker network prune -f
fi

echo "✅ Undeployment selesai!" 