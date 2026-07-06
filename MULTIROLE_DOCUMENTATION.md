# Raynor - Multi-Role Purchase Order & Invoice Management System

## Overview

Raynor adalah sistem manajemen Purchase Order (PO) dan Invoice berbasis Laravel dengan fitur multi-role authentication. Sistem ini memungkinkan dua jenis pengguna (Admin dan Owner) untuk mengelola data bisnis dengan akses dan wewenang yang berbeda.

## Fitur Utama

### 1. Multi-Role Authentication
Sistem mendukung 2 peran pengguna:

#### Admin
- **Akses Penuh CRUD**:
  - Customer (Create, Read, Update, Delete)
  - Product (Create, Read, Update, Delete)
  - Pegawai/Karyawan (Create, Read, Update, Delete)
  - Purchase Order (Create, Read, Update, Delete)
  - Invoice (Create, Read, Update, Delete)
- Dapat mengelola semua aspek bisnis
- Setiap PO dan Invoice yang dibuat akan tercatat dengan user yang mengelolanya

#### Owner
- **CRUD untuk Data Master**:
  - Pegawai/Karyawan (Create, Read, Update, Delete)
  - Customer (Create, Read, Update, Delete)
  - Product (Create, Read, Update, Delete)
- **View Only untuk Dokumen**:
  - Purchase Order (Hanya bisa melihat dan mencetak)
  - Invoice (Hanya bisa melihat dan mencetak)
- Tidak bisa membuat atau mengubah PO dan Invoice

### 2. User Tracking pada PO dan Invoice
- Setiap Purchase Order menyimpan `id_user` (user yang membuat)
- Setiap Invoice menyimpan `id_user` (user yang membuat)
- Memudahkan audit trail dan tracking siapa yang mengelola dokumen

### 3. Customer Quick Create
- Fitur modal untuk membuat customer baru langsung dari form create PO
- Tidak perlu navigate ke halaman customer terlebih dahulu
- Customer baru langsung muncul di dropdown setelah ditambahkan

### 4. Product Price Synchronization
- Ketika harga produk (unit_price) diubah di master data
- Sistem otomatis mensinkronisasi dengan:
  - Semua DetailProduct yang menggunakan produk tersebut
  - Mengupdate amount (qty × harga baru)
  - Mengupdate total PO (subtotal, PPN, grand total)
  - Mengupdate total Invoice (subtotal, PPN, grand total)

## Setup & Installation

### 1. Migration Database
```bash
php artisan migrate
```

Migrasi yang akan dijalankan:
- `2026_07_03_000001_add_role_to_users_table.php` - Menambah kolom 'role' ke tabel users
- `2026_07_03_000002_add_user_id_to_po_and_invoices.php` - Menambah kolom 'id_user' ke tabel purchase_order dan invoices

### 2. Seed Database (Opsional)
```bash
php artisan db:seed
```

Ini akan membuat 3 pengguna test:
- **Admin**: admin@raynor.com / password
- **Owner**: owner@raynor.com / password
- **Test User**: test@example.com / password

### 3. Login
Akses halaman login di `/login` dan gunakan kredensial di atas

## Model & Relationships

### User Model
```php
- hasMany('purchaseOrders') - PO yang dibuat user
- hasMany('invoices') - Invoice yang dibuat user
- isAdmin() - Check if user is admin
- isOwner() - Check if user is owner
```

### PurchaseOrder Model
```php
- belongsTo('User') as 'user' - User yang membuat PO
- hasMany('DetailProduct') as 'details'
- belongsTo('Customer')
- belongsTo('Pegawai')
```

### Invoice Model
```php
- belongsTo('User') as 'user' - User yang membuat Invoice
- belongsTo('PurchaseOrder')
- belongsTo('Customer')
- belongsTo('Pegawai')
```

### Product Model
- Dilengkapi dengan ProductObserver untuk sync otomatis

## Route Protection

Semua route protected dengan authentication middleware:

### Public Routes
- `GET /` - Dashboard
- `GET /login` - Login page
- `POST /login` - Login action
- `POST /logout` - Logout action

### Protected Routes (Admin & Owner)
- `/pegawai` - Resource routes (CRUD)
- `/customer` - Resource routes (CRUD)
  - `POST /customer/quick-store` - AJAX untuk quick create
- `/products` - Resource routes (CRUD)
- `/purchase_order` - PO management
- `/invoice` - Invoice management

## Authorization

### Admin Middleware
- Hanya user dengan role='admin' yang bisa akses
- Mengembalikan 403 Unauthorized untuk user lain

### Owner Middleware
- Hanya user dengan role='owner' yang bisa akses
- Mengembalikan 403 Unauthorized untuk user lain

### Controller Authorization
- PurchaseOrderController::authorizeModify() - Cegah owner membuat/edit PO
- InvoiceController::authorizeModify() - Cegah owner membuat/edit Invoice

## Feature Details

### 1. Customer Quick Create
**File**: `resources/views/purchase_order/create.blade.php`

