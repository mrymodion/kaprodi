# KAPRODI — Sistem Pendukung Akreditasi Program Studi

Aplikasi pendukung Kaprodi untuk peningkatan nilai akreditasi program studi
(target **Unggul**). Dibangun di atas **CodeIgniter 4.7** dengan arsitektur
**driver-based** sehingga dapat mengakomodasi beberapa instrumen akreditasi:

- ✅ **LAM INFOKOM 2.0 D3** (MVP — Manajemen Informatika)
- 🔜 LAM INFOKOM D4 / S1 / S2 / S3
- 🔜 BAN-PT, LAMDIK, LAMEMBA, LAM Teknik, dst.

PT yang dipakai sebagai data awal: **Universitas Teknologi Digital (UTD)**
dengan 11 program studi.

---

## Fitur Utama (roadmap)

| Modul | Status |
|---|---|
| Multi-PT, Multi-UPPS, Multi-Prodi | ✅ fondasi |
| Driver instrumen LAM INFOKOM 2.0 D3 (6 kriteria, 82 butir, 31 tabel LKPS) | ✅ seeder siap |
| Engine skoring auto (1.00 - 4.00) + agregasi per kriteria | ✅ skeleton |
| Master data: DTPR, Tendik, Mahasiswa, Tahun Akademik | ✅ migration |
| Modul Tridharma: Penelitian, PkM, Publikasi, HKI, Kerjasama, Prestasi | ✅ migration |
| Kurikulum OBE: CPL, CPMK, MK, Peta CPL | ✅ migration |
| VMTS, Sarpras, Keuangan, SPMI/PPEPP, Survei, Evidence, Audit Trail | ✅ migration |
| Generator LKPS (Excel) | 🔜 Fase 2 |
| Generator LED (Word) + Groq AI | 🔜 Fase 2 |
| Tracer study online + survei kepuasan | 🔜 Fase 2 |
| Integrasi PDDikti / SISTER / SINTA | 🔜 Fase 3 (default OFF) |

## Stack Teknis

- PHP 8.2+ · CodeIgniter 4.7
- MySQL 8 / MariaDB 10.6+
- CodeIgniter Shield (Auth & RBAC)
- PhpSpreadsheet (LKPS Excel), PhpWord (LED), dompdf (PDF)
- Groq AI (LLM) — model `llama-3.3-70b-versatile` & `llama-3.1-8b-instant`
- Bootstrap 5 + Bootstrap Icons

## Setup

### 1. Clone & install dependencies
```bash
git clone <repo> kaprodi && cd kaprodi
cp .env.example .env
composer install
```

### 2. Edit `.env`
Wajib di-set:
```
database.default.hostname = localhost
database.default.database = kaprodi
database.default.username = root
database.default.password = ...
groq.apiKey = gsk_xxxxxxxxxxxxxxxxxxxxx   # dari https://console.groq.com/keys
encryption.key = $(php -r 'echo bin2hex(random_bytes(16));')
```

### 3. Install Shield (auth & RBAC) — WAJIB sebelum migrate
```bash
composer require codeigniter4/shield
php spark shield:setup
```
Saat `shield:setup` ada konfirmasi, tekan `y` untuk menulis konfigurasi.
File `app/Config/AuthGroups.php` sudah disertakan repo ini dengan 11 role akreditasi,
jangan timpa.

### 4. Migrate & seed
```bash
php spark migrate --all
php spark db:seed DatabaseSeeder
```
> **Penting**: gunakan flag `--all`. Tanpa flag itu, hanya migration namespace `App\` yang jalan; tabel-tabel Shield (`users`, `auth_identities`, dll) tidak akan dibuat dan seeder demo user akan gagal.
Demo akun yang dibuat (password semua: **`rahasia123`**):

| Email | Role |
|---|---|
| superadmin@utd.ac.id | Super Administrator |
| kaprodi@utd.ac.id | Kaprodi MI D3 |
| sekprodi@utd.ac.id | Sekretaris Prodi |
| gkm@utd.ac.id | GKM |
| dosen@utd.ac.id | Dosen DTPR |
| tendik@utd.ac.id | Tendik |
| mahasiswa@utd.ac.id | Mahasiswa |
| asesor@utd.ac.id | Asesor |
| auditor@utd.ac.id | Auditor |
Setelah seeder berjalan, database berisi:
- 1 PT (UTD), 1 UPPS, 11 prodi, 5 tahun akademik (TS-4 … TS)
- 1 instrumen **LAM INFOKOM 2.0 D3** dengan 9 kriteria/bagian, **82 butir**, **31 tabel LKPS**, template LED 32 bagian
- Mapping otomatis untuk prodi MI D3 (kode 57401 & 57472)

### 5. Letakkan logo
Salin file logo UTD ke `public/assets/logo-utd.png`.

### 6. Jalankan
```bash
php spark serve
```
Buka http://localhost:8080/

## Arsitektur Driver Instrumen

Setiap instrumen LAM punya satu kelas driver yang
mengimplementasikan `Modules\Akreditasi\Contracts\AccreditationDriver`:
- `computeScores()` — hitung skor 1-4 per butir dari data DB
- `buildLkpsTable()` — bangun isi tabel LKPS spesifik
- `validateSubmission()` — aturan validasi pre-submit

Driver LAM INFOKOM D3 ada di `app/Modules/Akreditasi/Drivers/LamInfokomD3/`.
Menambah instrumen baru = buat folder driver baru + seeder baru, **tanpa**
mengubah engine.

## Skema Penilaian LAM INFOKOM 2.0 D3

| Bagian | Butir | Bobot | % |
|---|---:|---:|---:|
| A. Kondisi Eksternal | 1 | 4 | 1% |
| B. Profil UPPS | 1 | 4 | 1% |
| C1. Budaya Mutu | 10 | 40 | 10% |
| **C2. Relevansi Pendidikan** | **20** | **120** | **30%** |
| C3. Relevansi Penelitian | 15 | 32 | 8% |
| **C4. Relevansi PkM** | **15** | **100** | **25%** |
| C5. Akuntabilitas | 10 | 40 | 10% |
| C6. Diferensiasi Misi | 5 | 40 | 10% |
| D. Suplemen Prodi | 5 | 20 | 5% |
| **Total** | **82** | **400** | **100%** |

Distribusi IPO: Input 17 (15%) · Proses 27 (30%) · **Output 38 (55%)**.

## Catatan Keamanan

- **JANGAN** commit file `.env`.
- API key Groq disimpan di `.env`, bukan di kode.
- Default driver integrasi PDDikti/SISTER/SINTA dalam keadaan OFF.

## Lisensi

MIT
