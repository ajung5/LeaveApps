# Sistem Permohonan Cuti Pegawai (Laravel 13)

**Versi sumber:** 1.0.0 · **Framework:** Laravel 13 / PHP 8.3+ · **PDF:** barryvdh/laravel-dompdf 3.1.x.

Aplikasi administrasi permohonan cuti dengan dua role **Admin** dan **User**, enam jenis cuti, ledger saldo tahunan, tahapan keputusan pejabat, dan tiga dokumen PDF dalam **format A4 portrait**. Form input pengajuan dibuat dalam **satu halaman aplikasi**. Formulir cuti, surat izin, dan surat permohonan masing-masing didesain untuk **satu lembar A4**; PDF gabungan tepat tiga halaman. Ada pemeriksaan jumlah halaman saat PDF dihasilkan.

## Struktur paket

Paket ini adalah **source code aplikasi Laravel lengkap** termasuk skeleton aplikasi, migrasi, model, controller, service, Blade views, CSS, PDF views, seeder, test, dan pengaturan `.env.example`.

Seperti standar proyek Laravel yang didistribusikan sebagai source, folder **`vendor/` tidak disertakan** karena harus dibuat melalui Composer dan ditentukan menurut OS/PHP pengguna. File database produksi, `.env` rahasia, dan data pegawai tidak disertakan.

## Instalasi di macOS (MacBook)

Prasyarat: **PHP 8.3 atau lebih baru** dengan ekstensi **mbstring, xml, dom, fileinfo, pdo_sqlite** (untuk SQLite) atau pdo_mysql/pgsql, **Composer**, dan akses internet untuk pemasangan dependensi pertama kali. Di macOS dengan Homebrew umumnya:

```bash
brew install php composer
php -v
composer --version
```

Extract ZIP, buka Terminal di folder aplikasi:

```bash
cd cuti-laravel-v1
composer install
cp .env.example .env
mkdir -p database
: > database/database.sqlite
php artisan key:generate
php artisan migrate --seed
```

Edit `.env` memakai editor teks:

```ini
ADMIN_NAME="Administrator Cuti"
ADMIN_EMAIL="admin@contoh.go.id"
ADMIN_PASSWORD="GANTI-DENGAN-PASSWORD-UNIK-MINIMAL-12-KARAKTER"
```

Lanjutkan:

```bash
php artisan db:seed --class=AdminSeeder
php artisan serve
```

Buka **http://127.0.0.1:8000** dan masuk dengan `ADMIN_EMAIL` serta `ADMIN_PASSWORD` yang ditentukan. **Setelah membuat Admin, hapus nilai `ADMIN_PASSWORD` dari `.env` dan jangan pernah commit file `.env`.**

