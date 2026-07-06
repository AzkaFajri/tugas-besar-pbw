# Setup Guide - Multi-Role Raynor System

Panduan lengkap untuk setup dan menjalankan sistem Raynor dengan fitur multi-role.

## Prerequisites

- PHP 8.1+
- Laravel 11
- Composer
- MySQL / MariaDB
- Node.js & NPM (untuk asset compilation)

## Installation Steps

### Step 1: Clone / Pull Code
```bash
cd /path/to/laravel-raynor
git pull origin main  # atau copy code baru
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Environment Setup
Pastikan `.env` file sudah dikonfigurasi dengan benar:
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```
APP_NAME=Raynor
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_raynor
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Database Migration
```bash
php artisan migrate
```

Output yang diharapkan:
```
Migrating: 0001_01_01_000000_create_users_table
Migrated:  0001_01_01_000000_create_users_table (xxx ms)
Migrating: 0001_01_01_000001_create_cache_table
Migrated:  0001_01_01_000001_create_cache_table (xxx ms)
...
Migrating: 2026_07_03_000001_add_role_to_users_table
Migrated:  2026_07_03_000001_add_role_to_users_table (xxx ms)
Migrating: 2026_07_03_000002_add_user_id_to_po_and_invoices
Migrated:  2026_07_03_000002_add_user_id_to_po_and_invoices (xxx ms)
```

### Step 5: Database Seeding (Optional)
Untuk membuat test users:
```bash
php artisan db:seed
```

Test users yang akan dibuat:
1. **Admin User**
   - Email: admin@raynor.com
   - Password: password
   - Role: admin

2. **Owner User**
   - Email: owner@raynor.com
   - Password: password
   - Role: owner

3. **Test User**
   - Email: test@example.com
   - Password: password
   - Role: owner

### Step 6: Build Assets (Development)
```bash
npm run dev
# atau untuk production
npm run build
```

### Step 7: Start Development Server
```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

## Verification Checklist

Setelah setup selesai, pastikan hal berikut sudah terpenuhi:

### Database
```bash
# Cek kolom 'role' ada di tabel users
mysql> DESCRIBE users;
# Harusnya ada kolom 'role' dengan type ENUM

# Cek kolom 'id_user' ada di tabel purchase_order
mysql> DESCRIBE purchase_order;
# Harusnya ada kolom 'id_user' dengan type BIGINT FK

# Cek kolom 'id_user' ada di tabel invoices
mysql> DESCRIBE invoices;
# Harusnya ada kolom 'id_user' dengan type BIGINT FK
```

### Users
```bash
# Cek test users sudah ada
mysql> SELECT * FROM users;
# Harusnya ada 3 users dengan role 'admin' dan 'owner'
```

### Middleware
Pastikan middleware sudah terdaftar di `bootstrap/app.php`:
```php
'admin' => AdminMiddleware::class,
'owner' => OwnerMiddleware::class,
```

### Service Provider
Pastikan ProductObserver terdaftar di `app/Providers/AppServiceProvider.php`:
```php
Product::observe(ProductObserver::class);
```

## Testing Multi-Role Features

### Login sebagai Admin
1. Buka http://localhost:8000/login
2. Email: admin@raynor.com
3. Password: password
4. Klik Login
5. Verify: Bisa akses semua menu (Pegawai, Customer, Products, PO, Invoice)
6. Bisa create/edit/delete semua resource

### Login sebagai Owner
1. Logout dari admin
2. Buka http://localhost:8000/login
3. Email: owner@raynor.com
4. Password: password
5. Klik Login
6. Verify:
   - Bisa akses dan CRUD: Pegawai, Customer, Products
   - Bisa view saja (tanpa create/edit/delete): PO, Invoice
   - Tombol Create PO & Create Invoice tidak ada / disabled

### Test Customer Quick Create
1. Login sebagai admin
2. Navigasi ke Purchase Order > Buat PO Baru
3. Klik tombol "Tambah" di samping dropdown Customer
4. Isi form modal (Nama customer wajib diisi)
5. Klik "Tambah Customer"
6. Verify: Customer baru muncul di dropdown dan otomatis terpilih

### Test Product Price Sync
1. Login sebagai admin
2. Buat Purchase Order dengan salah satu product
3. Catat grand_total_po sebelum update
4. Edit product dan ubah harganya
5. Buka kembali PO yang sudah dibuat
6. Verify: grand_total_po sudah ter-update dengan harga baru

## Troubleshooting

### Error: "SQLSTATE[42S22]: Column not found"
**Solusi**:
```bash
php artisan migrate --fresh
php artisan db:seed
```

### Error: "Class not found: Illuminate\Support\Facades\Auth"
**Solusi**:
```bash
composer install
composer dump-autoload
```

### Login page tidak terload
**Solusi**:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Asset tidak loading (CSS/JS)
**Solusi**:
```bash
npm install
npm run dev
# atau
npm run build
```

### Email atau password salah saat login
**Solusi**:
1. Pastikan sudah seed: `php artisan db:seed`
2. Cek tabel users: `SELECT * FROM users;` di MySQL
3. Buat user baru via seeder atau manual di database

## Production Deployment

Sebelum push ke production:

### 1. Security Checks
```bash
# Update dependencies
composer update --no-dev
npm install --production

# Generate app key
php artisan key:generate

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Database Backup
```bash
# Backup existing database sebelum migrate
mysqldump -u root -p laravel_raynor > backup_20260703.sql
```

### 3. Environment
Update `.env` untuk production:
```
APP_ENV=production
APP_DEBUG=false
```

### 4. Run Migrations
```bash
php artisan migrate --force
```

### 5. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
```

## Reverting Changes (If Needed)

Jika perlu revert ke versi sebelumnya:

```bash
# Rollback migrations
php artisan migrate:rollback

# Atau rollback specific migration
php artisan migrate:rollback --target=2026_07_03_000002_add_user_id_to_po_and_invoices
```

## Support Files

File yang berkaitan dengan multi-role setup:
- `MULTIROLE_DOCUMENTATION.md` - Dokumentasi lengkap fitur
- `app/Models/User.php` - Updated dengan role & relationships
- `app/Models/PurchaseOrder.php` - Updated dengan user relationship
- `app/Models/Invoice.php` - Updated dengan user relationship
- `app/Http/Middleware/AdminMiddleware.php` - Admin role middleware
- `app/Http/Middleware/OwnerMiddleware.php` - Owner role middleware
- `app/Http/Controllers/AuthController.php` - Authentication controller
- `app/Http/Controllers/PurchaseOrderController.php` - Updated dengan authorization
- `app/Http/Controllers/InvoiceController.php` - Updated dengan authorization
- `app/Http/Controllers/CustomerController.php` - Updated dengan quick create
- `app/Observers/ProductObserver.php` - Product price sync logic
- `app/Providers/AppServiceProvider.php` - Observer registration
- `bootstrap/app.php` - Middleware registration
- `routes/web.php` - Route definitions dengan auth middleware
- `database/migrations/2026_07_03_*.php` - New migrations
- `database/factories/UserFactory.php` - Updated dengan role states
- `database/seeders/DatabaseSeeder.php` - Updated dengan test users
- `resources/views/auth/login.blade.php` - Login page
- `resources/views/purchase_order/create.blade.php` - Updated dengan customer quick create

---

**Setup Complete!** 🎉

Sistem Raynor dengan multi-role authentication sudah siap digunakan.
