# Pemrograman Web Dasar 2 — Simulasi Login (PHP + MySQL)

Repositori ini berisi **materi praktikum** berupa **dua aplikasi mini login** terpisah, plus **dokumentasi** dalam folder `docs/`. Proyek dibuat untuk pembelajaran **sesi PHP**, **PDO**, dan **perbandingan penyimpanan sandi**: teks polos (hanya lab) versus **hash bcrypt** (pola yang disarankan).

**Prasyarat:** PHP 7.4+ (disarankan), MySQL/MariaDB (misalnya [Laragon](https://laragon.org/)), ekstensi `pdo_mysql`.

---

## Isi repositori

| Folder | Isi |
|--------|-----|
| **`simulasi_login/`** | Login dengan kolom sandi **teks polos** di database — mudah diamati di phpMyAdmin. |
| **`simulasi_login_enkripsi/`** | Login dengan kolom **`password_hash`** (bcrypt) dan **`password_verify()`**. |
| **`docs/`** | Penjelasan edukatif perbedaan kedua pendekatan (`simulasi-login.md`). |

---

## Struktur singkat per aplikasi

```
simulasi_login/               simulasi_login_enkripsi/
├── config/database.php       ├── config/database.php
├── includes/                 ├── includes/
├── login.php                 ├── login.php
├── dashboard.php             ├── dashboard.php
├── logout.php                ├── logout.php
├── index.php                 ├── index.php
└── database/setup.sql        └── database/setup.sql
```

Database MySQL **berbeda** antar folder:

- `simulasi_login` → database **`simulasi_login`**
- `simulasi_login_enkripsi` → database **`simulasi_login_enkripsi`**

---

## Instalasi cepat (Laragon / XAMPP)

1. Letakkan folder proyek di web root, misalnya:  
   `C:\laragon\www\Pemrograman-Web-Dasar-2-Login`  
   atau klon repositori ini ke lokasi tersebut.

2. **Buat database** dengan mengimpor **kedua** file SQL (urutan bebas):

   - `simulasi_login/database/setup.sql`
   - `simulasi_login_enkripsi/database/setup.sql`

   Cara impor: phpMyAdmin → Import, atau CLI:

   ```bash
   mysql -u root < simulasi_login/database/setup.sql
   mysql -u root < simulasi_login_enkripsi/database/setup.sql
   ```

3. Sesuaikan **`config/database.php`** di masing-masing folder jika user/password MySQL Anda bukan `root` / kosong.

4. Buka di browser (sesuaikan path):

   - Non-enkripsi: `http://localhost/Pemrograman-Web-Dasar-2-Login/simulasi_login/`
   - Ter-hash: `http://localhost/Pemrograman-Web-Dasar-2-Login/simulasi_login_enkripsi/`

---

## Akun demo (sama di kedua simulasi)

| Username    | Password   |
|------------|------------|
| `admin`    | `admin123` |
| `mahasiswa`| `rahasia`  |

Perbedaannya: di **`simulasi_login`** sandi tampak apa adanya di tabel `users`; di **`simulasi_login_enkripsi`** yang tersimpan adalah string bcrypt di kolom `password_hash`.

---

## Dokumentasi lengkap

Baca **[docs/simulasi-login.md](docs/simulasi-login.md)** untuk:

- perbandingan tabel fitur;
- alur login → sesi → dashboard;
- penjelasan kunci sesi (`simulasi_*` vs `simulasi_enc_*`);
- catatan risiko dan praktik produksi.

---

## Lisensi & penggunaan

Materi ini untuk **pembelajaran**. Sandi teks polos **tidak** boleh ditiru di aplikasi produksi; gunakan hanya untuk lab terkontrol.

---

## Repositori terkait

Proyek utama akademik (`perkuliahan`) berada pada repositori terpisah; repositori ini memfokuskan folder simulasi login agar mudah dikloning untuk kelas atau tugas.
