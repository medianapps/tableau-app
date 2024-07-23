<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Instalasi Laravel dengan Inertia.js dan Vue.js

Proyek ini menggunakan Laravel dengan Inertia.js dan Vue.js. Berikut adalah langkah-langkah untuk menginstal proyek ini di Environment pengembangan (dev) dan produksi (prod).

### Prasyarat

-   PHP (>=7.3)
-   Composer
-   Node.js dan npm

### Langkah 1: Clone Repository

Clone repository proyek ke komputer lokal Anda:

```bash
git clone https://github.com/medianapps/tableau-app.git
cd tableau-app
```

### Langkah 2: Instal Dependensi PHP

Instal dependensi PHP dengan Composer:

```bash
composer install
```

### Langkah 3: Instal Dependensi JavaScript

Instal dependensi JavaScript dengan npm:

```bash
npm install
```

### Langkah 4: Konfigurasi Environment

Salin file .env.example menjadi .env dan sesuaikan konfigurasi yang diperlukan (seperti pengaturan database):

```bash
cp .env.example .env
```

Generate aplikasi key:

```bash
php artisan key:generate
```

### Langkah 5: Migrasi dan Seed Database

Migrasi database dan jalankan seeder:

```bash
php artisan migrate --seed
```

### Langkah 6: Build dan Jalankan Aplikasi

Untuk Pengembangan (dev)
Jalankan perintah berikut untuk memulai server pengembangan dan kompilasi aset:

```bash
npm run dev
php artisan serve
```

Untuk Produksi (prod)
Jalankan perintah berikut untuk membangun aset untuk produksi:

```bash
npm run build
php artisan serve
```

### Langkah 7: Akses Aplikasi

Buka browser dan akses aplikasi pada URL http://localhost:8000.

Sekarang aplikasi Laravel Anda sudah dikonfigurasi dengan Inertia.js dan Vue.js. Anda bisa mulai mengembangkan aplikasi Anda dengan menggunakan kombinasi Laravel dan Vue.js.
