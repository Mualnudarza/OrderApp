
# 📦 Aplikasi Pemesanan Makanan - OrderApp

Selamat datang di repositori **OrderApp**, aplikasi berbasis Laravel yang dibuat untuk memudahkan pengguna dalam melihat daftar menu makanan, mengelola kategori, dan melakukan pemesanan secara online.

---

## 🛠️ Apa Saja yang Dibutuhkan?

Sebelum mulai, pastikan perangkat berikut sudah terpasang di komputermu:

- PHP versi 8.1 atau lebih baru  
- Composer  
- MySQL atau SQLite  
- Git (untuk clone project)

---

## 🚀 Cara Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan aplikasi secara lokal:

### 1. Clone Project dari GitHub

```bash
git clone https://github.com/Mualnudarza/OrderApp.git
cd OrderApp/laravel-makanan
```

> Jangan lupa masuk ke folder `laravel-makanan`, karena di sanalah aplikasi Laravel-nya berada.

### 2. Install Dependency Laravel

```bash
composer install
```

### 3. Salin File Konfigurasi

```bash
cp .env.example .env
```

> Jika kamu pakai Windows:
```bash
copy .env.example .env
```

### 4. Buat App Key Laravel

```bash
php artisan key:generate
```

### 5. Atur Database

Buka file `.env` lalu sesuaikan bagian database seperti berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=orderapp_db
DB_USERNAME=root
DB_PASSWORD=
```

Jangan lupa buat dulu database-nya di MySQL sesuai nama di atas (`orderapp_db`).

### 6. Jalankan Migrasi

```bash
php artisan migrate
```

---

## 🖥️ Menjalankan Aplikasi

Masih di dalam folder `laravel-makanan`, jalankan perintah berikut:

```bash
php artisan serve
```

Aplikasi bisa dibuka lewat browser di alamat:

```
http://127.0.0.1:8000
```

---

## 📂 Struktur Folder Utama

```
OrderApp/
└── laravel-makanan/      ← Di sinilah semua file Laravel berada
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/
    └── ...
```

---

Semoga panduan ini membantu! Kalau ada kendala saat instalasi, jangan ragu untuk bertanya atau membuka issue di repository ini. 😊
