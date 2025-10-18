# Hostinger Deployment Guide for TalentLit POS

## Step 1: Prepare Your Local Project

### 1.1 Build Production Assets
```bash
# Install dependencies
npm install
composer install --no-dev --optimize-autoloader

# Build frontend assets
npm run build
```

### 1.2 Create Production Environment File
Create a `.env` file with these production settings:

```env
APP_NAME="TalentLit POS"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# QR Code Configuration
QR_BASE_IP=yourdomain.com
```

## Step 2: Hostinger Setup

### 2.1 Create Database
1. Login to Hostinger Control Panel
2. Go to "Databases" → "MySQL Databases"
3. Create a new database
4. Create a database user with full privileges
5. Note down the database credentials

### 2.2 Upload Files
1. Go to "File Manager" in Hostinger Control Panel
2. Navigate to `public_html` folder
3. Upload all project files EXCEPT:
   - `node_modules/`
   - `tests/`
   - `.git/`
   - `storage/logs/`
   - `storage/framework/cache/`
   - `storage/framework/sessions/`
   - `storage/framework/views/`

## Step 3: Configure Web Server

### 3.1 Create .htaccess for public_html
Create this `.htaccess` file in your `public_html` directory:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Handle Angular and other client-side routing
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /index.php [QSA,L]
    
    # Security headers
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    
    # Gzip compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/plain
        AddOutputFilterByType DEFLATE text/html
        AddOutputFilterByType DEFLATE text/xml
        AddOutputFilterByType DEFLATE text/css
        AddOutputFilterByType DEFLATE application/xml
        AddOutputFilterByType DEFLATE application/xhtml+xml
        AddOutputFilterByType DEFLATE application/rss+xml
        AddOutputFilterByType DEFLATE application/javascript
        AddOutputFilterByType DEFLATE application/x-javascript
    </IfModule>
</IfModule>
```

### 3.2 Move Laravel Files
1. Move all Laravel files from `public_html` to `public_html/pos/`
2. Move contents of `public` folder to `public_html/`
3. Update `.env` file with correct paths

## Step 4: Database Migration

### 4.1 Run Migrations
Access your site via SSH or use Hostinger's terminal:
```bash
cd public_html/pos
php artisan migrate --force
php artisan db:seed
```

### 4.2 Generate Application Key
```bash
php artisan key:generate
```

## Step 5: File Permissions

### 5.1 Set Correct Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
```

## Step 6: Final Configuration

### 6.1 Update .env File
Update your `.env` file with:
- Correct database credentials
- Your domain name
- Production settings

### 6.2 Clear Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 7: Test Your Deployment

### 7.1 Test Basic Functionality
1. Visit your domain
2. Test user registration/login
3. Test POS functionality
4. Check QR code generation

### 7.2 Common Issues & Solutions

**Issue: 500 Internal Server Error**
- Check file permissions
- Verify .env file exists and is readable
- Check Laravel logs in `storage/logs/`

**Issue: Database Connection Error**
- Verify database credentials in .env
- Ensure database exists and user has permissions

**Issue: Assets Not Loading**
- Run `npm run build` locally
- Upload the `public/build/` folder
- Check file paths in .htaccess

**Issue: QR Codes Not Working**
- Update `QR_BASE_IP` in .env to your domain
- Ensure HTTPS is enabled

## Step 8: Security Considerations

### 8.1 Production Security
1. Set `APP_DEBUG=false` in .env
2. Use strong database passwords
3. Enable HTTPS (SSL certificate)
4. Regular backups
5. Keep Laravel and dependencies updated

### 8.2 File Security
- Never commit .env file to version control
- Use environment-specific configurations
- Regular security updates

## Step 9: Monitoring & Maintenance

### 9.1 Log Monitoring
- Check `storage/logs/laravel.log` regularly
- Set up error monitoring if possible

### 9.2 Performance Optimization
- Enable OPcache in PHP settings
- Use CDN for static assets
- Optimize database queries
- Regular cache clearing

## Troubleshooting

### Common Commands
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check application status
php artisan about
```

### Support
If you encounter issues:
1. Check Hostinger's PHP error logs
2. Verify Laravel logs in `storage/logs/`
3. Ensure all file permissions are correct
4. Verify database connectivity

---

**Note**: This guide assumes you have a standard Hostinger shared hosting plan. For VPS or dedicated hosting, some steps may vary.
