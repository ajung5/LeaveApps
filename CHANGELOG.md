# Changelog

## 1.0.2 — 19 November 2026

- PDF A4: hapus pemisah `page-break-after` yang berpotensi menambah halaman kosong; gunakan pemisah antar-surat eksplisit.
- Tata letak Formulir Permintaan dan Pemberian Cuti dirapatkan tanpa menghilangkan kolom/isi atau mengurangi area tanda tangan menjadi nol.
- Generator PDF mencoba tiga tingkat kepadatan apabila sebuah surat meluber; tetap **menolak** hasil yang bukan tepat satu halaman per surat.
- Untuk PDF gabungan, setiap surat diperiksa sendiri dahulu, kemudian hasil gabungan diwajibkan tepat tiga halaman.
- Diagnostik mencantumkan jenis surat dan jumlah halaman aktual pada HTTP 422; detail teknis juga dicatat pada log Laravel.
- Tes regresi memverifikasi status HTTP 200, tipe PDF, header jumlah halaman, dan signature `%PDF-`.
- Label versi dashboard diperbarui.

## 1.0.1 — 19 November 2026

- Default `is_active` pada model User diselaraskan dengan migration.
- Tes regresi akses akun nonaktif, otorisasi data, dan debit saldo.

## 1.0.0 — 19 November 2026

- Laravel 13: login dengan dua role Admin/User tanpa registrasi publik.
- Dashboard, CRUD pegawai dan akun, pengaturan instansi, enam jenis cuti, kalender hari libur, laporan CSV.
- Pengajuan dalam satu halaman, kontrol benturan tanggal, durasi hari kerja/kalender, lampiran privat.
- Alur verifikasi Admin, pertimbangan atasan, keputusan pejabat, revisi dan penerbitan nomor surat.
- Ledger saldo tahunan, catatan koreksi Admin dan snapshot identitas pada saat penerbitan.
- Tiga jenis PDF A4 satu halaman per surat dengan pengujian jumlah halaman saat render.
- Arsip scan surat bertanda tangan dan pemeriksaan otorisasi akses unduh.
- Seeder master, pembuatan admin aman melalui environment, feature tests.

**Catatan:** Dependensi Composer tidak dibundel. Integrasi Laravel dan hasil Dompdf perlu diuji pada lingkungan pengguna setelah instalasi.
