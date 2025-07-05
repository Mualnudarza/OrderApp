
# 📦 Sistem Pemesanan Makanan - OrderApp

Repositori ini berisi sistem aplikasi **OrderApp** berbasis Laravel yang memungkinkan pengguna untuk melihat daftar makanan, mengelola kategori, serta melakukan pemesanan secara daring.

---

## 🛠️ Persyaratan Sistem

Pastikan Anda telah menginstal perangkat lunak berikut:

- PHP versi 8.1 atau lebih tinggi
- Composer
- MySQL / SQLite (tergantung pengaturan database)
- Git (untuk clone repository)
- Node.js dan NPM (jika ingin menjalankan Vite / Laravel Mix)

---

## 🚀 Langkah Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan aplikasi ini secara lokal:

### 1. Clone Repository

```bash
git clone https://github.com/nama-user/OrderApp.git
cd OrderApp/laravel-makanan
```

> Pastikan Anda berpindah direktori ke `laravel-makanan` karena seluruh kode Laravel berada di dalam folder tersebut.

### 2. Install Dependency PHP

```bash
composer install
```

### 3. Salin File .env

```bash
cp .env.example .env
```

> Di Windows:  
```bash
copy .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan pengaturan database lokal Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=orderapp_db
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Jalankan Migrasi Database

```bash
php artisan migrate
```

---

## 🖥️ Menjalankan Aplikasi

Masih berada di direktori `laravel-makanan`, jalankan perintah berikut:

```bash
php artisan serve
```

Aplikasi akan tersedia di:

```
http://127.0.0.1:8000
```


---

## 📂 Struktur Folder

```
OrderApp/
└── laravel-makanan/      ← Folder utama Laravel
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/
    └── ...
```


