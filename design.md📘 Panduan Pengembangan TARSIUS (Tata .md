# 📘 Panduan Pengembangan TARSIUS 
**(Tata Administrasi, Regulasi, Statistik, Informasi, keamanan website, dan layanan desa terintegrasi)**

## 1. Ringkasan Proyek
TARSIUS adalah sistem informasi terpadu berskala besar yang berfungsi sebagai pusat manajemen digital desa. Sistem ini menangani tata kelola administrasi, pengarsipan regulasi, penyajian data statistik, manajemen informasi publik, pemantauan keamanan website desa, serta pelayanan masyarakat secara terintegrasi.

## 2. Tech Stack & Infrastruktur
* **Backend:** Laravel (PHP)
* **Frontend:** Blade Template, Tailwind CSS
* **Database:** MySQL
* **Environment & Versioning:** Docker, Git (WSL)

## 3. Aturan UI/UX (Wajib Diikuti - Anti "Vibes Code")
Sistem ini memiliki banyak modul. Antarmuka harus sangat rapi, intuitif, dan tidak terlihat seperti *template admin* bawaan. Ikuti panduan Tailwind berikut dengan ketat:

* **Tipografi & Hierarki:** Gunakan font Sans-serif (seperti Inter). Pisahkan informasi dengan jelas menggunakan ketebalan font dan warna yang kontras (misal: `text-gray-900` untuk judul utama, `text-gray-500` untuk sub-teks).
* **Bentuk Organik:** Selalu gunakan `rounded-lg` atau `rounded-xl` pada kartu (cards), tombol, dan input form agar antarmuka terasa modern dan ramah pengguna.
* **Bayangan & Kedalaman (Shadows):** Hindari border garis keras. Gunakan bayangan halus (`shadow-sm`, `shadow-md`) dengan border transparan (`border border-gray-50`) untuk membedakan lapisan antar elemen.
* **Ruang Kosong (Whitespace):** Berikan jarak yang lega antar elemen. Gunakan padding yang besar pada *container* (minimal `p-6`). Ruang kosong membantu pengguna tidak merasa kewalahan saat melihat banyak data regulasi atau statistik.
* **Navigasi Modul (Penting):** Karena aplikasi ini memiliki banyak fitur (Administrasi, Regulasi, Statistik, dsb), gunakan *Sidebar* atau *Top Navbar* dengan ikon yang jelas dan pengelompokan menu (Dropdown/Accordion) yang rapi.
* **Palet Warna:** 
  * Background utama: `bg-gray-50`.
  * Wadah Konten/Card: `bg-white`.
  * Warna Aksi: Gunakan skema warna yang konsisten untuk setiap modul (misal: Biru untuk Administrasi, Hijau untuk Statistik, Merah/Kuning untuk peringatan Keamanan Website).

## 4. Aturan Penulisan Kode (Coding Rules)
* Ekstrak elemen UI menjadi **Laravel Blade Components** terpisah (misal: `<x-card>`, `<x-button>`, `<x-sidebar-link>`) agar kode tetap bersih dan bisa digunakan ulang di berbagai modul.
* Wajib 100% menggunakan *utility classes* dari Tailwind. Jangan menyisipkan CSS kustom (inline style).
* Pastikan tabel data (untuk regulasi atau informasi layanan) memiliki fitur responsif (`overflow-x-auto`) agar tetap rapi saat diakses melalui perangkat *mobile* (`md:`, `lg:`).

## 5. Fitur Utama & Modul (Scope)
Sistem dibagi menjadi beberapa modul utama:
* **Modul Administrasi & Layanan:** Form layanan masyarakat dan persuratan terintegrasi.
* **Modul Regulasi:** Repositori dan arsip digital untuk dokumen hukum/peraturan desa.
* **Modul Statistik:** Visualisasi data demografi dan indikator unggulan desa.
* **Modul Informasi & Keamanan Website:** Dasbor pemantauan status *uptime*, SSL, dan keamanan domain/website desa-desa.