> Bila menggunakan MySQL/PostgreSQL, ubah `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai instance database. Tidak perlu membuat file SQLite untuk driver tersebut.

## Konfigurasi pertama

1. Login sebagai Admin dan buka **Pengaturan Instansi**. Pastikan kop, nama pejabat, NIP, pangkat, dan format nomor surat benar.
2. Buka **Unit Kerja** untuk menambah bidang/bagian, kemudian **Data Pegawai** → **Tambah Pegawai**. Isi NIP, jabatan, unit kerja, email login, password pegawai, dan atasan langsung. Satu akun User terhubung satu profil pegawai.
3. Buka **Data Pegawai → Kelola → Saldo Cuti Tahunan**. Masukkan jatah N/N-1/N-2 yang *sudah diverifikasi petugas kepegawaian*. Contoh `delta=12` untuk saldo 12 hari, bukan hak otomatis.
4. Buka **Hari Libur**. Isi hari libur nasional dan cuti bersama yang sesuai agar perhitungan **hari kerja** akurat. Libur tidak diimpor secara otomatis.
5. Konfirmasi **Jenis Cuti**, satuan perhitungan hari kerja/kalender, dan persyaratan lampiran sesuai regulasi instansi.
6. User login lalu mengisi **Ajukan Cuti** (satu halaman).
7. Admin memverifikasi dan mencatat **pertimbangan atasan langsung** dan **keputusan pejabat berwenang** yang diperoleh secara resmi di luar sistem.
8. Setelah keduanya disetujui, Admin klik **Terbitkan Surat Izin**. Sistem membuat nomor surat dan membukukan saldo (khusus cuti tahunan).
9. Buka **PDF Gabungan** atau masing-masing PDF. Cetak, mintakan tanda tangan basah; **unggah scan final bertanda tangan** sebagai arsip privat.

## Alur status

`submitted → awaiting_supervisor → awaiting_authority → approved → issued`

Keputusan alternatif: `revision_required` (User bisa perbaiki dan kirim ulang), `deferred`, `rejected`, `cancelled`. Admin tidak bisa memproses permohonan miliknya sendiri.

## PDF dan referensi template

Template disusun dari dokumen yang diberikan: `FORM CUTI - November 2026.docx`.

- **Formulir Permintaan dan Pemberian Cuti:** delapan seksi (I–VIII), catatan N/N-1/N-2, pertimbangan dan keputusan, seluruhnya **1 halaman A4**.
- **Surat Izin Cuti:** kop instansi, nomor, identitas pegawai, periode, ketentuan, ruang tanda tangan **1 halaman A4**.
- **Surat Permohonan Cuti:** identitas, permohonan, alasan, ruang tanda tangan **1 halaman A4**.
- **Bundle:** 3 lembar A4, satu jenis surat per lembar.

Sistem memperbaiki ketidakkonsistenan redaksi pada contoh sumber (judul cuti tahunan tetapi paragraf menyebut cuti sakit) dengan menggunakan **jenis cuti yang dipilih** dalam teks surat.

File sumber logo instansi diekstraksi dari DOCX pengguna: `public/assets/img/lambang-subang.jpeg`. Bila logo/logo kop baru, ganti file ini dengan format JPEG bernama sama. Tidak ada tanda tangan pejabat yang dibubuhkan secara otomatis.

**Batas 1 halaman:** alasan maksimal 350 karakter dan alamat cuti 300 karakter. Apabila konten atau identitas yang sangat panjang menyebabkan overflow, generator menolak dengan HTTP 422 alih-alih membuat surat 2 halaman. Sesuaikan data/template setelah pemeriksaan pratinjau. Perlu **uji cetak fisik pada printer instansi** sebelum produksi.

## Kebijakan dan keterbatasan penting

- Aplikasi ditujukan terutama untuk cuti PNS. Field PPPK tersedia, **tetapi hak cuti dan persyaratannya tidak dibedakan otomatis**. Jika PPPK dipakai, Admin wajib memverifikasi aturan dan kelayakan secara manual.
- Mode perhitungan bawaan: Cuti Tahunan = hari kerja (Sabtu, Minggu dan libur terdaftar dikecualikan); lima kategori lainnya = hari kalender. **Verifikasi dan sesuaikan menurut aturan yang berlaku pada instansi**, jangan mengasumsikan semua jenis otomatis sah sesuai durasi yang dihitung.
- Jatah, kelayakan, dan pemindahan saldo N-2/N-1/N tidak ditentukan otomatis menurut seluruh peraturan kepegawaian. Sistem hanya membukukan saldo yang telah dimasukkan Admin. Pemakaian tahunan dicatat dari saldo paling lama terlebih dahulu.
- Cuti tahunan **lintas tahun** dipecah menjadi dua permohonan agar ledger tahunan konsisten.
- Tidak ada persetujuan elektronik/TTE atau integrasi SIASN/BKN. Admin hanya mencatat keputusan resmi pejabat.
- Surat izin `issued` berarti bernomor dan siap dimintakan tanda tangan; **bukan bukti sudah ditandatangani**. Scan bertanda tangan dikelola terpisah.
- Kalender hari libur memengaruhi permohonan baru; mengubah hari libur setelah permohonan dibuat **tidak menghitung ulang permohonan historis**.
- Data pegawai dan lampiran bersifat privat dan hanya dapat diunduh oleh User pemilik atau Admin yang berhak.
- PDF diturunkan dari Blade + Dompdf. Template mengutamakan keterbacaan di A4; tampilannya **mengikuti struktur sumber**, bukan salinan piksel-identik dari Word.

## Pengujian

Setelah `composer install`:

```bash
php artisan test
php artisan route:list
php -l app/Services/LeaveIssuer.php
```

Pengujian fitur mencakup: tamu diarahkan ke login, User tidak bisa membuka halaman Admin, durasi hari kerja, penolakan surat izin yang belum diterbitkan, User tidak bisa melihat permohonan orang lain, dan saldo hanya terdebit satu kali ketika surat diterbitkan.

**Catatan verifikasi penyusun:** lingkungan pembuatan paket tidak memiliki Composer maupun koneksi ke Packagist/GitHub, sehingga `composer install`, test integrasi Laravel, dan hasil PDF Dompdf tidak dapat dieksekusi di lingkungan tersebut. Semua file PHP diperiksa sintaksnya dengan `php -l`. Lakukan `php artisan test` dan uji cetak 3 dokumen setelah instalasi dependencies di Mac/server Anda sebelum produksi.

## Keamanan deployment produksi

Set `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-resmi...`, kunci APP_KEY unik, cookie/session hanya HTTPS, serta `LOG_LEVEL=warning`. Jalankan `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`. Web root wajib diarahkan ke folder `public/`, **bukan direktori proyek**. Backup database + `storage/app/private`, batasi hak akses server, aktifkan HTTPS, dan terapkan kebijakan retensi dokumen kepegawaian instansi.

Aplikasi ini adalah fondasi operasional, bukan pengganti proses otorisasi pejabat atau interpretasi peraturan kepegawaian.

### Opsi instalasi cepat (Mac)

Jika Composer dan ekstensi PHP yang dibutuhkan sudah terpasang, jalankan dari root folder:

```bash
bash setup-macos.sh
```

Setelah selesai, isi kredensial admin pada `.env`, jalankan AdminSeeder, lalu tes dan jalankan server sebagaimana petunjuk utama.

Untuk lampiran lebih dari 2 MB, atur `upload_max_filesize` setidaknya `12M` dan `post_max_size` setidaknya `16M` pada `php.ini` server, lalu restart layanan PHP.
