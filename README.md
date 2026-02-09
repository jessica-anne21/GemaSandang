# 👕 Gema Sandang - E-Commerce Platform

**Gema Sandang** adalah aplikasi e-commerce berbasis web yang dirancang untuk memberikan pengalaman belanja pakaian yang modern dan efisien. Proyek ini dikembangkan sebagai bagian dari Laporan Kerja Praktik (KP) dengan fokus pada kemudahan instalasi melalui fitur **Progressive Web App (PWA)**.

---

## ✨ Fitur Utama
* **PWA Ready:** Mendukung instalasi aplikasi di desktop dan mobile secara native melalui Service Worker.
* **Manajemen User:** Sistem autentikasi dengan peran yang berbeda (Admin & Customer).
* **Katalog Dinamis:** Manajemen produk, stok, dan kategori pakaian secara real-time.
* **Responsive UI:** Antarmuka yang optimal diakses dari berbagai ukuran layar (Mobile, Tablet, Desktop).
* **Shopping Cart:** Sistem keranjang belanja yang intuitif sebelum proses checkout.

## 🛠️ Tech Stack
* **Core:** PHP (Framework Laravel)
* **Frontend:** HTML5, CSS3, JavaScript (PWA Integration)
* **Database:** MySQL (Database Name: `gemasandangdb`)
* **Tools:** Composer, NPM

## 📂 Struktur Penting 
- `public/pwa-install.js`: Logika penanganan instalasi PWA (Event `beforeinstallprompt`).
- `public/manifest.json`: Konfigurasi identitas PWA (Icon, Start URL, Display Mode).
- `database/`: Berisi skrip SQL untuk skema tabel seperti `users`, `products`, `orders`, dll.

## 🚀 Cara Instalasi & Menjalankan Proyek
1.  **Clone Repository:**
    ```bash
    git clone [https://github.com/jessica-anne21/GemaSandang.git](https://github.com/jessica-anne21/GemaSandang.git)
    ```
2.  **Instal Dependensi:**
    ```bash
    composer install
    npm install && npm run dev
    ```
3.  **Konfigurasi Database:**
    - Buat database baru bernama `gemasandangdb` di MySQL.
    - Impor file `.sql` yang ada di lampiran atau folder database.
    - Sesuaikan file `.env` untuk koneksi database.
4.  **Jalankan Server:**
    ```bash
    php artisan serve
    ```
5.  **Akses Aplikasi:**
    Buka `http://localhost:8000` di browser Anda. Untuk fitur PWA, pastikan mengakses melalui protokol yang didukung atau local environment yang sesuai.
