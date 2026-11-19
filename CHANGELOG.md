# Changelog

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
