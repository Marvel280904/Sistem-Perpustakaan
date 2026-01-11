## 📚 Library Management System

A robust Web-based Library Management Application built with Laravel + Tailwind CSS, developed to fulfill Programmer Certification 06. FR.IA.02 TUGAS PRAKTEK DEMONTRASI requirements.

## 📋 PROJECT OVERVIEW

Certification    : PEMROGRAM (PROGRAMMER) — FR.IA.02 (IMT.01.15/SSK/LSP/X/2021)
Developer        : Marvel Hans Surjana
Platform         : Web (Desktop & Mobile Responsive)
Tech Stack       : Laravel, MySQL, Tailwind CSS
Architecture     : MVC (Model-View-Controller) + Service Pattern
Status           : Production Ready

=============================================================================

## 🎯 USER FLOW & FEATURES

🌍 1. PUBLIC ACCESS (MEMBER VIEW)
Route: / (Root)
- Flow: Pengunjung mengakses halaman utama.
- Features:
  - 📚 Book Catalog: Menampilkan seluruh koleksi buku perpustakaan dan jumlah stok tersedia secara real-time.
  - 🔍 Search: Pencarian buku realtime (Client-side) berdasarkan Judul/ISBN.
  - Read-Only: Anggota hanya bisa melihat daftar dan stok, tidak bisa mengubah data.

🔐 2. ADMIN AUTHENTICATION
Route: /login or /admin/dashboard
- Logic: Single-Role Authentication (Admin Only).
- Demo Credentials:
   - Email: admin@perpustakaan.com
   - Password: password123
- Security: Middleware auth memproteksi halaman admin. Jika belum login, redirect ke page Login.

⚡ 3. ADMIN DASHBOARD
Route: /admin/dashboard Setelah login sukses, Admin mengakses fitur manajemen utama:
A. 📚 Library Collection
- List lengkap semua buku.
- Stock Indicator: Menampilkan jumlah stok tersedia secara real-time.
B. 📋 Recent Borrowing (Riwayat)
- Tabel riwayat peminjaman.
- Status: Active (Sedang dipinjam) atau Returned (Sudah kembali).
- Action: Tombol "Return" untuk mengubah status untuk buku yang sudah dikembalikan dan menambah stok otomatis.

C. ➕ Add Loan (Pencatatan Peminjaman)
- Input Data Peminjam:
  - Nama Lengkap
  - Email
  - No. Telepon
- Input Buku: Select buku (Multiple selection allowed). Disable jika stock nya 0
- Date Logic:
  - Input: Loan Date (Tanggal Pinjam).
  - Auto Logic: Due Date otomatis terisi Loan Date + 7 Hari.
- Backend Process:
  - Database Transaction (Insert Data Peminjam + Decrement Stok Buku).

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
