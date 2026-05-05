# Dokumentasi simulasi login (dua folder)

Dokumen ini menjelaskan **dua aplikasi mini PHP** dalam repositori (`simulasi_login` vs `simulasi_login_enkripsi`) untuk memahami **proses login**. Keduanya sengaja **dipisah** agar fokus pembelajaran jelas: dulu mekanisme alur (form → database → sesi), lalu cara **menyimpan sandi** yang layak dipakai di aplikasi sungguhan.

**Lingkungan:** panduan mengacu pada **[Laragon](https://laragon.org/)** (Apache/Nginx + PHP + MySQL di Windows). Letakkan proyek di `...\laragon\www\...` dan jalankan **Start All** dari Laragon sebelum mengakses browser.

---

## Daftar isi

- [Mengapa ada dua folder?](#mengapa-ada-dua-folder)
- [Ringkasan perbandingan](#ringkasan-perbandingan)
- [Folder `simulasi_login` (sandi teks polos)](#folder-simulasi_login-sandi-teks-polos)
- [Folder `simulasi_login_enkripsi` (sandi ter-hash)](#folder-simulasi_login_enkripsi-sandi-ter-hash)
- [Alur umum setelah login](#alur-umum-setelah-login)
- [Sesi PHP: supaya tidak bentrok](#sesi-php-supaya-tidak-bentrok)
- [Persiapan database (WAJIB)](#persiapan-database-wajib)
- [Akun demo](#akun-demo)
- [Akses lewat browser](#akses-lewat-browser)
- [Catatan edukatif untuk mahasiswa](#catatan-edukatif-untuk-mahasiswa)

---

## Mengapa ada dua folder?

| Pertanyaan | Jawaban singkat |
|------------|-----------------|
| Kenapa tidak satu aplikasi dengan dua mode? | Memisahkan folder membuat **struktur file dan nama database** tidak bercampur. Mahasiswa bisa membuka salah satu proyek dan fokus ke satu konsep. |
| Apakah keduanya “login”? | Ya. Keduanya: form username/password → cek ke MySQL → jika valid, isi **sesi PHP** → halaman **dashboard** hanya untuk yang sudah login. |
| Apa yang **beda**? | Cara **menyimpan** sandi di database: teks yang bisa dibaca vs **hash bcrypt** yang tidak mengungkap sandi asli. |

---

## Ringkasan perbandingan

| Aspek | `simulasi_login` | `simulasi_login_enkripsi` |
|-------|-------------------|---------------------------|
| **Tujuan pembelajaran** | Memahami **alur** login end-to-end tanpa rumus kriptografi dulu | Memahami **praktik yang benar**: sandi tidak disimpan apa adanya |
| **Nama database MySQL** | `simulasi_login` | `simulasi_login_enkripsi` |
| **Tabel** | `users` | `users` (nama sama, **isi kolom beda**) |
| **Kolom sandi** | `password` — **teks polos** (sama seperti yang diketik user) | `password_hash` — string **bcrypt** panjang (bukan sandi asli) |
| **Pengecekan di PHP** | `hash_equals(teks_di_db, input)` (perbandingan string) | `password_verify(input, hash_di_db)` |
| **Skrip impor SQL** | `simulasi_login/database/setup.sql` | `simulasi_login_enkripsi/database/setup.sql` |
| **Warna / nuansa UI** | Navbar biru (default) | Navbar hijau — sebagai isyarat visual “versi lebih aman penyimpanan” |

**Istilah “enkripsi” di nama folder** di sini dipakai dalam arti **praktikum**: yang disimpan di database adalah **hash satu arah** (bcrypt), bukan sandi mentah. Secara teknis hash ≠ enkripsi simetris, tetapi untuk pemula naming folder membantu mengingat “bukan teks terbuka”.

---

## Folder `simulasi_login` (sandi teks polos)

### Fitur

- Halaman **login** (`login.php`), **dashboard** (`dashboard.php`), **logout** (`logout.php`).
- **Entry point** `index.php`: jika belum login → ke form login; jika sudah login → ke dashboard.
- Koneksi database lewat `config/database.php` (variabel `$pdo`).

### Mengapa sandi disimpan teks polos?

Untuk **transparansi pembelajaran**:

1. Buka **phpMyAdmin** → tabel `users` → kolom `password` terlihat persis seperti **`admin123`**, **`rahasia`**, dll.
2. Mahasiswa bisa membandingkan langsung: **input form** = **isi database**, sehingga mekanisme “cocokkan string” mudah dipahami.

### Risiko (wajib dipahami)

Jika database bocor (backup terupload, SQL injection, akses server), **semua sandi terbaca**. Itulah mengapa aplikasi produksi **tidak** boleh menyimpan sandi seperti ini.

---

## Folder `simulasi_login_enkripsi` (sandi ter-hash)

### Fitur

- Struktur halaman **sama**: `login.php`, `dashboard.php`, `logout.php`, `index.php`, `config/`, `includes/`.
- Database terpisah: **`simulasi_login_enkripsi`**.
- Kolom **`password_hash`** berisi string bcrypt (misalnya dimulai `$2y$10$...`).

### Mengapa memakai `password_verify`?

PHP menghitung ulang dari input pengguna dan membandingkan dengan hash secara aman. Anda **tidak** membandingkan string hash secara manual dengan `==`. Fungsi **`password_verify`** sudah dirancang untuk pekerjaan ini.

### Menambah user baru (konsep)

Saat registrasi atau ganti password di aplikasi nyata:

```php
$hash = password_hash($plainText, PASSWORD_DEFAULT);
// simpan $hash ke kolom password_hash — jangan simpan $plainText
```

---

## Alur umum setelah login

1. Browser mengirim **POST** ke `login.php` (username + password).
2. Skrip PHP membaca MySQL dengan **prepared statement** (`?`) untuk mengurangi risiko SQL injection.
3. Jika valid:
   - **`session_regenerate_id(true)`** — mitigasi session fixation (penjelasan lanjut di kode `includes/auth.php`).
   - Menyimpan **id**, **username**, **nama** di `$_SESSION` — **bukan** menyimpan password di sesi.
4. Redirect ke **dashboard**.
5. **Logout** mengosongkan sesi dan mengarahkan kembali ke form login.

---

## Sesi PHP: supaya tidak bentrok

Kedua folder dapat dibuka bergantian di browser yang sama:

| Folder | Awalan kunci sesi di `$_SESSION` |
|--------|----------------------------------|
| `simulasi_login` | `simulasi_*` (misalnya `simulasi_user_id`) |
| `simulasi_login_enkripsi` | `simulasi_enc_*` (misalnya `simulasi_enc_user_id`) |

Dengan begitu, status “sudah login” **tidak tertukar** antara dua aplikasi mini.

---

## Persiapan database (WAJIB)

Jalankan impor SQL **masing-masing** (database berbeda):

1. **Non-enkripsi:** impor `simulasi_login/database/setup.sql`  
   → membuat database `simulasi_login` dan data demo.

2. **Ter-hash:** impor `simulasi_login_enkripsi/database/setup.sql`  
   → membuat database `simulasi_login_enkripsi` dan data demo.

Sesuaikan **user/password MySQL** di `config/database.php` di dalam tiap folder jika instalasi Anda bukan `root` tanpa password (default Laragon umumnya seperti itu).

---

## Akun demo

**Sama** di kedua simulasi (yang berbeda hanya cara penyimpanan di MySQL):

| Username   | Password   | Nama (contoh)        |
|-----------|------------|----------------------|
| `admin`   | `admin123` | Administrator Demo   |
| `mahasiswa` | `rahasia` | Budi Contoh          |

---

## Akses lewat browser

Sesuaikan nama folder di `www` (contoh: proyek dikloning sebagai `Pemrograman-Web-Dasar-2-Login` atau disematkan di `mk_web`):

- Non-enkripsi: `http://localhost/Pemrograman-Web-Dasar-2-Login/simulasi_login/`
- Ter-hash: `http://localhost/Pemrograman-Web-Dasar-2-Login/simulasi_login_enkripsi/`

---

## Catatan edukatif untuk mahasiswa

1. **Urutan belajar yang masuk akal:** pahami dulu **`simulasi_login`** (alur + phpMyAdmin bisa dibaca polos), lalu **`simulasi_login_enkripsi`** (bandingkan isi kolom di database).
2. **Produksi:** gabungkan pola hash dengan **HTTPS**, validasi input, pembatasan percobaan login, dan pertimbangan **CSRF** pada form sensitif.
3. **Jangan** menyalin pola teks polos ke tugas akhir atau aplikasi kampus yang dipakai banyak orang — gunakan hanya untuk lab terkontrol.

---

*Dokumen ini mendampingi kode di folder `simulasi_login` dan `simulasi_login_enkripsi` pada repositori pemrograman web.*
