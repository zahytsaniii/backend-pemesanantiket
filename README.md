# 🚌 Travel Booking API – Laravel 12 + Sanctum

Aplikasi pemesanan travel sederhana dengan dua role: **Admin** dan **Passenger (Penumpang)**.  
Fitur meliputi: manajemen jadwal travel, pemesanan tiket, pembayaran, laporan jumlah penumpang, dan invoice.

---

## 📌 Fitur Utama

### 🔑 **Auth**
- Register (Admin / Passenger)
- Login menggunakan Laravel Sanctum
- Logout
- Middleware role untuk memisahkan akses Admin & Passenger

### 👨‍💼 **Admin**
- CRUD Jadwal Travel
- Laporan jumlah penumpang per jadwal
- Melihat detail penumpang pada satu perjalanan

### 👤 **Passenger**
- Melihat jadwal travel tersedia
- Memesan tiket
- Validasi kuota otomatis
- Melakukan pembayaran (upload bukti)
- Mendapatkan invoice
- Melihat riwayat pemesanan

---

## 🛠️ **Teknologi**
- Laravel 12
- Laravel Sanctum
- PostgreSQL
- Storage local untuk bukti pembayaran

---

# 🚀 Instalasi & Setup

## 1. Clone Repository
	git clone <repository-url>
	cd travel-booking-api

## 2. Install Dependencies
	composer install

## 3. Copy Environment File
	cp .env.example .env

## 4. Konfigurasi `.env`
      Database (PostgreSQL)
	DB_CONNECTION=pgsql
	DB_HOST=127.0.0.1
	DB_PORT=5432
	DB_DATABASE=travel_db
	DB_USERNAME=(sesuaikan username)
	DB_PASSWORD=(sesuaikan password)

      Storage 
	php artisan storage:link

---

# 📦 Migrasi & Seeder

## Jalankan migrasi + seeder:
	php artisan migrate:fresh --seed

## Seeder akan menghasilkan:
   ### Admin:
     - **email:** admin@example.com  
     - **password:** password

   ### Passenger Dummy:
     - john@example.com / password
     - sarah@example.com / password

   ### Jadwal Travel Dummy:
     - Bandung (08:00)
     - Jakarta (09:00)
     - Bogor (07:30)

---

# 🧰 Menjalankan Server

## Untuk menjalankan server gunakan:
	php artisan serve

## Untuk mengakses API, endpoint yang dapat digunakan:
	http://localhost:8000/api
	http://127.0.0.1:8000/api