Cara menggunakan:
1. Saat membuat PO, klik tombol "Tambah" di samping dropdown Customer
2. Modal akan muncul dengan form input customer baru
3. Isi nama customer, alamat (opsional), dan no HP (opsional)
4. Klik "Tambah Customer"
5. Customer baru akan langsung muncul di dropdown dan otomatis terpilih

### 2. Product Price Sync
**File**: `app/Observers/ProductObserver.php`

Cara kerja:
1. Admin edit harga product di master data
2. ProductObserver mendeteksi perubahan 'unit_price'
3. Otomatis update semua DetailProduct dengan produk tersebut
4. Otomatis recalculate total PO dan Invoice terkait
5. Data selalu konsisten tanpa perlu manual update

Contoh:
- Product A harga Rp 50.000 (ada di 3 PO)
- Ubah harga Product A menjadi Rp 60.000
- Semua DetailProduct dengan Product A otomatis update amount
- Semua PO yang terkait otomatis recalculate totals
- Semua Invoice terkait otomatis update dengan total baru

### 3. User Tracking
- Setiap PO yang dibuat akan menyimpan user ID (admin atau owner) sebagai pembuat
- Setiap Invoice yang dibuat akan menyimpan user ID (admin atau owner) sebagai pembuat
- Data user dapat diakses via relationship: `$po->user` dan `$invoice->user`

## Database Tables

### users
```
- id (int) PK
- name (string)
- email (string) UNIQUE
- password (string) hashed
- role (enum: 'admin', 'owner')
- email_verified_at (timestamp)
- remember_token (string)
- created_at, updated_at (timestamps)
```

### purchase_order
```
- id_po (string) PK
- tgl_po (date)
- subtotal_po (decimal)
- ppn_po (decimal)
- grand_total_po (decimal)
- id_customer (string) FK
- id_pegawai (string) FK
- id_user (bigint) FK - NEW
```

### invoices
```
- id_invoice (string) PK
- id_po (string) FK
- tgl_invoice (date)
- subtotal_invoice (decimal)
- ppn_invoice (decimal)
- grand_total_invoice (decimal)
- id_customer (string) FK
- id_pegawai (string) FK
- notes (text)
- id_user (bigint) FK - NEW
- created_at, updated_at (timestamps)
```

## Controller Methods & Authorization

### PurchaseOrderController
- `authorizeModify()` - Private method untuk cegah owner modify PO
- Methods protected:
  - create, storeInitial (owner: FORBIDDEN)
  - edit (owner: FORBIDDEN)
  - addItem, updateItem, editItem, destroyItem (owner: FORBIDDEN)
  - destroy (owner: FORBIDDEN)
- Methods allowed untuk owner:
  - index (view list)
  - show (view detail)
  - print (print PO)

### InvoiceController
- `authorizeModify()` - Private method untuk cegah owner modify Invoice
- Methods protected:
  - create, store (owner: FORBIDDEN)
  - destroy (owner: FORBIDDEN)
- Methods allowed untuk owner:
  - index (view list)
  - showPO (view PO for invoice)
  - print (print invoice)

## Troubleshooting

### Login tidak bekerja
- Pastikan sudah migrate: `php artisan migrate`
- Pastikan sudah seed: `php artisan db:seed`
- Clear cache: `php artisan cache:clear`

### Role tidak terdeteksi
- Pastikan kolom 'role' ada di tabel users
- Jalankan migration: `php artisan migrate`
- Cek user record di database punya value 'admin' atau 'owner' di kolom role

### Product sync tidak jalan
- Pastikan ProductObserver terdaftar di AppServiceProvider
- Cek error log: `storage/logs/laravel.log`
- Pastikan Eloquent events tidak di-disable

### Customer quick create tidak bisa submit
- Pastikan JavaScript enabled di browser
- Cek browser console untuk error messages
- Pastikan endpoint `/customer/quick-store` accessible

## API Endpoints

### Authentication
- `POST /login` - Login dengan email & password
- `POST /logout` - Logout

### Customer Management (AJAX)
- `POST /customer/quick-store` - Create customer via AJAX (untuk PO create modal)
- Data: `{ nama_customer, alamat_customer?, hp_customer? }`
- Response: `{ success, message, customer }`

## Security Considerations

1. **CSRF Protection**: Semua POST/PUT/DELETE request di-protect dengan CSRF token
2. **Authentication**: Semua resource routes require authentication
3. **Authorization**: Role-based access control di-enforce via middleware dan controller logic
4. **SQL Injection**: Menggunakan Eloquent ORM yang prevent SQL injection
5. **Password**: Hashed menggunakan bcrypt

## Future Enhancements

Fitur yang bisa ditambahkan di masa depan:
1. Permission-based authorization (lebih granular dari role-based)
2. Activity logging untuk audit trail
3. Approval workflow untuk PO dan Invoice
4. Discount/Promo management
5. Multi-currency support
6. Payment tracking
7. Report generation & export
8. Dashboard analytics

## Support & Contact

Untuk pertanyaan atau issues, silahkan hubungi tim development.

---

**Version**: 1.0.0  
**Last Updated**: 3 Juli 2026  
**Status**: Production Ready
