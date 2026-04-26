# Deployment Guide - SatuSehat Laravel Application

## Overview

Konfigurasi deployment Docker untuk aplikasi Laravel SatuSehat tanpa service database dan migration. Aplikasi akan menggunakan database eksternal yang sudah ada.

## Prerequisites

- Docker dan Docker Compose terinstall
- Database MySQL eksternal sudah siap
- File `.env.production` sudah dikonfigurasi dengan benar

## File Konfigurasi

### 1. docker-compose.prod.yml

File ini berisi konfigurasi Docker untuk production deployment dengan:

- Service Laravel tanpa database dan Redis
- Konfigurasi environment production
- Health check untuk monitoring
- Volume mapping untuk storage dan cache

### 2. .env.production

Template konfigurasi environment untuk production:

- Konfigurasi database eksternal
- Setting production (APP_ENV=production, APP_DEBUG=false)
- Konfigurasi SatuSehat API
- Setting cache dan session

### 3. deploy.sh

Script otomatis untuk deployment yang melakukan:

- Validasi file konfigurasi
- Generate application key
- Build dan start container
- Optimize Laravel (config, route, view cache)
- Set proper permissions

### 4. undeploy.sh

Script untuk menghentikan deployment dan membersihkan resources.

## Langkah Deployment

### 1. Persiapan

```bash
# Copy template environment
cp .env.example .env.production

# Edit konfigurasi database eksternal
nano .env.production
```

### 2. Konfigurasi Database Eksternal

Pastikan konfigurasi database di `.env.production` sudah benar:

```env
DB_CONNECTION=mysql
DB_HOST=your-external-db-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

### 3. Konfigurasi SatuSehat

Sesuaikan konfigurasi SatuSehat API:

```env
SATUSEHAT_CLIENT_ID=your-client-id
SATUSEHAT_CLIENT_SECRET=your-client-secret
SATUSEHAT_ORGANIZATION_ID=your-organization-id
SATUSEHAT_BASE_URL=https://api-satusehat.kemkes.go.id
```

### 4. Deployment

```bash
# Berikan permission execute pada script
chmod +x deploy.sh undeploy.sh

# Jalankan deployment
./deploy.sh
```

### 5. Verifikasi

```bash
# Cek status container
docker-compose -f docker-compose.prod.yml ps

# Cek logs
docker-compose -f docker-compose.prod.yml logs laravel.app

# Test aplikasi
curl http://localhost/health
```

## Monitoring dan Maintenance

### Cek Status

```bash
# Status container
docker-compose -f docker-compose.prod.yml ps

# Logs real-time
docker-compose -f docker-compose.prod.yml logs -f laravel.app
```

### Update Aplikasi

```bash
# Pull code terbaru
git pull origin main

# Rebuild dan restart
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build
docker-compose -f docker-compose.prod.yml up -d

# Optimize ulang
docker-compose -f docker-compose.prod.yml exec laravel.app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec laravel.app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec laravel.app php artisan view:cache
```

### Undeployment

```bash
./undeploy.sh
```

## Troubleshooting

### Container tidak start

```bash
# Cek logs detail
docker-compose -f docker-compose.prod.yml logs laravel.app

# Cek konfigurasi environment
docker-compose -f docker-compose.prod.yml config
```

### Permission issues

```bash
# Set permission manual
docker-compose -f docker-compose.prod.yml exec laravel.app chown -R www-data:www-data /var/www/html/storage
docker-compose -f docker-compose.prod.yml exec laravel.app chmod -R 775 /var/www/html/storage
```

### Database connection issues

- Pastikan database eksternal dapat diakses dari container
- Cek firewall dan network configuration
- Verifikasi credentials database

## Security Considerations

1. **Environment Variables**: Jangan commit file `.env.production` ke repository
2. **Database**: Gunakan database yang aman dengan SSL/TLS
3. **Network**: Batasi akses network sesuai kebutuhan
4. **Updates**: Update Docker images secara berkala
5. **Backup**: Backup data penting secara regular

## Performance Optimization

1. **Cache**: Gunakan Redis untuk cache jika diperlukan
2. **CDN**: Gunakan CDN untuk static assets
3. **Load Balancer**: Implement load balancer untuk high availability
4. **Monitoring**: Setup monitoring dan alerting
