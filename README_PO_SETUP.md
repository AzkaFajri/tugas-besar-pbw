Ringkasan singkat cara coba fitur CRUD dan Purchase Order:

1. Pastikan `.env` berisi konfigurasi database MySQL/MariaDB untuk database `raynor`.
2. Jalankan `composer install` jika belum, dan `php artisan key:generate`.
3. Aplikasi ini menggunakan tabel sudah ada di database (pegawai, customers, products, purchase_order, detail_products).
4. Jalankan server lokal:

```bash
php artisan serve
```

5. Akses halaman:
- `/pegawai` — CRUD pegawai
- `/customer` — CRUD customer
- `/products` — CRUD produk
- `/purchase_order` — Buat PO: pilih pegawai & customer, lalu klik "Lanjutkan isi pesanan" untuk menambah produk dari `products`.

Catatan: ini implementasi minimal untuk memulai. Jika ingin penambahan validasi, relasi model, atau UI yang lebih baik, saya dapat lanjutkan.
